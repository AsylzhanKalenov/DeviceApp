<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $table = 'order';

    public $timestamps = false;
    public function __construct(array $attributes = []) {
        $option = Options::where('category', 'order')->get();
        $arr = array();
        $arr[] = 'id_center';

        foreach ($option as $op){
            $arr[] = $op->name_en;
        }
        $arr[] = 'fio';

        $this->fillable($arr);
        parent::__construct($attributes);

    }

}
