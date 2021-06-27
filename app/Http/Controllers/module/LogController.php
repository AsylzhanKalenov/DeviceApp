<?php

namespace App\Http\Controllers\module;


use App\Cabinet;
use App\Center;
use App\Http\Controllers\Controller;
use App\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    //
    public function index($num=0){


        $log = Log::query()->where('id','!=', 0)->skip($num)->take(50)->orderBy('date', 'desc')->get();

        return view('modules.log.list', compact('log'));
    }

    public function list(Request $request, $num=0){

        if ($request->ajax()) {

            $arr = array();
            $data = array();
            if (isset($request->user) && $request->user!=''){
                $arr[] = ['user', 'like', '%' . $request->user . '%'];
                $data["user"] = $request->user;
            }
            if (isset($request->data1) && $request->data1!=''){
                $arr[] = ['date', '>=', $request->data1];
                $data["data1"] = $request->data1;
            }
            if(isset($request->data2) && $request->data2!=''){
                $arr[] = ['date', '<=', $request->data2];
                $data["data2"] = $request->data2;
            }

            $req_num = isset($request->num)?$request->num:0;
            $log = Log::query()->where($arr)->skip($req_num*50)->take(50)->orderBy('date', 'desc')->get();
            $str ='';
            $num1 = 0;
            foreach ($log as $l){
            $str.='<tr><td>'.$l["date"].'</td><td>'.$l["user"].'</td><td>'.$l["center"].'</td><td>'.$l["action"].'</td><td>'.$l["vid"].'</td></tr>';
            }
            $count = Log::query()->where($arr)->get()->count();
            $pag = '';
            $left =0;
            $right = 0;
            for($i = 0; $i<$count; $i+=50){
                if($num1==0)
                $pag.='<li '.($num1==$req_num?'class="active"':'').'><a onclick="get_ser_page(' .$num1. ')" class="small_text">'.($num1+1).'</a></li>';
                elseif($num1>=$req_num-4 && $num1<=$req_num+4)
                $pag.='<li '.($num1==$req_num?'class="active"':'').'><a onclick="get_ser_page(' .$num1. ')" class="small_text">'.($num1+1).'</a></li>';
                elseif($count<$i+50)
                $pag.='<li '.($num1==$req_num?'class="active"':'').'><a onclick="get_ser_page(' .$num1. ')" class="small_text">'.($num1+1).'</a></li>';

                if($left==0 && $num1>$req_num+4) {
                    $pag .= '<li ' . ($num1 == $req_num ? 'class="active"' : '') . '><a class="small_text">...</a></li>';
                    $left++;
                }
                if($right==0 && $num1<$req_num-4){
                    $pag.='<li '.($num1==$req_num?'class="active"':'').'><a class="small_text">...</a></li>';
                    $right++;
                }
                $num1++;
            }
            $output = array(
                'str' => $str,
                'count' => $count,
                'num' => $request->num,
                'pag' => $pag,
            );

            return response()->json($output);
        }
        return view('modules.log.list', compact('log'));

    }
}
