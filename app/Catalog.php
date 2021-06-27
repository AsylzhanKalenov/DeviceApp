<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    //
    protected $table = 'catalog';

    public $timestamps = false;

    protected $fillable = ['name', 'model', 'ids', 'ids1', 'ids2', 'ids3', 'ids4', 'idc', 'idc1', 'proizv', 'compl', 'rash', 'instr', 'metod', 'ptb', 'treb', 'trebpom', 'idcab', 'del', 'licens', 'licens1',
        'reguds', 'reguds1', 'metod1', 'photo2', 'dat', 'ser_nom', 'photo', 'in_nom', 'cena', 'datae', 'gar_per', 'datas', 'post', 'pasport', 'dog_nom', 'dog_data', 'proplat', 'ruk_pol', 'ruk_tech', 'gar_per1',
        'gar_list', 'gar_comp', 'gar_comp_data', 'inv_data', 'primech', 'rep_his', 'summ_of_cost'];

//    public function __construct(array $attributes = [])
//    {
//        $option = Options::query()->where('category', 'catalog')->get();
//        $arr = array();
//        $arr1 = ['name', 'model', 'ids', 'ids1', 'ids2', 'ids3', 'ids4', 'idc', 'idc1', 'proizv', 'compl', 'rash', 'instr', 'metod', 'ptb', 'treb', 'trebpom', 'idcab', 'del', 'licens', 'licens1',
//            'reguds', 'reguds1', 'metod1', 'photo2', 'dat'];
//
//        foreach ($option as $op) {
//            $arr[] = $op->name_en;
//        }
//        $arr = array_merge($arr, $arr1);
//
//        $this->fillable($arr);
//        parent::__construct($attributes);
//
//    }

}