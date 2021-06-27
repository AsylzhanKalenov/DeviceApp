<?php

namespace App\Http\Controllers\module;

use App\Cabinet;
use App\Catalog;
use App\Center;
use App\Options;
use App\Peremesh;
use App\Razdel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeremeshController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function list_premesh(){

        $pere = DB::table('peremesh as p')->leftJoin('centers as ce','ce.id','=','p.idc')->leftJoin('centers as ce1','ce1.id','=','p.idc1')->leftJoin('catalog as cat','cat.id','=','p.idcat')
        ->select(['p.id as pid', 'ce.name as cename', 'p.idcab as pidcab', 'ce1.name as ce1name', 'p.idcab1 as pidcab1','p.date as pdate', 'p.idcat as pidcat', 'p.id_user', 'cat.ids2'])->where('p.id', '>', 0)->skip(0)->take(50)->orderBy('p.date', 'desc')->get();

        $pere_count = DB::table('peremesh as p')->leftJoin('centers as ce','ce.id','=','p.idc')->leftJoin('centers as ce1','ce1.id','=','p.idc')->leftJoin('catalog as cat','cat.id','=','p.idcat')
            ->select(['p.id as pid', 'ce.name as cename', 'p.idcab as pidcab', 'ce1.name as ce1name', 'p.idcab1 as pidcab1','p.date as pdate', 'p.idcat as pidcat', 'p.id_user', 'cat.ids2'])->where('p.id', '>', 0)->get()->count();

//        $pere = Peremesh::query()->skip(0)->take(50)->orderBy('date', 'desc')->get();
        $center = DB::table('centers')->where('del',0)->get();
        $cabinet1 = DB::table('cabinet')->where('del',0)->get();

        $req_num = 0;

        $num1 = 0;

        $pag = '';
        $left =0;
        $right = 0;
        for($i = 0; $i<$pere_count; $i+=50){
            if($num1==0)
                $pag.='<li '.($num1==$req_num?'class="active"':'').'><a onclick="get_ser_page('.$num1.')" class="small_text">'.($num1+1).'</a></li>';
            elseif($num1>=$req_num-4 && $num1<=$req_num+4)
                $pag.='<li '.($num1==$req_num?'class="active"':'').'><a onclick="get_ser_page('.$num1.')" class="small_text">'.($num1+1).'</a></li>';
            elseif($pere_count<$i+50)
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
        return view('modules.operator_peremesh.list_pere',
            [
                'pere' => $pere,
                'center' => $center,
                'cabinet1' => $cabinet1,
                'pag' => $pag
            ]);

    }


    public function list_premesh_search(Request $request){
        $arr = array();
        $cabinet1 = DB::table('cabinet')->where('del',0)->get();

        $data1 = '';
        $data2 = '';

        if ($request->ajax()) {
            $num = $request->num;
        }
        else{
            $num = 0;
        }


        if(isset($request->data1) && $request->data1!=''){
            $arr[] = ['p.date', '>=', $request->data1];
            $data1 = $request->data1;
        }
        if(isset($request->data2) && $request->data2!=''){
            $arr[] = ['p.date', '<=', $request->data2];
            $data2 = $request->data2;
        }
        $id_center ='';
        if($request->get('center')!='') {
            $id_center = $request->get('center');
            $arr[] = ['p.idc', '=', $id_center];
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
                    $ncab[]=$nc->id;
                }
            }
        }

        $pere = DB::table('peremesh as p')->leftJoin('centers as ce','ce.id','=','p.idc')->leftJoin('centers as ce1','ce1.id','=','p.idc1')->leftJoin('catalog as cat','cat.id','=','p.idcat')
        ->select(['p.id as pid', 'ce.name as cename', 'p.idcab as pidcab', 'ce1.name as ce1name', 'p.idcab1 as pidcab1','p.date as pdate', 'p.idcat as pidcat', 'p.id_user', 'cat.ids2'])->where('p.id', '>', 0)
        ->where($arr)->whereIn('p.idcab', $ncab)->skip($num*50)->take(50)->orderBy('p.date', 'desc')->get();

        $pere_count = DB::table('peremesh as p')->leftJoin('centers as ce','ce.id','=','p.idc')->leftJoin('centers as ce1','ce1.id','=','p.idc')->leftJoin('catalog as cat','cat.id','=','p.idcat')
            ->select(['p.id as pid', 'ce.name as cename', 'p.idcab as pidcab', 'ce1.name as ce1name', 'p.idcab1 as pidcab1','p.date as pdate', 'p.idcat as pidcat', 'p.id_user', 'cat.ids2'])->where('p.id', '>', 0)
            ->where($arr)->whereIn('p.idcab', $ncab)->get()->count();

        $req_num = $num;

        $num1 = 0;

        $pag = '';
        $left =0;
        $right = 0;
        for($i = 0; $i<$pere_count; $i+=50){
            if($num1==0)
                $pag.='<li '.($num1==$req_num?'class="active"':'').'><a onclick="get_ser_page('.$num1.')" class="small_text">'.($num1+1).'</a></li>';
            elseif($num1>=$req_num-4 && $num1<=$req_num+4)
                $pag.='<li '.($num1==$req_num?'class="active"':'').'><a onclick="get_ser_page('.$num1.')" class="small_text">'.($num1+1).'</a></li>';
            elseif($pere_count<$i+50)
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

        if ($request->ajax()) {
        $str ='lol';
        foreach ($pere as $c){

            $raz = Razdel::query()->find($c->ids2);

            $cab1 = $c->pidcab1!=0?(Cabinet::query()->select('nomer')->find($c->pidcab1)->nomer.'('.Cabinet::query()->select('deayt')->find($c->pidcab1)->deayt.')'):'';
            $str.= '<tr>
                <td align="center" style="padding:5px;">'.$c->ce1name.'</td>';
            $str .='
                <td align="center" style="padding:5px;">'.$cab1.'</td>';

            $str .='
                <td align="center" style="padding:5px;">'.$c->cename.'</td>';

            $str .='
                <td align="center" style="padding:5px;">'.Cabinet::query()->find($c->pidcab)->name.'('.Cabinet::query()->find($c->pidcab)->deayt.')</td>';

            $str .='
                <td align="center" style="padding:5px;">'.$raz["name"].'</td>

                <td align="center" style="padding:5px;">'.$c->pdate.'</td>';
            $str .='
                <td align="center" style="padding:5px;">'.($c->id_user!=0?\App\User::get_user($c->id_user)->fio:(' ')).'</td>';

            $str.='
            </tr>';

        }

            $output = array(
                'str' => $str,
                'count' => $pere_count,
                'num' => $request->num,
                'pag' => $pag,
            );

            return response()->json($output);

        }

//        $pere = Peremesh::query()->skip(0)->take(50)->orderBy('date', 'desc')->get();
        $center = DB::table('centers')->where('del',0)->get();

        return view('modules.operator_peremesh.list_pere',
            [
                'pere' => $pere,
                'center' => $center,
                'cabinet1' => $cabinet1,
                'data1' => $data1,
                'data2' => $data2,
                'id_center' => $id_center,
                'isncab' => $isncab,
                'pag' => $pag
            ]);
    }

    public function show_oper($id){
        $arr = array(25,26,27,28,29,36,37,40,41,42,43,44,45,46);

        $center = Center::query()->where('del','!=', 1)->whereIn('id', $arr)->get();
        $catalog = Catalog::query()->find($id);
        $options = Options::where('category', 'catalog')->where('category_id','=', 0)->where('select_fields','=', 1)->orderby('id_sort', 'asc')->get();

        return view('modules.operator_peremesh.show', compact('catalog', 'center', 'options'));
    }

    public function index_oper(){

        $arr = array(25,26,27,28,29,36,37,40,41,42,43,44,45,46);

        $catalog = Catalog::query()->select('catalog.*', 'razdel.name as bname')->leftJoin('razdel', 'catalog.ids2', '=','razdel.id')->
        where('catalog.ser_nom', '!=', '')->where('catalog.del', '!=', 1)->whereIn('idc', $arr)->skip(0)->take(50)->orderBy('catalog.id', 'desc')->get();

        $catalog_count = Catalog::query()->select('catalog.*', 'razdel.name as bname')->leftJoin('razdel', 'catalog.ids2', '=','razdel.id')->
        where('catalog.ser_nom', '!=', '')->where('catalog.del', '!=', 1)->whereIn('idc', $arr)->get()->count();

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

        $cabinet1 = Cabinet::query()->where('del', '=', 0)->get();


        $center = Center::all();
        $option = Options::where('category', 'catalog')->where('category_id','=', 0)->where('select_fields','=', 1)->orderby('id_sort', 'asc')->get();

        return view('modules.operator_peremesh.list', compact('catalog', 'option', 'center', 'cabinet1', 'pag'));
    }

    public function index_oper_search(Request $request){
        $cabinet1 = Cabinet::query()->where('del', '=', 0)->get();

        $arr = array();

        if ($request->ajax()) {
            $num = $request->get('num');

            if(isset($request->ser) && $request->ser!=''){
                $arr[] = ['catalog.id', '=', $request->ser];

            }
            if(isset($request->name) && $request->name!=''){
                $arr[] = ['razdel.name', 'like', '%' . $request->name . '%'];

            }
            $id_center ='';
            if($request->get('center')!='') {
                $id_center = $request->get('center');
                $arr[] = ['catalog.idc', '=', $id_center];
            }
            $ncab = array();
            if($request->get('ncab')){
                $ncab = array_merge($ncab, $request->get('ncab'));
            }
            else {
                foreach ($cabinet1 as $nc){
                    if($nc!=''){
                        $ncab[]=$nc["id"];
                    }
                }
            }

            $catalog = Catalog::query()->select('catalog.*', 'razdel.name as bname')->leftJoin('razdel', 'catalog.ids2', '=','razdel.id')->
            where('catalog.ser_nom', '!=', '')->where('catalog.del', '!=', 1)->where($arr)->whereIn('catalog.idcab', $ncab)->skip($num*50)->take(50)->orderBy('catalog.id', 'desc')->get();


            $option = Options::where('category', 'catalog')->where('category_id','=', 0)->where('select_fields','=', 1)->orderby('id_sort', 'asc')->get();
            $str='';
            foreach ($catalog as $ca){
                $str.='<tr>

                    <td align="center" style="text-align:left">'.$ca->id.'</td>

                    <td align="center">'.Center::query()->find($ca->idc)->name.'</td>

                    <td align="center">'.Cabinet::query()->find($ca->idcab)->nomer.'('.Cabinet::query()->find($ca->idcab)->deayt.')</td>

                    <td align="center">'.DB::table('razdel')->find($ca->ids2)->name.'</td>

                    <td align="center">'.DB::table('razdel')->find($ca->ids3)->name.'</td>';

                foreach ($option as $o){
                    $str.='<td align="center" style="padding:5px;">'.$ca[$o->name_en].'</td>';
                }

                $str.='<td align="center" style="padding:5px; text-align:center"><a href="'.route('admin.peremesh.show_oper', $ca->id).'" title="Переместить"><i class="icon-circle-arrow-up"></i></a>

                </td>
            </tr>';
            }



            $catalog_count = Catalog::query()->select('catalog.*', 'razdel.name as bname')->leftJoin('razdel', 'catalog.ids2', '=','razdel.id')->
            where('catalog.ser_nom', '!=', '')->where('catalog.del', '!=', 1)->where($arr)->whereIn('catalog.idcab', $ncab)->get()->count();

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


            $output = array(
                'str' => $str,
                'count' => $catalog_count,
                'num' => $request->num,
                'pag' => $pag,
            );

            return response()->json($output);

        }

        $ser ='';
        $name ='';
        if(isset($request->ser) && $request->ser!=''){
        $arr[] = ['catalog.id', '=', $request->ser];
        $ser = $request->ser;
        }
        if(isset($request->name) && $request->name!=''){
            $arr[] = ['razdel.name', 'like', '%' . $request->name . '%'];
            $name = $request->name;
        }
        $id_center ='';
        if($request->get('center')!='') {
            $id_center = $request->get('center');
            $arr[] = ['catalog.idc', '=', $id_center];
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

        $catalog = Catalog::query()->select('catalog.*', 'razdel.name as bname')->leftJoin('razdel', 'catalog.ids2', '=','razdel.id')->
        where('catalog.ser_nom', '!=', '')->where('catalog.del', '!=', 1)->where($arr)->whereIn('catalog.idcab', $ncab)->skip(0)->take(50)->orderBy('catalog.id', 'desc')->get();

        $catalog_count = Catalog::query()->select('catalog.*', 'razdel.name as bname')->leftJoin('razdel', 'catalog.ids2', '=','razdel.id')->
        where('catalog.ser_nom', '!=', '')->where('catalog.del', '!=', 1)->where($arr)->whereIn('catalog.idcab', $ncab)->get()->count();

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


        $center = Center::all();
        $option = Options::where('category', 'catalog')->where('category_id','=', 0)->where('select_fields','=', 1)->orderby('id_sort', 'asc')->get();

        return view('modules.operator_peremesh.list', compact('catalog', 'option', 'center', 'cabinet1', 'pag', 'id_center', 'isncab', 'ser', 'name'));

        }

    public function place_change(Request $request, $id){

        $arr = array();

        if($request->get("idc")!="" && $request->get('idcab')!="") {
            $arr["idc"] = $request->get("idc");
            $arr["idcab"] = $request->get('idcab');

            $cat = Catalog::query()->find($id);
            $catalog1 = Catalog::query()->find($id)->update($arr);

            $arr["idc1"] = $cat->idc;
            $arr["idcab1"] = $cat->idcab;
            $arr["date"] = date('Y-m-d H:i:s');
            $arr["idcat"] = $cat->id;
            $arr["id_user"] = Auth::user()->id;
            Peremesh::query()->create($arr);
        }
        $arr = array(25,26,27,28,29,36,37,40,41,42,43,44,45,46);

        $center = Center::query()->where('del','!=', 1)->whereIn('id', $arr)->get();
        $catalog = Catalog::query()->find($id);
        $options = Options::where('category', 'catalog')->where('category_id','=', 0)->where('select_fields','=', 1)->orderby('id_sort', 'asc')->get();
        $success = 'success';
        $error = 'Выберите кабинет!';
        if(isset($catalog1)){
            return view('modules.operator_peremesh.show', compact('catalog', 'center', 'options', 'success'));
        }
        else{
            return view('modules.operator_peremesh.show', compact('catalog', 'center', 'options', 'error'));
        }
    }

    public function index()
    {
        //
        $catalog = Catalog::query()->select('catalog.*', 'razdel.name as bname')->leftJoin('razdel', 'catalog.ids2', '=','razdel.id')->
        where('catalog.ser_nom', '!=', '')->skip(0)->take(50)->orderBy('bname', 'asc')->get();

        $cabinet1 = Cabinet::all();

        $center = Center::all();
        $option = Options::where('category', 'catalog')->where('category_id','=', 0)->where('select_fields','=', 1)->orderby('id_sort', 'asc')->get();
        return view('modules.peremesh.list', compact('catalog', 'option', 'center', 'cabinet1'));

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
