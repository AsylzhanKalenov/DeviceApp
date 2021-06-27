<?php

namespace App\Http\Controllers\operator;

use App\Cabinet;
use App\Catalog;
use App\Center;
use App\Isp;
use App\Log;
use App\Operator;
use App\Options;
use App\Remont;
use App\Service;
use App\Type;
use App\User;
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
    public function index()
    {
        //
        $catalog = Catalog::query()->select('catalog.*', 'razdel.name as bname')->leftJoin('razdel', 'catalog.ids2', '=','razdel.id')->
        where('catalog.ser_nom', '!=', '')->skip(0)->take(50)->orderBy('bname', 'asc')->get();

        $cabinet1 = Cabinet::all();

        $center = Center::all();
        $option = Options::where('category', 'catalog')->where('category_id','=', 0)->where('select_fields','=', 1)->orderby('id_sort', 'asc')->get();
        return view('operator.remont.list', compact('catalog', 'option', 'center', 'cabinet1'));
    }

    public function print($id)
    {

        $remont = Remont::query()->where('id', '=', $id)->first();
        $catalog = Catalog::query()->find($remont->idcat);
        return view('operator.remont.print', [
            'remont' => $remont,
            'catalog' => $catalog
        ]);
    }

    public function show_added($id){

        $remont = Remont::query()->where('id', '=', $id)->first();

        $isp = Isp::query()->find($remont->idisp);

        $type = Type::query()->find($remont->idtype);

        return view('operator.remont.show_added',
            [
                'remont' => $remont,
                'isp' => $isp,
                'type' => $type
            ]);

    }

    public function add_archive($id){

        $remont1 = DB::table('remont')->where('id', $id)->update(['stat' => 'Архив', 'data3' => date('Y-m-d')]);

        $res = false;
        if($remont1)
        $res = true;

        $remont = Remont::query()->where('id', '=', $id)->first();

        $isp = Isp::query()->find($remont->idisp);

        $type = Type::query()->find($remont->idtype);

        return view('operator.remont.show_added',
            [
                'remont' => $remont,
                'isp' => $isp,
                'type' => $type,
                'res' => $res
            ]);

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

        return view('operator.remont.show_rem', compact('remont', 'type', 'isp', 'types', 'isps'));

    }

    public function added(Request $request){

        $arr = [
            ['r.stat', '!=', 'Архив'],
            ['r.stat', '!=', 'Дефектовано']
        ];
//        DB::enableQueryLog();

        if (isset($request) && $request->get('search') != '') {
            $sel_center = '';
            if ($request->get('center')) {
                $arr[] = ['c.idc', '=', $request->get('center')];
                $sel_center = $request->get('center');
            }
            $sel_cab = array();
            if ($request->get('ncab')) {
                $arr[] = ['c.idcab', '=', $request->get('ncab')];
                $sel_cab[] = $request->get('ncab');
            }
            $in_nom = '';
            if ($request->get('in_nomer')) {
                $arr[] = ['c.in_nom', '=', $request->get('in_nomer')];
                $in_nom = $request->get('in_nomer');
            }
        }

        $id_center = Operator::query()->where('id_user', '=', Auth::user()->id)->first();
        $id_arr = explode(',', $id_center["id_center"]);
        if($id_arr[sizeof($id_arr)-1]=='')
        unset($id_arr[sizeof($id_arr)-1]);

        $catalog = Remont::query()->leftJoin('catalog as c', 'c.id', '=', 'r.idcat')->select('r.id as rid', 'c.id as cid', 'c.ser_nom as cser_nom', 'c.in_nom as cin_nom', 'r.stat as rstat', 'r.data1 as rdata', 'c.idc', 'c.idcab', 'c.ids2', 'c.ids3')->where($arr)->where('r.id_user', Auth::user()->id)->skip(0)->take(50)->orderBy('r.id', 'desc')->get();
//        dd(DB::getQueryLog());

        $cabinet1 = DB::table('cabinet')->where('del', 0)->get();

        $center = DB::table('centers')->where('del', 0)->get();
        $option = Options::query()->where('category', 'catalog')->where('category_id', 0)->where('select_fields', 1)->orderby('id_sort', 'asc')->get();
        if (isset($request) && $request->get('search') != '')
        return view('operator.remont.added', compact('catalog', 'option', 'center', 'cabinet1', 'sel_center', 'sel_cab', 'in_nom'));
        else
        return view('operator.remont.added', compact('catalog', 'option', 'center', 'cabinet1'));

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
            $arr[] = ['r.id_user', '=', Auth::user()->id];

//        DB::enableQueryLog();

            $catalog = Remont::query()->join('catalog as c', 'c.id', '=', 'r.idcat')->select('r.id as idd', 'r.data3 as data3', 'c.*')->where('c.idc', $id_center != '' ? '=' : '!=', $id_center != '' ? $request->get('center') : 0)
                ->where($arr)->whereIn('r.stat', ['Архив'])->skip(0)->take(50)->orderBy('r.id', 'desc')->get();
//        dd(DB::getQueryLog());

            $cabinet1 = Cabinet::all();

            $center = Center::all();
            $option = Options::query()->where('category', 'catalog')->where('category_id', 0)->where('select_fields', 1)->orderby('id_sort', 'asc')->get();

            return view('operator.remont.list_archive', compact('id_center', 'catalog', 'option', 'center', 'cabinet1', 'isncab'));

        }

