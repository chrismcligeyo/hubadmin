<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'itemname',
        'supplier',
        'unit_cost',
        'quantity'
        
    ];

    protected $dates = ['deleted_at'];

    // public function requisition()
    // {
    //     return $this->belongsTo('App\Requisition', 'requisition');
    // }


    public static function getValidationRule () {
        return [
            'itemname' => 'required',
            'supplier' => 'required',
            'unit_cost' => 'numeric',
            'quantity' => 'numeric'
        ];
    }

    public static function getEditValidationRule () {
        return [
            'itemname' => 'required',
            'supplier' => 'required',
            'unit_cost' => 'numeric',
            'quantity' => 'numeric'
        ];
    }
    public function requisition()
    {
        return $this->belongsTo(Requisition::class, 'requisition_id');
    }
}
