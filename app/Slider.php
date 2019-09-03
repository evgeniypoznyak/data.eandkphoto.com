<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    // // N + 1 Problem solved
    // $slider = App\Slider::with('pictures')->get();

    public function pictures()
    {
        return $this->belongsToMany(Picture::class);
    }
}
