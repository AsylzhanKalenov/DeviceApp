<?php

namespace App\Http\Controllers\operator;

use App\Cabinet;
use App\Center;
use App\Options;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OperatorController extends Controller
{
    //
    public function list_cabinet(){

        $cabinet = Cabinet::all();
        $cabinet1 = Cabinet::all();

        $center = Center::all();
        $option = Options::where('category', 'cabinet')->orderby('id_sort', 'asc')->get();
        return view('operator.cabinet.list', compact('cabinet', 'option', 'center', 'cabinet1'));
    }
    public function search(Request $request)
    {
        $cabinet1 = Cabinet::all();
        $id_center ='';
        if($request->get('center')!='') {
            $id_center = $request->get('center');
        }
        $ncab = array();
        $isncab = array();
        if(!$request->get('ncab')){
            foreach ($cabinet1 as $nc){
                if($nc!=''){
                    $ncab[]=$nc["nomer"];
                }
            }
        }
        else {
            $ncab = array_merge($ncab, $request->get('ncab'));
            $isncab = $ncab;
        }
        $deayt = array();
        $isdeayt = array();
        if(!$request->get('deayt')) {
            foreach ($cabinet1 as $de) {
                if ($de != '') {
                    $deayt[] = $de["deayt"];
                }
            }
        }
        else {
            $deayt = $request->get('deayt');
            $isdeayt = $deayt;
        }
//        DB::enableQueryLog();
        $cabinet = Cabinet::query()->where('ids',$id_center!=''?'=':'!=' ,$id_center!=''?$request->get('center'):0)->whereIn('nomer', $ncab)->whereIn('deayt', $deayt)->get();

//        dd(DB::getQueryLog());
        $center = Center::all();

        $option = Options::query()->where('category', 'cabinet')->orderby('id_sort', 'asc')->get();
        return view('operator.cabinet.list', compact('cabinet', 'option', 'center', 'cabinet1', 'isncab', 'isdeayt', 'id_center'));
    }

    public function show_cabinet($id)
    {
        //
        $cabinet = Cabinet::query()->find($id);
        $center = Center::query()->find($cabinet->ids);
        $option = Options::query()->where('category', 'cabinet')->get();
        return view('operator.cabinet.show', compact('cabinet', 'id', 'option', 'center'));
    }
}
