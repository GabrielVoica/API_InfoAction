<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class UsrRegister extends Controller
{
    public function registro(Request $request){

        $validated = $request->validate([
            'name' => 'max:4|required',
            'email' => 'max:10|required',


        ]);



            if($validated){
                return 'Correcto';
            }
            else{
                return 'Error';
            }

    }
}
