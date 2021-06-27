<?php

namespace App\Http\Controllers\module;

use App\Accountant;
use App\Admin;
use App\Center;
use App\Dispatcher;
use App\Groups;
use App\Isp;
use App\Operator;
use App\Options;
use App\Passarch;
use App\User;
use App\Viewer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index_personal($group){

        if(intval($group) == 8) {
            $option = [
                ['name_ru'=>'ФИО', 'name_en'=>'fio'],
                ['name_ru'=>'Модуль', 'name_en' =>'module']
            ];
            $operator = Dispatcher::query()->leftJoin('users', 'users.id','=','dispatcher.id_user')->leftJoin('pass_arch', 'pass_arch.id_user','=','dispatcher.id_user')->select(['dispatcher.*', 'users.name as login'])->get();
        }
        elseif (intval($group) == 7){
            $option = [
                ['name_ru'=>'ФИО', 'name_en'=>'fio'],
                ['name_ru'=>'Модуль', 'name_en' =>'module']
            ];
            $operator = Accountant::query()->leftJoin('users', 'users.id','=','accountant.id_user')->leftJoin('pass_arch', 'pass_arch.id_user','=','accountant.id_user')->select(['accountant.*', 'users.name as login'])->get();
        }
        $center = Center::all();

        return view('modules.user.list', compact('operator', 'option', 'center'));
    }

    public function index()
    {
        //
        $operator = Operator::query()->leftJoin('users', 'users.id','=','operator.id_user')->leftJoin('pass_arch', 'pass_arch.id_user','=','operator.id_user')->select(['operator.*', 'users.name as login', 'pass_arch.pass as password'])->get();
        $center = Center::all();
        $option = Options::where('category', 'operator')->orderby('id_sort', 'asc')->get();
        return view('modules.user.list', compact('operator', 'option', 'center'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $option = Options::where('category', 'operator')->orderby('id_sort', 'asc')->get();
        $center = Center::all();

        return view('modules.user.add', compact('option','center'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'fio' => 'required',
            'dolzh' => 'required',
            'login' => 'required',
            'password' => 'required',
        ]);

        $dolzh = Groups::query()->find($request->get('dolzh1'));

        if($dolzh["id"] == 2)
            $option = Options::where('category', 'operator')->get();
        elseif($dolzh["id"] == 5)
            $option = Options::where('category', 'viewer')->get();
        elseif($dolzh["id"] == 6)
            $option = Options::where('category', 'isp')->get();

        if(isset($option)) {
            $arr = array();
            $count = 0;
            foreach ($option as $op) {

                if ($op->type_field == 'i11') {
                    $image = $request->file($op->name_en);
                    if (isset($image)) {
                        $img = 'user_' . time() . '_' . $count . '.' . $image->getClientOriginalExtension();
                        $destinationPath = public_path('/upload/testimg');
                        $image->move($destinationPath, $img);
                        $arr[$op->name_en] = $img;
                        $arr["foto"] = $img;
                    }
                } elseif ($op->type_field == 'i2') {
                    $arr[$op->name_en] = date('Y-m-d', strtotime($request->get($op->name_en)));

                } else {
                    $arr[$op->name_en] = strval($request->get($op->name_en));
                }
                $count++;
            }
        }
        elseif($dolzh["id"] == 1){
            $arr = array(
                'fio' => $request->get('fio'),
                'module' => '1,2,3,4,5,6,7,8,9,10,11,12,13,14,21,22,28'
            );
        }

        $user = User::create([
            'id_group' => $request->get('dolzh1'),
            'name' => $request->get('login'),
            'password' => Hash::make($request->get('password')),
        ]);

        Passarch::query()->create([
            'id_user' => $user->id,
            'pass' => $request->get('password')
        ]);

        $arr["id_user"] = $user->id;
        $arr["date1"] = date('Y-m-d');
        $arr["date2"] = date('Y-m-d');
        $id_c ='';
        if($request->get('center'))
        foreach ($request->get('center') as $c){
            $id_c .=$c.',';
        }
        $arr["id_center"] = $id_c;

        if($dolzh["id"] == 2){
        $arr["module"] = '16,17,18,19,23,24,25,28';
        Operator::create($arr);}
        elseif($dolzh["id"] == 5){
        $arr["module"] = '1,5,6,7,8,11,13,14,21,10,28';
        Viewer::create($arr);}
        elseif($dolzh["id"] == 6){
        Isp::create($arr);}
        elseif($dolzh["id"] == 1)
        Admin::create($arr);
        elseif ($dolzh["id"] == 7){
        $arr = array(
            'id_user' => $user->id,
            'fio' => $request->get('fio'),
            'module' => '1,5,6,7,8,11,13,14,21,10,28'
        );
        Accountant::create($arr);}
        elseif ($dolzh["id"] == 8){
        $arr["fio"] = $request->get('fio');
        $arr["module"] = '1,5,6,7,8,11,13,14,21,10,28';
        Dispatcher::create($arr);
        }

        if ($request->input('login') == null)
            return redirect()->route('admin.user.create');
        else
            return redirect()->route('admin.user.index');
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
        $id = Operator::find($id);
        $option = Options::where('category', 'operator')->orderby('id_sort', 'asc')->get();
        return view('modules.user.edit', compact('option', 'id'));
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
        $option = Options::where('category', 'operator')->get();
        $operator = Operator::find($id);
        $arr = array();
        $count = 0;
        foreach ($option as $op){
            if($op->type_field != 'i11') {
                if($operator[$op->name_en]!=$request->get($op->name_en))
                    $arr[$op->name_en] = $request->get($op->name_en);
            }
            else{
                if ($request->hasFile($op->name_en)) {
                    $image = $request->file($op->name_en);
                    if(isset($image)) {
                        $usersImage = public_path("upload/testimg/{$operator[$op->name_en]}"); // get previous image from folder
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

        Operator::find($id)->update($arr);


        return redirect()->route('admin.user.edit', $id);
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
        $operator = Operator::find($id);
        $option = Options::where('category', 'operator')->get();
        foreach ($option as $op){
            if($op->type_field == 'i11'){
                if (File::exists('upload/testimg/' . $operator[$op->name_en])) {
                    File::delete('upload/testimg/' . $operator[$op->name_en]);
                }
            }
        }

        Operator::find($id)->delete();

        return redirect()->route('admin.user.index');
    }
}
