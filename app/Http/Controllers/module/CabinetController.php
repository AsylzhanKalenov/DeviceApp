<?php

namespace App\Http\Controllers\module;

use App\Cabinet;
use App\Center;
use App\Log;
use App\Operator;
use App\Options;
use App\Razdel;
use App\Remont;
use App\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CabinetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cabinet = Cabinet::query()->leftJoin('center','center.id','=','cabinet.ids')->select('cabinet.*', 'center.name as cename')->get();
        $cabinet1 = Cabinet::all();

        $center = Center::all();
        $option = Options::where('category', 'cabinet')->orderby('id_sort', 'asc')->get();
        return view('modules.cabinet.list', compact('cabinet', 'option', 'center', 'cabinet1'));
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
            return view('modules.cabinet.list', compact('cabinet', 'option', 'center', 'cabinet1', 'isncab', 'isdeayt', 'id_center'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $option = Options::where('category', 'cabinet')->orderby('id_sort', 'asc')->get();
        $center = Center::all();

        return view('modules.cabinet.add', compact('option','center'));
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
        $option = Options::where('category', 'cabinet')->get();

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
            elseif ($op->type_field == 'i12'){
                $image = $request->file($op->name_en);
                if(isset($image)) {
                    $img = time() .'_'.$count. '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/upload/files');
                    $image->move($destinationPath, $img);
                    $arr[$op->name_en] = $img;
                }
            }
            else {
                $arr[$op->name_en] = $request->get($op->name_en);
            }
            $count++;
        }

        $arr["ids"] = $request->get("ids");
        $arr["del"] = 0;

        Cabinet::query()->create($arr);


        if ($request->input('ids') == null)
            return redirect()->route('admin.cabinet.create');
        else
            return redirect()->route('admin.cabinet.index');
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
        $cabinet = Cabinet::query()->find($id);
        $center = Center::query()->find($cabinet->ids);
        $option = Options::query()->where('category', 'cabinet')->get();
        return view('operator.cabinet.show', compact('cabinet', 'id', 'option', 'center'));
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
        $id = Cabinet::query()->find($id);
        $center = Center::all();
        $option = Options::query()->where('category', 'cabinet')->orderby('id_sort', 'asc')->get();
        return view('modules.cabinet.edit', compact('option', 'id', 'center'));
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
        $option = Options::where('category', 'cabinet')->get();
        $center = Cabinet::find($id);
        $arr = array();
        $count = 0;
        foreach ($option as $op){
            if($op->type_field != 'i11') {
                if($center[$op->name_en]!=$request->get($op->name_en))
                    $arr[$op->name_en] = $request->get($op->name_en);
            }
            else{
                if ($request->hasFile($op->name_en)) {
                    $image = $request->file($op->name_en);
                    if(isset($image)) {
                        $usersImage = public_path("uploads/images/{$center[$op->name_en]}"); // get previous image from folder
                        if (File::exists($usersImage)) { // unlink or remove previous image from folder
                            unlink($usersImage);
                        }

                        $img = time() .'_'.$count. '.' . $image->getClientOriginalExtension();
                        $destinationPath = public_path('/upload/images');
                        $image->move($destinationPath, $img);
                        $arr[$op->name_en] = $img;
                    }
                }
            }
            $count++;
        }
        $arr["ids"] = $request->get("ids");

        Cabinet::query()->find($id)->update($arr);

        return redirect()->route('admin.cabinet.edit', $id);
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
        $center = Cabinet::find($id);
        $option = Options::where('category', 'cabinet')->get();
        foreach ($option as $op){
            if($op->type_field == 'i11'){
                if (File::exists('upload/images/' . $center[$op->name_en])) {
                    File::delete('upload/images/' . $center[$op->name_en]);
                }
            }
        }

        Log::add_log($id, 'cabinet', 'Удаление');
        Cabinet::find($id)->delete();

        return redirect()->route('admin.cabinet.index');
    }
}
