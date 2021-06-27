<?php

namespace App\Http\Controllers\dispatcher;

use App\Cabinet;
use App\Catalog;
use App\Center;
use App\Isp;
use App\Operator;
use App\Options;
use App\Razdel;
use App\Remont;
use App\Service;
use App\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RemontController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function show_added($id){

        $remont = Remont::query()->where('id', '=', $id)->first();

        $isp = Isp::query()->find($remont->idisp);

        $type = Type::query()->find($remont->idtype);

        return view('dispatcher.remont.show_added',
            [
                'remont' => $remont,
                'isp' => $isp,
                'type' => $type
            ]);

    }

    public function show_rem($id)
    {

        $remont = Remont::query()->where('id', '=',$id)->first();

        $remont1 = Remont::query()->where('idcat', '=', $remont->idcat)->get();

        $type = \App\Type::query()->where('id','=',$remont["idtype"])->first();

        $isp = Isp::query()->find($remont["idisp"]);

        $types = \App\Type::all();

        $isps = Isp::all();

        return view('dispatcher.remont.show_rem', compact('remont', 'type', 'isp', 'types', 'isps', 'remont1'));

    }

    public function list_archive(Request $request){

        if (isset($request) && $request->get('search') != '') {

            $id_center = '';
            $arr = array();
            $isncab = '';
            $data1 = '';
            $data2 = '';
            if(isset($request->ser) && $request->ser!=''){
                $arr[] = ['c.id', '=', $request->ser];
            }
            if(isset($request->name) && $request->name!=''){
                $arr[] = ['razdel.name', 'like', '%' . $request->name . '%'];
            }
            if(isset($request->center) && $request->center!=''){
                $arr[] = ['c.idc', '=', $request->center];
                $id_center = $request->center;
            }
            if(isset($request->data1) && $request->data1!=''){
                $arr[] = ['r.data1', '>=', $request->data1];
                $data1 = $request->data1;
            }
            if(isset($request->data2) && $request->data2!=''){
                $arr[] = ['r.data1', '<=', $request->data2];
                $data2 = $request->data2;
            }
            if(isset($request->ncab) && $request->ncab!=''){
                $arr[] = ['c.idcab', '=', $request->ncab];
                $isncab = $request->ncab;
            }


//        DB::enableQueryLog();

            $catalog = Remont::query()->join('catalog as c', 'c.id', '=', 'r.idcat')->select('r.id as idd', 'r.data3 as data3', 'c.*')->where('c.idc', $id_center != '' ? '=' : '!=', $id_center != '' ? $request->get('center') : 0)
                ->where($arr)->whereIn('r.stat', ['Архив'])->skip(0)->take(50)->orderBy('r.id', 'desc')->get();
//        dd(DB::getQueryLog());

            $cabinet1 = Cabinet::all();

            $center = Center::all();
            $option = Options::query()->where('category', 'catalog')->where('category_id', 0)->where('select_fields', 1)->orderby('id_sort', 'asc')->get();

            return view('dispatcher.remont.list_archive', compact('id_center', 'catalog', 'option', 'center', 'cabinet1', 'isncab', 'data1', 'data2'));

        }

//        DB::enableQueryLog();
        $arr = [
            ['c.ser_nom', '!=', '']
        ];
        $catalog = Remont::query()->join('catalog as c', 'c.id', '=', 'r.idcat')->select('r.id as idd', 'r.data3 as data3', 'c.*')->where($arr)->whereIn('r.stat', ['Архив'])->skip(0)->take(50)->orderBy('r.id', 'desc')->get();
//        dd(DB::getQueryLog());
        $cabinet1 = Cabinet::all();

        $center = Center::all();
        $option = Options::query()->where('category', 'catalog')->where('category_id', 0)->where('select_fields', 1)->orderby('id_sort', 'asc')->get();

        return view('dispatcher.remont.list_archive', compact('catalog', 'option', 'center', 'cabinet1'));

    }

    public function index()
    {
        //
        $arr = [
            ['r.stat', '!=', 'Архив'],
            ['r.stat', '!=', 'Дефектовано']
        ];

//        DB::enableQueryLog();

        $id_center = Operator::query()->where('id_user', '=', Auth::user()->id)->first();
        $id_arr = explode(',', $id_center["id_center"]);
        if($id_arr[sizeof($id_arr)-1]=='')
            unset($id_arr[sizeof($id_arr)-1]);

        $catalog = Remont::query()->leftJoin('catalog as c', 'c.id', '=', 'r.idcat')->select('r.id as rid', 'c.id as cid', 'c.ser_nom as cser_nom', 'c.in_nom as cin_nom', 'r.stat as rstat', 'r.data1 as rdata', 'c.idc', 'c.idcab', 'c.ids2', 'c.ids3', 'c.datae as cdatae', 'c.primech as cprimech')->where($arr)->skip(0)->take(50)->orderBy('r.id', 'desc')->get();
//        $cabinet1 = Cabinet::query()->where('del', 0)->get();

        $catalog_count = Remont::query()->leftJoin('catalog as c', 'c.id', '=', 'r.idcat')->select('r.id as rid', 'c.id as cid', 'c.ser_nom as cser_nom', 'c.in_nom as cin_nom', 'r.stat as rstat', 'r.data1 as rdata', 'c.idc', 'c.idcab', 'c.ids2', 'c.ids3', 'c.datae as cdatae', 'c.primech as cprimech')->where($arr)->orderBy('r.id', 'desc')->get()->count();

        $req_num = 0;

        $num1 = 0;

        $pag = '';
        $left =0;
        $right = 0;
        for($i = 0; $i<$catalog_count; $i+=50){
            if($num1==0)
                $pag.='<li '.($num1==$req_num?'class="active"':'').'><a onclick="get_ser_page('.$num1.')" class="small_text">'.($num1+1).'</a></li>';
            elseif($num1>=$req_num-4 && $num1<=$req_num+4)
                $pag.='<li '.($num1==$req_num?'class="active"':'').'><a onclick="get_ser_page('.$num1.')" class="small_text">'.($num1+1).'</a></li>';
            elseif($catalog_count<$i+50)
                $pag.='<li '.($num1==$req_num?'class="active"':'').'><a onclick="get_ser_page('.$num1.')" class="small_text">'.($num1+1).'</a></li>';

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

        $cabinet1 = DB::table('cabinet')->where('del', 0)->get();


        $center = DB::table('centers')->where('del', 0)->get();

        $option = Options::query()->where('category', 'catalog')->where('category_id', 0)->where('select_fields', 1)->orderby('id_sort', 'asc')->get();
//        dd(DB::getQueryLog());


        return view('dispatcher.remont.list', compact('catalog', 'option', 'center', 'cabinet1', 'pag'));
    }

    public function index_search(Request $request){
        $cabinet1 = Cabinet::query()->where('del', '=', 0)->get();
        $num = 0;
        if($request->ajax()){
            $num = $request->num;
        }
        $id_center = '';
        $arr = array();
        $data1 = '';
        $data2 = '';
        if(isset($request->ser) && $request->ser!=''){
            $arr[] = ['c.id', '=', $request->ser];
        }
        if(isset($request->name) && $request->name!=''){
            $arr[] = ['razdel.name', 'like', '%' . $request->name . '%'];
        }
        if(isset($request->center) && $request->center!=''){
            $arr[] = ['c.idc', '=', $request->center];
            $id_center = $request->center;
        }
        if(isset($request->data1) && $request->data1!=''){
            $arr[] = ['r.data1', '>=', $request->data1];
            $data1 = $request->data1;
        }
        if(isset($request->data2) && $request->data2!=''){
            $arr[] = ['r.data1', '<=', $request->data2];
            $data2 = $request->data2;
        }
        $ncab = array();
        $isncab = array();
        if($request->get('ncab')){
            $ncab = array_merge($ncab, $request->get('ncab'));
            $isncab = $ncab;
        }
        else {
            foreach ($cabinet1 as $nc){
                if($nc!=''){
                    $ncab[]=$nc["id"];
                }
            }
        }
        $arr[] = ['r.stat', '!=', 'Архив'];
        $arr[] = ['r.stat', '!=', 'Дефектовано'];

//        DB::enableQueryLog();

        $catalog = Remont::query()->leftJoin('catalog as c', 'c.id', '=', 'r.idcat')->select('r.id as rid', 'c.id as cid', 'c.ser_nom as cser_nom', 'c.in_nom as cin_nom', 'r.stat as rstat', 'r.data1 as rdata', 'c.idc', 'c.idcab', 'c.ids2', 'c.ids3', 'c.datae as cdatae', 'c.primech as cprimech')->where($arr)->whereIn('c.idcab', $ncab)->skip($num*50)->take(50)->orderBy('r.id', 'desc')->get();

        $catalog_count = Remont::query()->leftJoin('catalog as c', 'c.id', '=', 'r.idcat')->select('r.id as rid', 'c.id as cid', 'c.ser_nom as cser_nom', 'c.in_nom as cin_nom', 'r.stat as rstat', 'r.data1 as rdata', 'c.idc', 'c.idcab', 'c.ids2', 'c.ids3', 'c.datae as cdatae', 'c.primech as cprimech')->where($arr)->whereIn('c.idcab', $ncab)->orderBy('r.id', 'desc')->get()->count();

//        $cabinet1 = Cabinet::query()->where('del', 0)->get();

        $req_num = $num;

        $num1 = 0;

        $pag = '';
        $left =0;
        $right = 0;
        for($i = 0; $i<$catalog_count; $i+=50){
            if($num1==0)
                $pag.='<li '.($num1==$req_num?'class="active"':'').'><a onclick="get_ser_page('.$num1.')" class="small_text">'.($num1+1).'</a></li>';
            elseif($num1>=$req_num-4 && $num1<=$req_num+4)
                $pag.='<li '.($num1==$req_num?'class="active"':'').'><a onclick="get_ser_page('.$num1.')" class="small_text">'.($num1+1).'</a></li>';
            elseif($catalog_count<$i+50)
                $pag.='<li '.($num1==$req_num?'class="active"':'').'><a onclick="get_ser_page('.$num1.')" class="small_text">'.($num1+1).'</a></li>';

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
        $option = Options::query()->where('category', 'catalog')->where('category_id', 0)->where('select_fields', 1)->orderby('id_sort', 'asc')->get();

        if($request->ajax()){

            $str = '';
            foreach ($catalog as $c) {
                $cab = Cabinet::query()->find($c->idcab);
                $cen = Center::find($c->idc);
                $str .= '<tr>

                    <td align="center" style="padding:5px; padding-left:10px;">'.$c->cid.'</td>

                    <td align="center" style="padding:5px; padding-left:10px;">'.$c->rdata.'</td>

                    <td align="center" style="padding:5px;">'.$cen["name"].'</td>

                    <td align="center" style="padding:5px;">'.$cab->nomer.'('.$cab->deayt.')</td>

                    <td align="center" style="padding:5px;">'.Razdel::query()->find($c->ids2)->name.'</td>

                    <td align="center" style="padding:5px;">'.Razdel::query()->find($c->ids3)->name.'</td>';

                foreach ($option as $o){
                $str.='<td align="center" style="padding:5px;">'.$c['c'.$o->name_en].'</td>';
                }
                $str.='<td align="center" style="padding:5px;">'.$c->rstat.'</td>
                        <td align="right" width="70">

                            <a href="'.route('dispatcher.remont.show_rem', $c->rid).'" title="Просмотреть"><i class="icon-circle-arrow-up"></i></a>

                        </td>
                    </tr>';
            }
            $output = array(
                'str' => $str,
                'count' => $catalog_count,
                'num' => $request->num,
                'pag' => $pag,
            );

            return response()->json($output);
        }

        $cabinet1 = DB::table('cabinet')->where('del', 0)->get();

        $center = DB::table('centers')->where('del', 0)->get();

//        dd(DB::getQueryLog());
        return view('dispatcher.remont.list', compact('catalog', 'option', 'center', 'cabinet1', 'isncab', 'id_center', 'data1', 'data2', 'pag'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $service = Service::all();
        $remont = Remont::query()->where('id', '=', $id)->first();

        $remont1 = Remont::query()->where('idcat', '=', $remont->idcat)->get();
        $option = Options::query()->where('category', 'remont')->get();
        $isp = Isp::all();

        return view('dispatcher.remont.show', compact('remont', 'id', 'option', 'service', 'remont1', 'isp'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $arr['idtype'] = $request->get('idtype');
        $arr['comm1'] = $request->get('comm1');
        $arr['idisp'] = $request->get('idisp');
        $arr['stat'] = $request->get('stat');
        $arr['data2'] = date('Y-m-d');
        $arr['id_disp'] = Auth::user()->id;

        $remont1 = DB::table('remont')->where('id', $id)->update($arr);

        $res = false;
        if($remont1)
        $res = true;

        $remont = Remont::query()->where('id', '=',$id)->first();

        $type = \App\Type::query()->where('id','=',$remont["idtype"])->first();

        $isp = Isp::query()->find($remont["idisp"]);

        $types = \App\Type::all();

        $isps = Isp::all();

        return view('dispatcher.remont.show_rem',
            [
                'remont' => $remont,
                'type' => $type,
                'isp' => $isp,
                'types' => $types,
                'isps' => $isps,
                'res' => $res
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
