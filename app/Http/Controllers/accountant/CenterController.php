<?php

namespace App\Http\Controllers\accountant;

use App\Center;
use App\Options;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class CenterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $center = Center::all();
        $option = Options::where('category', 'center')->orderby('id_sort', 'asc')->get();
        return view('accountant.center.list1', compact('center', 'option'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $option = Options::where('category', 'center')->orderby('id_sort', 'asc')->get();
        return view('accountant.center.add', compact('option'));
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
        $this->validate($request, [
            'name' => 'required',
            'fact' => 'required',
            'uadr' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:300000',
        ]);

        $option = Options::where('category', 'center')->get();

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

        Center::create($arr);


        if ($request->input('name') == null)
            return redirect()->route('module.center.create');
        else
            return redirect()->route('module.center.index');
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
        $id = Center::find($id);
        $option = Options::where('category', 'center')->orderby('id_sort', 'asc')->get();
        return view('accountant.center.edit', compact('option', 'id'));
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

        $option = Options::where('category', 'center')->get();
        $center = Center::find($id);
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

        Center::find($id)->update($arr);


        return redirect()->route('module.center.edit', $id);
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
