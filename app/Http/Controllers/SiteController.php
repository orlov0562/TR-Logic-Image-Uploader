<?php

namespace App\Http\Controllers;

class SiteController extends Controller
{
    public function actionIndex(){
        return view('site.index');
    }
}
