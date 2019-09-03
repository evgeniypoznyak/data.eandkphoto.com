<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    // protected $fillable = ['name', 'event_date', 'description', 'location'];
    protected $guarded = [];

//    public function histories()
//    {
//        return $this->belongsTo(History::class);
//    }


    public function getOneEventByYearMonthAndFolder($year, $month, $folder)
    {
        $month     = $this->convertMonthToNumber($month);
        $dateStart = Carbon::createFromDate($year, $month, '01')->toDateString();
        $dateEnd   = Carbon::createFromDate($year, $month, '31')->toDateString();

        return self::where(
            'event_date',
            '>=',
            $dateStart
        )->where(
            'event_date',
            '<=',
            $dateEnd
        )->where('name', '=', $folder)->get();
    }


    public function addBaseUrl($array)
    {
        $res = [];
//        if (!is_array($array)){
//            return $_SERVER["APP_URL"] . $array;
//        } else {
            foreach ($array as $file) {
                $res[] = $_SERVER["APP_URL"]. '/' . $file;
            }
//            }
//        }

        return $res;
//        $event = [];
//        $event['cover'] = $allFiles[0];
//
//        foreach ($allFiles as $index => $file) {
//            $res = explode('/', $file);
//            if (sizeof($res) === 6){
//            }
//        //    $event['cover'] = $allFiles[0][$file];
//        }
//
//        return $event;

    }


    public function convertMonthToNumber($month)
    {
        switch ($month) {
            case 'january':
                return 1;
            case 'february':
                return 2;
            case 'march':
                return 3;
            case 'april':
                return 4;
            case 'may':
                return 5;
            case 'june':
                return 6;
            case 'july':
                return 7;
            case 'august':
                return 8;
            case 'september':
                return 9;
            case 'october':
                return 10;
            case 'november':
                return 11;
            case 'december':
                return 12;
        }

    }

    /**
     * @param $month
     *
     * @return string
     */
    public function serializeMonth($month)
    {
        switch ($month) {
            case 'January':
                $month = '01-' . $month;
            break;
            case 'February':
                $month = '02-' . $month;
            break;
            case 'March':
                $month = '03-' . $month;
            break;
            case 'April':
                $month = '04-' . $month;
            break;
            case 'May':
                $month = '05-' . $month;
            break;
            case 'June':
                $month = '06-' . $month;
            break;
            case 'July':
                $month = '07-' . $month;
            break;
            case 'August':
                $month = '08-' . $month;
            break;
            case 'September':
                $month = '09-' . $month;
            break;
            case 'October':
                $month = '10-' . $month;
            break;
            case 'November':
                $month = '11-' . $month;
            break;
            case 'December':
                $month = '12-' . $month;
            break;
        }

        return $month;
    }


}
