<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cabinet extends Model
{
    //
    protected $table = 'cabinet';

    public $timestamps = false;

    protected $fillable = ['ids', 'info', 'bezo', 'textreb', 'del', 'nomer', 'deayt', 'photo' ,'photo_2', 'photo_3', 'med_sest', 'invent' ,'plan_kab'];
//    public function __construct(array $attributes = []) {
//        $option = Options::where('category', 'cabinet')->get();
//        $arr = array();
//        $arr1 = ['ids', 'info', 'bezo', 'textreb', 'del'];
//
//        foreach ($option as $op){
//            $arr[] = $op->name_en;
//        }
//        $arr = array_merge($arr, $arr1);
//
//        $this->fillable($arr);
//        parent::__construct($attributes);
//
//    }
}
