<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requisition extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'date',
        'activation_id',
        'user_id',
        'status',
        'requisition_code'
    ];

    protected $dates = ['deleted_at'];


    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function activation()
    {
        return $this->belongsTo('App\Activation', 'activation_id');
    }

    public function requisitionitem()
    {
        return $this->hasMany('App\RequisitionItem', 'requisition');
    }

    public function reconciliation()
    {
        return $this->hasOne('App\Reconciliation', 'requisition');
    }

    public static function getValidationRule () {
        return [
            'name' => 'required',
            'description' => 'required',
            'date' => 'required',
            
        ];
    }

      public static function getEditValidationRule () {
        return [
            'name' => 'required',
            'description' => 'required',
            'date' => 'required',
            
        ];
    }

   
}
