<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Integrations\Bci\Bci;

class HomeController extends Controller
{
    public function bci()
    {
        $bci = new Bci();

        try{
            dd($bci->requestAuthorize());
        }catch(\Exception $e){
            dd($e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine());
        }
    }
}
