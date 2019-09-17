<?php

namespace App\Http\Controllers;

use App\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  \App\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function show(Slider  $slider)
    {


        $pictures = $slider->pictures;

        //$slider = Slider::find($id);

        //$pictures = $slider;

        //  return response()->json( $slider, 200);
        return response()->json( $pictures, 200);


        //
    }
}