//        DB::enableQueryLog();
        $arr = [
            ['c.ser_nom', '!=', ''],
            ['r.id_user', '=', Auth::user()->id]
        ];
        $catalog = Remont::query()->join('catalog as c', 'c.id', '=', 'r.idcat')->select('r.id as idd', 'r.data3 as data3', 'c.*')->where($arr)->whereIn('r.stat', ['Архив'])->skip(0)->take(50)->orderBy('r.id', 'desc')->get();
//        dd(DB::getQueryLog());
        $cabinet1 = Cabinet::all();

        $center = Center::all();
        $option = Options::query()->where('category', 'catalog')->where('category_id', 0)->where('select_fields', 1)->orderby('id_sort', 'asc')->get();

        return view('operator.remont.list_archive', compact('catalog', 'option', 'center', 'cabinet1'));

    }

    public function new_order(){

        $service = Service::query()->orderBy('name','asc')->get();
        $user = User::get_user(Auth::user()->id);
        $centers = explode(',', $user["id_center"]);
        $center = Center::query()->whereIn('id', $centers)->get();

        return view('operator.remont.add_order',
            [
                'service' => $service,
                'center' => $center
            ]);
    }

    public function create()
    {
        //
        $service = Service::query()->orderBy('name','asc')->get();

        return view('operator.remont.add',compact('service'));
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
            'idcat' => 'required']);

//        $cat = Catalog::query()->where('id', '=', $request->get('cat_id'))->first();
//        $cen = Center::query()->where('id', '=', $cat->idc)->first();
//        $cab = Cabinet::query()->where('id','=', $cat->idcab)->first();
//        $raz = Razdel::query()->where('id','=',$cat->ids2)->first();

        $user = User::get_user(Auth::user()->id);
        $arr = array(
            'idcat' =>$request->get('idcat'),
            'idser' => $request->get('idser'),
            'data1' => date('Y-m-d'),
            'stat' => 'Поданная заявка',
            'comm' => $request->get('comm'),
            'user' => $user->fio,
            'id_user' => Auth::user()->id,
        );

//        DB::statement('insert into remont(idcat, idser, data1, stat, comm, ) '.$delete["name_en"].' ');

        $rem = DB::table('remont')->insert($arr);
//        $rem = Remont::query()->create($arr);

        Log::add_log($rem["id"], 'remont', 'Заявка на ремонт');

        $success = '';
        $error = '';
        if($rem)
        $success = 'Ваша заявка отпралена';
        else
        $error = 'Ошибка при отправки заяки';

        $service = Service::query()->orderBy('name','asc')->get();
        $user = User::get_user(Auth::user()->id);
        $centers = explode(',', $user["id_center"]);
        $center = Center::query()->whereIn('id', $centers)->get();
        return view('operator.remont.add_order',
            [
                'service' => $service,
                'center' => $center,
                'success' => $success,
                'error' => $error
            ]);
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

        $remont = Remont::query()->where('idcat', '=', $id)->get();
        $option = Options::query()->where('category', 'remont')->get();
        return view('operator.remont.show', compact('remont', 'id', 'option', 'service'));
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

        return view('operator.remont.add',compact('service', 'id'));
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

        $remont = Remont::query()->where('id', '=', $id)->first();

        $isp = Isp::query()->find($remont->idisp);

        $type = Type::query()->find($remont->idtype);

        return view('operator.remont.show_added',
            [
                'remont' => $remont,
                'isp' => $isp,
                'type' => $type
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
