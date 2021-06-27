<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Log extends Model
{
    //
    protected $table = 'log';

    public $timestamps = false;

    protected $fillable = ['user', 'center', 'action', 'vid', 'date'];


    public static function add_log($id, $table, $vid){

        $id_cur = DB::table($table)->find($id);

        $cen = '';

        switch ($table)

        {

            case 'cabinet': $id_cab = Cabinet::query()->find($id);
                $center = Center::query()->find($id_cab->ids);
                $info = 'Кабинет - '.$center["name"].' '.$id_cab["nomer"];
                $cen = $center["name"];
                break;

            case 'center': $id_cen = Center::query()->find($id);
                $info = 'Центр - '.$id_cen["name"];
                $cen = $id_cen["name"];
                break;

            case 'catalog': $r = Catalog::query()->find($id);
                $rr = Cabinet::query()->find($r["idcab"]);
                $rrr =Center::query()->find($r["idc"]);
                $rrrr = Razdel::query()->find($r["ids2"]);
                $info = 'Оборудование - '.$rrrr["name"].' '.$rrr["name"].' '.$rr["nomer"];
                $cen = $rrr["name"];
                break;
            case 'order': $order = Order::query()->find($id);
                $info = 'Заявка - '.$order["f4"];
                $cen = $order["f1"];
                break;

            case 'remont': $rem = DB::table('remont')->find($id);
                $cat = Catalog::find($rem["idcat"]);
                $raz = Razdel::find($cat["ids2"]);
                $info = 'Оборудование - '.$raz["name"];

        }


        Log::query()->create([
            'user' => Auth::user()->name,
            'center' => $cen,
            'action' => $info,
            'vid' => $vid,
            'date' => date('Y-m-d H:i:s')
        ]);
    }

}
