<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Team;
class testing extends Controller
{
    function result(){
      $response = Team::paginate(10);
     if(count($response)!=0){
     	return view("testing",array("data"=>$response));
     }
    }
}
