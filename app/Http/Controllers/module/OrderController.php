<?php

namespace App\Http\Controllers\module;

use App\Cabinet;
use App\Center;
use App\Log;
use App\Options;
use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function index($num=0)
    {
        //
        $option = Options::query()->where('category', '=', 'order')->get();
        $order = Order::query()->where('id','!=', 0)->skip($num)->take(50)->orderBy('id', 'desc')->get();

        return view('modules.order.list', compact('order', 'option'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $center = Center::all();
        $option = Options::where('category', 'order')->orderby('id_sort', 'asc')->get();
        return view('modules.order.add', compact('option', 'center'));
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

        $option = Options::where('category', 'order')->get();

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
                    $destinationPath = public_path('/upload/testimg');
                    $image->move($destinationPath, $img);
                    $arr[$op->name_en] = $img;
                }
            }
            else {
                $arr[$op->name_en] = $request->get($op->name_en);
            }
            $count++;
        }
        $arr["fio"] = Auth::user()->name;
        $arr["id_center"] = $request->get('center');
        $center = Center::query()->find($arr["id_center"]);
        $arr["f1"] = $center["name"];

        $log = Order::create($arr);

        Log::add_log($log->id, 'order', 'Добавление');

        if ($request->input('name') == null)
            return redirect()->route('module.order.create');
        else
            return redirect()->route('module.order.index');
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
        $option = Options::query()->where('category', '=','order')->get();
        $order = Order::query()->find($id);
        return view('modules.order.show', compact('option', 'order'));
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
        $id = Order::query()->find($id);
        $option = Options::query()->where('category', 'order')->orderby('id_sort', 'asc')->get();
        return view('modules.order.edit', compact('option', 'id'));
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
        $option = Options::where('category', 'order')->get();
        $center = Order::find($id);
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
                        $usersImage = public_path("uploads/testimg/{$center[$op->name_en]}"); // get previous image from folder
                        if (File::exists($usersImage)) { // unlink or remove previous image from folder
                            unlink($usersImage);
                        }

                        $img = time() .'_'.$count. '.' . $image->getClientOriginalExtension();
                        $destinationPath = public_path('/upload/testimg');
                        $image->move($destinationPath, $img);
                        $arr[$op->name_en] = $img;
                    }
                }
            }
            $count++;
        }
        $arr["ids"] = $request->get("ids");
        Log::add_log($id, 'order', 'Редактирование');


        Order::query()->find($id)->update($arr);


        return redirect()->route('module.order.edit', $id);
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
        $center = Order::find($id);
        $option = Options::where('category', 'order')->get();
        foreach ($option as $op){
            if($op->type_field == 'i11'){
                if (File::exists('upload/testimg/' . $center[$op->name_en])) {
                    File::delete('upload/testimg/' . $center[$op->name_en]);
                }
            }
        }

        Log::add_log($id, 'order', 'Удаление');
        Order::find($id)->delete();

        return redirect()->route('module.order.index');
    }
}
