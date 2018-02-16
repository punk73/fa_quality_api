<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class QualityController extends Controller
{
    //
	public function testDB(){

    	try {
		    DB::connection()->getPdo();
		} catch (\Exception $e) {
		    // die("Could not connect to the database.  Please check your configuration.");
		    die($e);
		}
	}

	public function getPhpInfo(){
		phpinfo();
	}
}
