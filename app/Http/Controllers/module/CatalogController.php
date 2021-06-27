<?php

namespace App\Http\Controllers\module;

use App\Cabinet;
use App\Catalog;
use App\Center;
use App\Options;
use App\Razdel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
//      DB::enableQueryLog();

        //
        $catalog = Catalog::query()->where('id','!=', 0)->skip(0)->take(50)->orderBy('id', 'desc')->get();
        $catalog_count = Catalog::query()->where('id','!=', 0)->get()->count();
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

        $center = Center::query()->where('del','=',0)->get();
        $option = Options::where('category', 'catalog')->where('required_fields', '=', 1)->orderby('id_sort', 'asc')->get();
//      dd(DB::getQueryLog());

        return view('modules.catalog.list', compact('catalog', 'option', 'center', 'cabinet1', 'catalog_count', 'pag'));
    }


    public function photo(){

        return view('modules.photo');
    }
    public function search(Request $request){

        if ($request->ajax()) {
            $num = $request->get('num');

            $cabinet1 = Cabinet::query()->where('del', '=', 0)->get();

            $id_center ='';
            if($request->get('center')!='') {
                $id_center = $request->get('center');
            }
            $name = '';
            if($request->get('name_cat')!='') {
                $id_center = $request->get('name_cat');
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
            $option = Options::where('category', 'catalog')->where('required_fields', '=', 1)->orderby('id_sort', 'asc')->get();

//            $catalog = Catalog::query()->where('idc',$id_center!=''?'=':'!=' ,$id_center!=''?$request->get('center'):0)->whereIn('idcab', $ncab)->skip($num*50)->take(50)->orderBy('id', 'desc')->get();
            $catalog = Catalog::query()->leftJoin('razdel as r', 'r.id','=','catalog.ids2')->select('catalog.*')->where('catalog.idc',$id_center!=''?'=':'!=' ,$id_center!=''?$request->get('center'):0)->where('r.name','like','%'.$name.'%')->whereIn('catalog.idcab', $ncab)->skip($num*50)->take(50)->orderBy('catalog.id', 'desc')->get();

            $str ='';
            foreach($catalog as $ca){

            $str.='<tr>

                    <td align="center" style="text-align:left">'.$ca->id.'</td>

                    <td align="center">'.Center::query()->find($ca->idc)->name.'</td>

                    <td align="center">'.Cabinet::query()->find($ca->idcab)->nomer.'('.Cabinet::query()->find($ca->idcab)->deayt.')</td>

                    <td align="center">'.DB::table('razdel')->find($ca->ids2)->name.'</td>

                    <td align="center">'.DB::table('razdel')->find($ca->ids3)->name.'</td>';
                foreach ($option as $o){
                    $str.='<td>'.$ca[$o->name_en].'</td>';
                }
            $str.='<td align="right" width="70">
                        <form id="delete-form-'.$ca->id.'" method="post"
                              action="'.route('admin.catalog.destroy',$ca->id).'"
                              style="display: none">
                            '.csrf_field().' 
                            '.method_field('DELETE').'
                        </form>

                        <a href="'.route('admin.catalog.show', $ca->id).'" title="Просмотреть"><i class="icon-circle-arrow-up"></i></a> | <a href="'.route('admin.catalog.edit', $ca->id).'" title="Редактировать"><i class="icon-edit"></i></a> |
                        <a href="" onclick="
                                if(confirm(\'Are you sure, You Want to delete this?\'))
                                {
                                event.preventDefault();
                                document.getElementById(\'delete-form-'.$ca->id.').submit();
                                }
                                else{
                                event.preventDefault();
                                }" title="Удалить"><i class="icon-minus-sign"></i></a>

                    </td>
                </tr>';
            }


            $catalog_count = Catalog::query()->where('idc',$id_center!=''?'=':'!=' ,$id_center!=''?$request->get('center'):0)->whereIn('idcab', $ncab)->get()->count();

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

        $cabinet1 = Cabinet::query()->where('del', '=', 0)->get();

        $id_center ='';
        if($request->get('center')!='') {
            $id_center = $request->get('center');
        }
        $name ='';
        if($request->get('name_cat')!='') {
            $name = $request->get('name_cat');
        }
        $ser_num ='';
        if($request->get('ser_num')!='') {
            $ser_num = $request->get('ser_num');
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
        $catalog = Catalog::query()->leftJoin('razdel as r', 'r.id','=', 'catalog.ids2')->select('catalog.*')->where('catalog.idc',$id_center!=''?'=':'!=' ,$id_center!=''?$request->get('center'):0)->where('r.name','like','%'.$name.'%')->where('catalog.ser_nom','like','%'.$request->get('ser_num').'%')->whereIn('catalog.idcab', $ncab)->skip(0)->take(50)->orderBy('catalog.id', 'desc')->get();

//        $catalog_count = Catalog::query()->where('idc',$id_center!=''?'=':'!=' ,$id_center!=''?$request->get('center'):0)->whereIn('idcab', $ncab)->get()->count();

        $catalog_count = Catalog::query()->leftJoin('razdel as r', 'r.id','=', 'catalog.ids2')->select('catalog.*')->where('catalog.idc',$id_center!=''?'=':'!=' ,$id_center!=''?$request->get('center'):0)->where('r.name','like','%'.$name.'%')->where('catalog.ser_nom','like','%'.$request->get('ser_num').'%')->whereIn('catalog.idcab', $ncab)->skip(0)->take(50)->orderBy('catalog.id', 'desc')->get()->count();

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
//        dd(DB::getQueryLog());
        $center = Center::all();

        $option = Options::where('category', 'catalog')->where('required_fields', '=', 1)->orderby('id_sort', 'asc')->get();

        return view('modules.catalog.list', compact('catalog', 'option', 'center', 'cabinet1', 'isncab', 'isdeayt', 'id_center', 'catalog_count', 'pag','name', 'ser_num'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $option = Options::where('category', 'catalog')->orderby('id_sort', 'asc')->get();
        $center = Center::all();
        $cabinet = Cabinet::all();
        $razdel = Razdel::query()->where('ids', 0)->get();


        return view('modules.catalog.add', compact('option','center', 'cabinet', 'razdel'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $option = Options::where('category', 'catalog')->get();

        $required = array();

        foreach ($option as $op){
            if($op->required_fields==1){
                if($op->type_field=='i11'){
                    $format = str_replace('|',',', $op->format_file);
                    $required[$op->name_en] = 'required|image|mimes:'.$format.'|'.$op->size_file;
                }
                elseif($op->type_field=='i12'){
                    $format = str_replace('|',',', $op->format_file);
                    $required[$op->name_en] = 'required|file|mimes:'.$format.'|'.$op->size_file;
                }
                else{
                    $required[$op->name_en] = 'required';
                }
            }
        }
        $required['classe'] = 'required';
        $required['classe1'] = 'required';
        $required['classe2'] = 'required';
        $required['classe4'] = 'required';

        $this->validate($request, $required);

        $arr = array();
        $count = 0;


        $raz = Razdel::query()->where('name',$request->get('class'))->where('ids', 0)->first();

        if($raz){
            $arr["ids"] = $raz->id;
        }
        else{
            $raz = Razdel::query()->create([
                'name' => $request->get('class'),
                'ids' => 0
            ]);
            $arr["ids"] = $raz->id;
        }

        $raz = Razdel::query()->where('name',$request->get('class1'))->where('ids', $arr["ids"])->first();

        if($raz){
            $arr["ids1"] = $raz->id;
        }
        else{
            $raz = Razdel::query()->create([
                'name' => $request->get('class1'),
                'ids' => $arr["ids"]
            ]);
            $arr["ids1"] = $raz->id;
        }

        $raz = Razdel::query()->where('name',$request->get('class2'))->where('ids', $arr["ids1"])->first();

        if($raz){
            $arr["ids2"] = $raz->id;
        }
        else{
            $raz = Razdel::query()->create([
                'name' => $request->get('class2'),
                'ids' => $arr["ids1"]
            ]);
            $arr["ids2"] = $raz->id;
        }

        $raz = Razdel::query()->where('name',$request->get('class3'))->where('ids', $arr["ids2"])->first();

        if($raz){
            $arr["ids3"] = $raz->id;
        }
        else{
            $raz = Razdel::query()->create([
                'name' => $request->get('class3'),
                'ids' => $arr["ids2"]
            ]);
            $arr["ids3"] = $raz->id;
        }

        $raz = Razdel::query()->where('name',$request->get('class4'))->where('ids', $arr["ids3"])->first();

        if($raz){
            $arr["ids4"] = $raz->id;
        }
        else{
            $raz = Razdel::query()->create([
                'name' => $request->get('class4'),
                'ids' => $arr["ids3"]
            ]);
            $arr["ids4"] = $raz->id;
        }


        foreach ($option as $op) {
            if ($op->type_field == 'i11' && $op->name_en!='photo'){
                $image = $request->file($op->name_en);
                if(isset($image)) {
                    $img = time() .'_'.$count. '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/upload/images');
                    $image->move($destinationPath, $img);
                    $arr[$op->name_en] = $img;
                }
            }
            elseif ($op->type_field == 'i12'){
                $file = $request->file($op->name_en);
                if(isset($file)) {
                    $img = time() .'_'.$count. '.' . $file->getClientOriginalExtension();
                    $destinationPath = public_path('/upload/files');
                    $file->move($destinationPath, $img);
                    $arr[$op->name_en] = $img;
                }
            }
            else {
                $arr[$op->name_en] = $request->get($op->name_en);
            }
            $count++;
        }

        if(intval($request->get('phot_col'))>0) {
            $arr["photo"] = '';
            for ($i = 1; $i <= intval($request->get('phot_col')); $i++) {
                $image = $request->file('ph_' . $i);
                if (isset($image)) {
                    $img = 'cat_'.time() . '_' . $i . '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/upload/images');
                    $image->move($destinationPath, $img);
                    $arr["photo"] .= $img.';';
                }
            }
        }

        $arr["del"] = 0;
        $arr["idc"] = $request->get("ids");
        $arr["idc1"] = $request->get("ids1");
        $arr["idcab"] = $request->get("ncab");


        Catalog::query()->create($arr);


        if ($request->input('ids') == null)
            return redirect()->route('admin.catalog.create');
        else
            return redirect()->route('admin.catalog.index');
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
        $catalog = Catalog::query()->find($id);
        $option = Options::query()->where('category', '=', 'catalog')->orderBy('id_sort')->get();


        return view('modules.catalog.show', compact('catalog', 'option'), [
            'catalog' => $catalog
        ]);

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
        $catalog = Catalog::query()->find($id);

        $name = Razdel::query()->find($catalog->ids2);
        $model = Razdel::query()->find($catalog->ids3);
        $category = Razdel::query()->find($name->ids);
        $class = Razdel::query()->find($category->ids);
        $producer = Razdel::query()->find($catalog->ids4);

        $razdel1 = array(
            'id_cl2' => $catalog->ids2,
            'name_cl2' => $name->name,
            'id_cl3' => $catalog->ids3,
            'name_cl3' => $model->name,
            'id_cl' => $class->id,
            'name_cl' => $class->name,
            'id_cl1' => $category->id,
            'name_cl1' => $category->name,
            'id_cl4' => $producer->id,
            'name_cl4' => $producer->name,
        );

        $option = Options::query()->where('category', '=', 'catalog')->orderBy('id_sort')->get();

        $center = Center::all();
        $cabinet = Cabinet::all();
        $razdel = Razdel::query()->where('ids', 0)->get();

        return view('modules.catalog.edit', [
            'catalog' => $catalog,
            'option' => $option,
            'center' => $center,
            'cabinet' => $cabinet,
            'razdel' => $razdel,
            'razdel1' => $razdel1
        ]);
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
        $catalog = Catalog::query()->find($id);

        $option = Options::where('category', 'catalog')->get();

        $required = array();

        foreach ($option as $op){
            if($op->required_fields==1){
                if($op->type_field=='i11'){
                    $format = str_replace('|',',', $op->format_file);
                    $required[$op->name_en] = 'required|image|mimes:'.$format.'|'.$op->size_file;
                }
                elseif($op->type_field=='i12'){
                    $format = str_replace('|',',', $op->format_file);
                    $required[$op->name_en] = 'required|file|mimes:'.$format.'|'.$op->size_file;
                }
                else{
                    $required[$op->name_en] = 'required';
                }
            }
        }

        $this->validate($request, $required);

        $arr = array();
        $count = 0;

        $raz = Razdel::query()->where('name',$request->get('class'))->where('ids', 0)->first();

        if($raz){
            $arr["ids"] = $raz->id;
        }
        else{
            $raz = Razdel::query()->create([
                'name' => $request->get('class'),
                'ids' => 0
            ]);
            $arr["ids"] = $raz->id;
        }

        $raz = Razdel::query()->where('name',$request->get('class1'))->where('ids', $arr["ids"])->first();

        if($raz){
            $arr["ids1"] = $raz->id;
        }
        else{
            $raz = Razdel::query()->create([
                'name' => $request->get('class1'),
                'ids' => $arr["ids"]
            ]);
            $arr["ids1"] = $raz->id;
        }

        $raz = Razdel::query()->where('name',$request->get('class2'))->where('ids', $arr["ids1"])->first();

        if($raz){
            $arr["ids2"] = $raz->id;
        }
        else{
            $raz = Razdel::query()->create([
                'name' => $request->get('class2'),
                'ids' => $arr["ids1"]
            ]);
            $arr["ids2"] = $raz->id;
        }

        $raz = Razdel::query()->where('name',$request->get('class3'))->where('ids', $arr["ids2"])->first();

        if($raz){
            $arr["ids3"] = $raz->id;
        }
        else{
            $raz = Razdel::query()->create([
                'name' => $request->get('class3'),
                'ids' => $arr["ids2"]
            ]);
            $arr["ids3"] = $raz->id;
        }

        $raz = Razdel::query()->where('name',$request->get('class4'))->where('ids', $arr["ids3"])->first();

        if($raz){
            $arr["ids4"] = $raz->id;
        }
        else{
            $raz = Razdel::query()->create([
                'name' => $request->get('class4'),
                'ids' => $arr["ids3"]
            ]);
            $arr["ids4"] = $raz->id;
        }


        foreach ($option as $op) {

            if ($op->type_field == 'i11'){
                $image = $request->file($op->name_en);
                if(isset($image)) {
                    $img = time() .'_'.$count. '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/upload/images');
                    $image->move($destinationPath, $img);
                    $arr[$op->name_en] = $img;
                }
            }
            else {
                $arr[$op->name_en] = $request->get($op->name_en);
            }
            $count++;
        }
        $photo = '';

        if($catalog["photo"]!='') {
            $del = explode(';', $catalog["photo"]);
            $c = 0;
            foreach ($del as $d) {
                if ($d != '') {
                if($request->get('delete'.$c) && $request->get('delete'.$c)==1) {

                }else{
                    $photo.=$d.';';
                }
                }
                $c++;
            }
        }

        if(intval($request->get('phot_col'))>0) {
            for ($i = 1; $i <= intval($request->get('phot_col')); $i++) {
                $image = $request->file('ph_' . $i);
                if (isset($image)) {
                    $img = 'cat_'.time() . '_' . $i . '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/upload/images');
                    $image->move($destinationPath, $img);
                    $photo .= $img.';';
                }
            }
        }
        $arr["photo"] = $photo;

        $arr["del"] = 0;
        $arr["idc"] = $request->get("ids");
        $arr["idc1"] = $request->get("ids1");
        $arr["idcab"] = $request->get("ncab");

        Catalog::query()->find($id)->update($arr);

        return redirect()->route('admin.catalog.edit', $id);
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
