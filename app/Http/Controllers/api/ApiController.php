<?php

namespace App\Http\Controllers\api;

use App\Cabinet;
use App\Catalog;
use App\Center;
use App\Options;
use App\Order;
use App\Razdel;
use App\Remont;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    //
    public function catalog_add(Request $request){

        if($request->classes==1 || $request->classes==2 || $request->classes==3 || $request->classes==5) {
            $raz = Razdel::query()->where('ids', $request->id)->get();
            $str = '<option value="">Выберите</option>';
            if ($raz) {

                foreach ($raz as $r) {
                    if ($r["name"] != 'Выберите' && $r["name"] != 'Нет элементов')
                        $str .= '<option value="' . $r["id"] . '">' . $r["name"] . '</option>';
                }
            }
            else{
                        $str.='<option value="">Нет элементов</option>';
            }
        }
        if($request->classes==4) {
            $raz = Cabinet::query()->where('ids', $request->id)->get();
            $str = '<option value="">Выберите</option>';
            if ($raz) {
                foreach ($raz as $r) {
                    if ($r["name"] != 'Выберите')
                        $str .= '<option value="' . $r["id"] . '">' . $r["deayt"] .' ('.$r["nomer"].')</option>';
                }
            }
            else{
                $str.='<option value="">Нет элементов</option>';
            }
        }
        $output = array(
            'str' => $str,
        );
        return response()->json($output);
    }

    public function order_add(Request $request){

        if ($request->ajax()) {
            $str = '';

            if ($request->classes == 1) {
                $str = '<option value="">Выберите кабинет</option>';

                $cab = Cabinet::query()->where('ids','=', $request->id)->get();
                if($cab){
                    foreach ($cab as $c){
                        $str.='<option value="'.$c->id.'">'.$c->nomer.'('.$c->deayt.')</option>';

                    }
                }

            }
            elseif ($request->classes == 2) {
                $str = '<option value="">Выберите оборудование</option>';
                $cat = DB::table('catalog')->leftJoin('razdel', 'catalog.ids2', '=', 'razdel.id')->select('catalog.id', 'razdel.name')->where('catalog.del', '=', 0)->where('catalog.idcab', '=', $request->id)->orderBy('catalog.ids2')->get();
                if($cat){
                    foreach ($cat as $c){
                        $str.= '<option value="'.$c->id.'">'.$c->name.'</option>';
                    }
                }
            }
            $output = array(
                'str' => $str,
            );

        }
        return response()->json($output);
        }

    public function export(Request $request){
        $result=false;
        $str = '';
        if ($request->ajax()) {

            if($request->get('do') && $request->get('do')=='export_cabinet') {
                $cabinet1 = Cabinet::all();
                $id_center = '';
                if ($request->get('center') != '') {
                    $id_center = $request->get('center');
                }
                $ncab = array();
                if (!$request->get('ncab')) {
                    foreach ($cabinet1 as $nc) {
                        if ($nc != '') {
                            $ncab[] = $nc["nomer"];
                        }
                    }
                } else {
                    $ncab = array_merge($ncab, $request->get('ncab'));
                }
                $deayt = array();
                if (!$request->get('deayt')) {
                    foreach ($cabinet1 as $de) {
                        if ($de != '') {
                            $deayt[] = $de["deayt"];
                        }
                    }
                } else {
                    $deayt = $request->get('deayt');
                }

                $option = Options::query()->where('category', 'cabinet')->orderby('id_sort', 'asc')->get();


                $str .= '<table class="table-striped" width="100%"><tr>
                <th>
                    <a href="/admin/cabinet/list/">Центр</a>
                </th>';
                foreach ($option as $o) {
                    $str .= '<th><a style=" white-space:nowrap">' . $o["name_ru"] . '</a></th>';
                }
                $str .= '</tr>';

                $cabinet = Cabinet::query()->where('ids',$id_center!=''?'=':'!=' ,$id_center!=''?$request->get('center'):0)->whereIn('nomer', $ncab)->whereIn('deayt', $deayt)->get();

                foreach ($cabinet as $c) {
                    $str .= '<tr><td align="center">' . $c["ids"] . '</td>';

                    foreach ($option as $o) {
                        $str .= '<td align="center">' . $c[$o["name_en"]] . '</td>';
                    }
                    $str .= '</tr>';
                }
                $str .= '</table>';
                $n = time();
                $filename = 'exportcabinet'.$n;
                $file = fopen(public_path().'/upload/testexport/'.$filename.'.xls','w+');
                $wr = fputs($file, $str);
                if ($wr)
                    $result = true;
            }

            if($request->get('do') && $request->get('do')=='export_catalog') {
                $catalog1 = Cabinet::query()->where('del', '=', 0)->get();
                $id_center ='';
                if($request->get('center')!='') {
                    $id_center = $request->get('center');
                }
                $name = '';
                if($request->get('name_cat')!='') {
                    $name = $request->get('name_cat');
                }
                $ncab = array();
                $isncab = array();
                if(!$request->get('ncab')){
                    foreach ($catalog1 as $nc){
                        if($nc!=''){
                            $ncab[]=$nc["id"];
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
                    foreach ($catalog1 as $de) {
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
                $catalog = Catalog::query()->leftJoin('razdel as r', 'r.id','=', 'catalog.ids2')->select('catalog.*')->where('idc',$id_center!=''?'=':'!=' ,$id_center!=''?$request->get('center'):0)->where('r.name','like','%'.$name.'%')->where('catalog.ser_nom','like','%'.$request->get('ser_num').'%')->whereIn('idcab', $ncab)->orderBy('id', 'desc')->get();
//                $catalog = Catalog::query()->leftJoin('razdel as r', 'r.id','=', 'catalog.ids2')->select('catalog.*')->where('catalog.idc',$id_center!=''?'=':'!=' ,$id_center!=''?$request->get('center'):0)->where('r.name','like','%'.$name.'%')->where('catalog.ser_nom','like','%'.$request->get('ser_num').'%')->whereIn('catalog.idcab', $ncab)->skip(0)->take(50)->orderBy('catalog.id', 'desc')->get();

//        dd(DB::getQueryLog());
                $center = Center::all();

                $option = Options::query()->where('category', 'catalog')->where('required_fields', '=', 1)->orderby('id_sort', 'asc')->get();

                $str.='<table class="table-striped" width="100%">

                <tr>

                <th width="60"> <a href="/admin/catalog/list/" style="white-space:nowrap">Штрих код</a> </th>

                <th width="150"> <a href="/admin/catalog/list/" style="white-space:nowrap">Центр</a> </th>

                <th> <a href="/admin/catalog/list/" style="white-space:nowrap">Кабинет</a> </th>

                <th> <a href="/admin/catalog/list/" style="white-space:nowrap">Наименование</a> </th>

                <th> <a href="/admin/catalog/list/" style="white-space:nowrap">Модель</a> </th>';

                foreach($option as $o){
                    $str.='<th><a href="/admin/center/list/">'.$o["name_ru"].'</a></th>';
                }
                $str.='</tr>';
                foreach($catalog as $ca){

                    $str.='<tr>

                    <td align="center" style="text-align:left">'.$ca->id.'</td>

                    <td align="center">'.get_center($ca->idc, 'name').'</td>

                    <td align="center">'.get_cabinet($ca->idcab, 'nomer').'('.get_cabinet($ca->idcab,'deayt').')</td>

                    <td align="center">'.get_razdel($ca->ids2, 'name').'</td>

                    <td align="center">'.get_razdel($ca->ids3, 'name').'</td>';

                    foreach($option as $o) {
                    $str.='<td align="center">'.$ca[$o->name_en].'</td>';
                    }
                    $str.='</tr>';
                    }
                $str.='</table>';
                $n = time();
                $filename = 'exportcatalog'.$n;
                $file = fopen(public_path().'/upload/testexport/'.$filename.'.xls','w+');
                $wr = fputs($file, $str);
                if ($wr)
                    $result = true;
            }
            if($request->get('do') && $request->get('do')=='export_center') {

                $center = Center::all();
                $option = Options::where('category', 'center')->orderby('id_sort', 'asc')->get();

                $str.='<table class="table-striped" width="100%">
                <tr>';
                foreach($option as $o){
                    $str.='<th><a href="/admin/center/list/">'.$o["name_ru"].'</a></th>';
                }
                $str.='</tr>';
                foreach ($center as $c){
                    $str.='<tr>';
                    foreach ($option as $o){
                     $str.='<td >'.$c[$o["name_en"]].'</td>';
                    }
                    $str.='</tr>';
                }
                $str.='</table>';
                $n = time();
                $filename = 'exportcenter'.$n;
                $file = fopen(public_path().'/upload/testexport/'.$filename.'.xls','w+');
                $wr = fputs($file, $str);
                if ($wr)
                    $result = true;
            }
            if($request->get('do') && $request->get('do')=='export_remont_archive') {
                $arr = array();

                $cabinet1 = Cabinet::all();
                $id_center = '';
                if ($request->get('center') != '') {
                    $id_center = $request->get('center');
                }
                $isncab = '';
                if ($request->get('ncab')) {
                    $arr[] = ['c.idcab', '=', $request->get('ncab')];
                    $isncab = $request->get('ncab');
                }
                $deayt = array();
                if ($request->get('deayt')) {
                    $arr[] = ['c.idcab', '=', $request->get('ncab')];
                }
                $shtrih = '';
                $arr[] = ['r.id', '!=', 0];
                if($request->get('ser')!=''){
                    $shtrih = $request->get('ser');
                    $arr[] = ['r.id', '=', $shtrih];
                    session(['ser' => $shtrih]);
                }
                if($request->get('data1')){
                    $shtrih = $request->get('data1');
                    $arr[] = ['r.data3', '>=', date('Y-m-d',strtotime($shtrih))];
                }

                if($request->get('data2')){
                    $shtrih = $request->get('data2');
                    $arr[] = ['r.data3', '<=', date('Y-m-d',strtotime($shtrih))];
                }

//        DB::enableQueryLog();

                $catalog = Remont::query()->join('catalog as c', 'c.id', '=', 'r.idcat')->select('r.id as idd', 'r.data3 as data3', 'c.*')->where('c.idc', $id_center != '' ? '=' : '!=', $id_center != '' ? $request->get('center') : 0)
                    ->where($arr)->whereIn('r.stat', ['Архив'])->skip(0)->take(50)->orderBy('r.id', 'desc')->get();
//        dd(DB::getQueryLog());


                $option = Options::query()->where('category', 'catalog')->where('category_id', 0)->where('select_fields', 1)->orderby('id_sort', 'asc')->get();

                $str.='<table class="table-striped table-hover table-border" width="100%">
            <tr>
                <th width="60" align="center" style="white-space:nowrap">Штрих код</th>
                <th width="60" align="center" style="white-space:nowrap">Дата</th>
                <th width="150" style="white-space:nowrap" align="center">Мед. центр</th>
                <th align="center">Кабинет</th>
                <th align="center" style="white-space:nowrap">Наименование</th>
                <th align="center" style="white-space:nowrap">Модель</th>';
                foreach($option as $o){
                    $str.='<th align="center" style="white-space:nowrap">'.$o->name_ru.'</th>';
                }
                $str.='</tr>';

                foreach($catalog as $c){

                    $str.='<tr>

                    <td align="center" style="padding:5px; padding-left:10px;">'.$c->idd.'</td>

                    <td align="center" style="padding:5px; padding-left:10px;">'.strval($c->data3).'</td>

                    <td align="center" style="padding:5px;">'.get_center($c->idc, 'name').'</td>

                    <td align="center" style="padding:5px;">'.get_cabinet($c->idcab, 'nomer').'('.get_cabinet($c->idcab, 'deayt').')'.'</td>

                    <td align="center" style="padding:5px;">'.get_razdel($c->ids2, 'name').'</td>

                    <td align="center" style="padding:5px;">'.get_razdel($c->ids3, 'name').'</td>';
                    foreach($option as $o) {
                    $str.='<td align="center" style="padding:5px;">'.$c[$o->name_en].'</td>';
                    }
                    $str.='</tr>';
                }
                $str.='</table>';

                $n = time();
                $filename = 'exportcenter'.$n;
                $file = fopen(public_path().'/upload/testexport/'.$filename.'.xls','w+');
                $wr = fputs($file, $str);
                if ($wr)
                    $result = true;

            }
            if($request->get('do') && $request->get('do')=='export_order') {
                $order = Order::all();
                $option = Options::where('category', 'order')->orderby('id_sort', 'asc')->get();

                $str.='<table class="table-striped" width="100%">
                <tr>';
                foreach($option as $o){
                    $str.='<th><a href="/admin/center/list/">'.$o["name_ru"].'</a></th>';
                }
                $str.='</tr>';
                foreach ($order as $or){
                    $str.='<tr>';
                    foreach ($option as $o){
                        $str.='<td align="center">'.$or[$o["name_en"]].'</td>';
                    }
                    $str.='</tr>';
                }
                $str.='</table>';
                $n = time();
                $filename = 'exportcenter'.$n;
                $file = fopen(public_path().'/upload/testexport/'.$filename.'.xls','w+');
                $wr = fputs($file, $str);
                if ($wr)
                    $result = true;

            }
            }
        $output = array(
            'res' => $result?'success':'error',
            'file' => public_path().'/upload/testexport/'.$filename,
            'filename' => $filename
        );
        return response()->json($output);
    }

}
