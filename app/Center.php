<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static find(int $id)
 */
class Center extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'centers';

    public $timestamps = false;

    protected $fillable = ['name', 'logo', 'fact', 'uadr', 'phones', 'req', 'fio', 'dolzh', 'tel', 'photo', 'map', 'photo1', 'del', 'glav', 'sestra', 'log_med_sentr', 'plan_1', 'plan_2', 'plan_3', 'plan_4', 'plan_5', 'f1'];
//    public function __construct(array $attributes = []) {
//        $option = Options::where('category', 'center')->get();
//        $arr = array();
//        foreach ($option as $op){
//            $arr[] = $op->name_en;
//        }
//        $this->fillable($arr);
//        parent::__construct($attributes);
//
//    }



}
