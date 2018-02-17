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

	public function index(Request $request){
		//$Quality = DB::table('QUALITY')->first();

		// return date('Y');

		$Quality = Quality::select('DATE001', 
			'MONTH001',
			'YEAR001',
			'LINE001',
			'SHIFT001',
			'IM_CODE', //sum
			'PCB_CODE', //sum
			'DESIGN_CODE', //sum
			'MECHANISM_CODE', //sum
			'ELECTRICAL_CODE', //sum
			'MECHANICAL_CODE',//sum
			'FINAL_ASSY_CODE',//sum
			'OTHERS_CODE',//sum
			'DEFECTIVE_CAUSE',
			'PLACE_DISPOSAL',
			'SYMPTOM',
			'QTY_REJECT'
		);

		if (isset($request->date)) {
			# code...
			$Quality = $Quality->where('DATE001', $request->date );
		}else{
			$request->date = date('d');
			$Quality = $Quality->where('DATE001', $request->date  );
		}


		if (isset($request->month)) {
			# code...
			$Quality = $Quality->where('MONTH001', $request->month );
		}else{
			$request->month = date('m');
			$Quality = $Quality->where('MONTH001', $request->month );
		}

		if (isset($request->year)) {
			# code...
			$Quality = $Quality->where('YEAR001', $request->year );
		}else{
			$request->year = date('Y');
			$Quality = $Quality->where('YEAR001', $request->year );
		}

		if (isset($request->shift)) {
			# code...
			$Quality = $Quality->where('SHIFT001', $request->shift );
		}



		$Quality = $Quality->get();		
		//init 0 value
		$SMT = [];
		$PCB_CODE = [];
		$DESIGN_CODE = [];
		$MECHANISM_CODE = [];
		$ELECTRICAL_CODE = [];
		$MECHANICAL_CODE = [];
		$FINAL_ASSY_CODE = [];
		$OTHERS_CODE = [];
		$line = [];

		//count
		foreach ($Quality as $key => $value) {
			//didalam sini, di klasifikasi, based on line & shift
			if (!isset( $line[ $value['LINE001'].$value['SHIFT001'] ] )) { //kalau sudah ada sebelumnya
 				
 				$SMT[$value['LINE001'].$value['SHIFT001'] ]  = 0;
				$PCB_CODE[$value['LINE001'].$value['SHIFT001'] ] = 0;
				$DESIGN_CODE[$value['LINE001'].$value['SHIFT001'] ] = 0;
				$MECHANISM_CODE[$value['LINE001'].$value['SHIFT001']] = 0;
				$ELECTRICAL_CODE[$value['LINE001'].$value['SHIFT001']] = 0;
				$MECHANICAL_CODE[$value['LINE001'].$value['SHIFT001']] = 0;
				$FINAL_ASSY_CODE[$value['LINE001'].$value['SHIFT001']] = 0;
				$OTHERS_CODE[$value['LINE001'].$value['SHIFT001']] = 0;
			}

			$SMT[$value['LINE001'].$value['SHIFT001']]  = $SMT[$value['LINE001'].$value['SHIFT001']] + $value['IM_CODE'] ;
			$PCB_CODE[$value['LINE001'].$value['SHIFT001']] = $PCB_CODE[$value['LINE001'].$value['SHIFT001']] + $value['PCB_CODE'] ;
			$DESIGN_CODE[$value['LINE001'].$value['SHIFT001']] = $DESIGN_CODE[$value['LINE001'].$value['SHIFT001']] + $value['DESIGN_CODE'];
			$MECHANISM_CODE[$value['LINE001'].$value['SHIFT001']] = $MECHANISM_CODE[$value['LINE001'].$value['SHIFT001']]  + $value['MECHANISM_CODE'];
			$ELECTRICAL_CODE[$value['LINE001'].$value['SHIFT001']] = $ELECTRICAL_CODE[$value['LINE001'].$value['SHIFT001']] + $value['ELECTRICAL_CODE'] ;
			$MECHANICAL_CODE[$value['LINE001'].$value['SHIFT001']] = $MECHANICAL_CODE[$value['LINE001'].$value['SHIFT001']] + $value['MECHANICAL_CODE'];
			$FINAL_ASSY_CODE[$value['LINE001'].$value['SHIFT001']] = $FINAL_ASSY_CODE[$value['LINE001'].$value['SHIFT001']] + $value['FINAL_ASSY_CODE'];
			$OTHERS_CODE[$value['LINE001'].$value['SHIFT001']] = $OTHERS_CODE[$value['LINE001'].$value['SHIFT001']] + $value['OTHERS_CODE'];
			
			// $line[ $value['LINE001']]['AFTER_REPAIR_QTY'] = 

			$line[ $value['LINE001']][$value['SHIFT001']] = [
				'SMT' => $SMT[$value['LINE001'].$value['SHIFT001']] ,
				'PCB_CODE' => $PCB_CODE[$value['LINE001'].$value['SHIFT001']],
				'DESIGN_CODE' => $DESIGN_CODE[$value['LINE001'].$value['SHIFT001']],
				'MECHANISM_CODE' => $MECHANISM_CODE[$value['LINE001'].$value['SHIFT001']],
				'ELECTRICAL_CODE' => $ELECTRICAL_CODE[$value['LINE001'].$value['SHIFT001']],
				'MECHANICAL_CODE' => $MECHANICAL_CODE[$value['LINE001'].$value['SHIFT001']],
				'FINAL_ASSY_CODE' => $FINAL_ASSY_CODE[$value['LINE001'].$value['SHIFT001']],
				'OTHERS_CODE' => $OTHERS_CODE[$value['LINE001'].$value['SHIFT001']],
				'AFTER_REPAIR_QTY' => (
					$SMT[$value['LINE001'].$value['SHIFT001']] +
					$PCB_CODE[$value['LINE001'].$value['SHIFT001']] +
					$DESIGN_CODE[$value['LINE001'].$value['SHIFT001']] +
					$MECHANISM_CODE[$value['LINE001'].$value['SHIFT001']] +
					$ELECTRICAL_CODE[$value['LINE001'].$value['SHIFT001']] +
					$MECHANICAL_CODE[$value['LINE001'].$value['SHIFT001']] +
					$FINAL_ASSY_CODE[$value['LINE001'].$value['SHIFT001']] +
					$OTHERS_CODE[$value['LINE001'].$value['SHIFT001']]
				  )
			];

		}

		return [
			'message' => 'OK',
			'date'=> $request->date . $request->month . $request->year ,
			'count' => count($Quality),
			// 'data' => $Quality,
			'line' => $line
		];
		//return (array) $Quality;
	}
}
