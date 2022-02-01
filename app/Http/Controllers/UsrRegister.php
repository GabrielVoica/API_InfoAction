<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class UsrRegister extends Controller
{
    public function registro(Request $request){
        return $request->input('name');
    }
}
