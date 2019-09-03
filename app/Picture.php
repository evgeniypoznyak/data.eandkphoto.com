<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot'
    ];


    public function sliders()
    {
        return $this->belongsToMany(Slider::class);
    }
}
