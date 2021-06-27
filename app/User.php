<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_group', 'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function get_user($id){
        $user = User::query()->find($id);
        if($user->id_group == 1){
            $res = Admin::query()->where('id_user', '=', $user->id)->first();
        }elseif($user->id_group == 2){
            $res = Operator::query()->where('id_user', '=', $user->id)->first();
        }elseif($user->id_group == 3){
            $res = Service::query()->where('id_user', '=', $user->id)->first();
        }elseif($user->id_group == 5){
            $res = Viewer::query()->where('id_user', '=', $user->id)->first();
        }elseif($user->id_group == 4){
            $res = Isp::query()->where('id_user', '=', $user->id)->first();
        }elseif($user->id_group == 7){
            $res = Accountant::query()->where('id_user', '=', $user->id)->first();
        }elseif($user->id_group == 8){
            $res = Dispatcher::query()->where('id_user', '=', $user->id)->first();
        }
        if(isset($res))
        return $res;
        else
        return ['error' => 'error'];
    }
}
