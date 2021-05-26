<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'id_number', 'phone_number', 'email','password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public static function getValidationRule () {
        return [
            'email' => 'required|email',
            'password' => 'required'
        ];
    }

    public static function getRegisterValidationRule () {
        return [ 
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required', 
            'email' => 'required|email',    
        ];
    }

    public function requisition()
    {
        return $this->hasMany('App\Requisition', 'user');
    }

    public function reconciliation()
    {
        return $this->hasMany('App\Reconciliation', 'user');
    }

    public function activation()
    {
        return $this->hasMany('App\Activation', 'user');
    }
}
