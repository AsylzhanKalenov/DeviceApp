<?php

namespace App\Http\Controllers\module;

use App\Cabinet;
use App\Catalog;
use App\Center;
use App\Isp;
use App\Log;
use App\Operator;
use App\Options;
use App\Razdel;
use App\Remont;
use App\Service;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Type;

class RemontController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $catalog = Catalog::query()->select('catalog.*', 'razdel.name as bname')->leftJoin('razdel', 'catalog.ids2', '=','razdel.id')->
        where('catalog.ser_nom', '!=', '')->skip(0)->take(50)->orderBy('bname', 'asc')->get();

        $cabinet1 = Cabinet::all();

        $center = Center::all();
        $option = Options::where('category', 'catalog')->where('category_id','=', 0)->where('select_fields','=', 1)->orderby('id_sort', 'asc')->get();
        return view('modules.remont.list', compact('catalog', 'option', 'center', 'cabinet1'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function show_rem($id)
    {

        $remont = Remont::query()->where('id', '=',$id)->first();

        $type = \App\Type::query()->where('id','=',$remont["idtype"])->first();

        $isp = Isp::query()->find($remont["idisp"]);

        $types = \App\Type::all();

        $isps = Isp::all();

        return view('modules.remont.show_rem', compact('remont', 'type', 'isp', 'types', 'isps'));

    }

    public function added(){

        $arr = [
            ['r.stat', '!=', 'Архив'],
            ['r.stat', '!=', 'Дефектовано']
        ];
        $id_center = Operator::query()->where('id_user', '=', 56)->first();
        $id_arr = explode(',', $id_center["id_center"]);
        $catalog = Remont::query()->select('r.id as rid', 'c.id as cid', 'c.ser_nom as cser_nom', 'c.in_nom as cin_nom', 'r.stat as rstat', 'r.data1 as rdata', 'c.idc', 'c.idcab', 'c.ids2', 'c.ids3')->leftJoin('catalog as c', 'c.id', '=', 'r.idcat')->where($arr)->whereIn('c.idc', $id_arr)->skip(0)->take(50)->orderBy('r.id', 'desc')->get();
        $cabinet1 = Cabinet::all();

        $center = Center::all();
        $option = Options::query()->where('category', 'catalog')->where('category_id', 0)->where('select_fields', 1)->orderby('id_sort', 'asc')->get();
        return view('modules.remont.added', compact('catalog', 'option', 'center', 'cabinet1'));

    }


    public function list_archive(Request $request){

        if (isset($request) && $request->get('search') != '') {
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

            $cabinet1 = Cabinet::all();

            $center = Center::all();
            $option = Options::query()->where('category', 'catalog')->where('category_id', 0)->where('select_fields', 1)->orderby('id_sort', 'asc')->get();

            return view('modules.remont.list_archive', compact('id_center', 'catalog', 'option', 'center', 'cabinet1', 'isncab'));

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

        return view('modules.remont.list_archive', compact('catalog', 'option', 'center', 'cabinet1'));

    }

    public function new_order(){

        $service = Service::query()->orderBy('name','asc')->get();
        $user = User::get_user(Auth::user()->id);
        $centers = explode(',', $user["id_center"]);
        $center = Center::query()->whereIn('id', $centers)->get();

        return view('modules.remont.add_order',
            [
                'service' => $service,
                'center' => $center
            ]);
    }

    public function create()
    {
        //
        $service = Service::query()->orderBy('name','asc')->get();

        return view('modules.remont.add',compact('service'));
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
        $option = Options::where('category', 'remont')->get();

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
        $this->validate($request, ['idser' => 'required',
                                    'cat_id' => 'required']);

//        $cat = Catalog::query()->where('id', '=', $request->get('cat_id'))->first();
//        $cen = Center::query()->where('id', '=', $cat->idc)->first();
//        $cab = Cabinet::query()->where('id','=', $cat->idcab)->first();
//        $raz = Razdel::query()->where('id','=',$cat->ids2)->first();

        $arr = array(
            'idcat' =>$request->get('cat_id'),
            'idser' => $request->get('idser'),
            'data1' => date('Y-m-d'),
            'stat' => 'Поданная заявка',
            'comm' => $request->get('comm'),
            'user' => Auth::user()->name,
            'id_user' => Auth::user()->id,
        );

//        DB::statement('insert into remont(idcat, idser, data1, stat, comm, ) '.$delete["name_en"].' ');

        $rem = DB::table('remont')->insert($arr);
//        $rem = Remont::query()->create($arr);

        Log::add_log($rem["id"], 'remont', 'Заявка на ремонт');

        $id=$request->get('cat_id');

        $success = isset($rem["id"])?'success':'';

        $service = Service::query()->orderBy('name','asc')->get();

        return view('modules.remont.add',compact('service', 'id', 'success'));

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

        $remont =Remont::query()->where('idcat', '=', $id)->get();
        $option = Options::query()->where('category', 'remont')->get();
        return view('modules.remont.show', compact('remont', 'id', 'option', 'service'));
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
        $service = Service::query()->orderBy('name','asc')->get();

        return view('modules.remont.add',compact('service', 'id'));
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
