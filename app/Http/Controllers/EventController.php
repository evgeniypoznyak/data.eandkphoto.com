<?php

namespace App\Http\Controllers;

use App\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Imagick;
use JWTAuth;

class EventController extends Controller
{
    public function get()
    {
        $event  = new Event();
        $carbon = Carbon::now();
        $res    = $event::all()->sortBy('event_date')->toArray();
        $events = [];
        foreach ($res as $items) {
            $year                    = $carbon::createFromFormat('Y-m-d H:i:s', $items['event_date'])
                                              ->format('Y');
            $month                   = $carbon::createFromFormat('Y-m-d H:i:s', $items['event_date'])
                                              ->format('F');
            $month                   = $event->serializeMonth($month);
            $events[$year][$month][] = $items;
        }

        return $events;
        // return response()->json($events, 200);
    }


    public function test(Request $request)
    {
        $credentials = ['email' => 'evgene.pozniak@gmail.com', 'password' => '1234'];
        try {
            // attempt to verify the credentials and create a token for the user
            if ( ! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json([
            'token' => $token
        ], 200);


//        $event  = new Event();
//        $year   = 2017;
//        $month  = 'december';
//        $month  = $event->serializeMonth(ucfirst($month));
//        $folder = 'tasha-and-family-photoshoot-stockyards';
//        $path   = '/events/' . $year . '/' . $month . '/' . $folder;
//        $disc   = Storage::disk('www');
//
//        $dirs['large'] = $event->addBaseUrl($disc->files($path . '/large/'));
//        $dirs['small'] = $event->addBaseUrl($disc->files($path . '/small/'));
//        $dirs['cover'] = $event->addBaseUrl($disc->files($path));

        return response()->json(['dirs' => $dirs], 200);
    }


    public function put(Request $request)
    {

        $event              = new Event();
        $disc               = Storage::disk('www');
        $carbon             = Carbon::now();
        $date               = $carbon::createFromFormat('Y-m-d H:i:s', $request->sqlTime);
        $path               = '/events/' . $request->year . '/' . $request->month;
        $unzippedDir        = $this->createEventAndReturnDir($disc, $path, $request->binary);
        $event->event_date  = $date;
        $event->name        = array_last(explode("/", $unzippedDir));
        $event->description = $request->description;
        $event->location    = $request->location;
        $event->save();

        return response()->json($date, 200);
    }

    public function getOneEvent($year, $month, $folder)
    {
        $event       = new Event();
        $monthNumber = $event->convertMonthToNumber($month);
        $monthFolder  = $event->serializeMonth(ucfirst($month));
        $record      = $event->getOneEventByYearMonthAndFolder($year, $monthNumber, $folder);
        $disc        = Storage::disk('www');
        $path        = '/events/' . $year . '/' . $monthFolder . '/' . $folder;
        $dirs['large'] = $event->addBaseUrl($disc->files($path . '/large/'));
        $dirs['small'] = $event->addBaseUrl($disc->files($path . '/small/'));
        $dirs['cover'] = $event->addBaseUrl($disc->files($path));
        return response()->json(['event' => $record, 'dir' => $dirs ], 200);
    }


    public function createEventAndReturnDir($disc, $path, $binary)
    {
        file_put_contents('temp.zip', base64_decode($binary));
        if ( ! $disc->exists($path)) {
            $disc->createDir($path);
        }

        // todo return string or null - check
        $res = exec('unzip temp.zip -d ' . $disc->path($path));
        $disc->delete('temp.zip');

        if (isset($disc->directories($path)[0])) {
            $unzippedDir = $disc->directories($path)[0];
        }

        return $unzippedDir;
    }


    public function decodeImage($binnary)
    {

        $imageBlob = base64_decode($binnary);

        $imagick = new Imagick();
        $imagick->readImageBlob($imageBlob);

        //  header("Content-Type: image/png");

    }

}
