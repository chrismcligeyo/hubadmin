<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'user',
        'status',
        'client'
    ];

    protected $dates = ['deleted_at'];

    public function requisition()
    {
        return $this->hasMany('App\Requisition', 'activation');
    }

    public function route()
    {
        return $this->hasMany('App\Route', 'activation');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user');
    }

    public function client()
    {
        return $this->belongsTo('App\Client', 'client');
    }

    public static function getValidationRule () {
        return [
            'name' => 'required',
            'start_date' => 'required',
            'client' => 'required'
        ];
    }

    public static function getEditValidationRule () {
        return [
            'name' => 'required',
            'start_date' => 'required',
            'client' => 'required'
        ];
    }
}
