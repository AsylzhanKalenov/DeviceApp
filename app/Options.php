<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, string $string1)
 * @method static find($id)
 */
class Options extends Model
{
    //
    protected $table = 'options';

    public $timestamps = false;

}
