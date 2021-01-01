<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Slider;

class SliderController extends Controller
{
    public function sliders()
    {
        $sliders = Slider::all();
        return view('carousel',compact('sliders'));
    }
}
