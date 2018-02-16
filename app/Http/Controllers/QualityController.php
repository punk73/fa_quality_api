<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Schema;
use App\Quality;

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

	public function index(){
		//$Quality = DB::table('QUALITY')->first();
		$Quality = Quality::select('DATE001', 
			'MONTH001',
			'YEAR001',
			'LINE001',
			'SHIFT001',
			'IM_CODE',
			'PCB_CODE',
			'DESIGN_CODE',
			'MECHANISM_CODE',
			'ELECTRICAL_CODE',
			'MECHANICAL_CODE',
			'FINAL_ASSY_CODE',
			'OTHERS_CODE',
			'DEFECTIVE_CAUSE',
			'PLACE_DISPOSAL',
			'SYMPTOM',
			'QTY_REJECT'
		)->where('MONTH001', 2)->where('YEAR001', 2018)->where('DATE001', 16)->get();



		return [
			'message' => 'OK',
			'count' => count($Quality),
			'data' => $Quality
		];
		//return (array) $Quality;
	}
}
