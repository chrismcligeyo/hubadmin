<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'date',
        'activation',
        
    ];

    protected $dates = ['deleted_at'];

    public function activation()
    {
        return $this->belongsTo('App\Activation', 'activation');
    }

    // public function requisition_category()
    // {
    //     return $this->belongsTo('App\RequisitionCategory', 'requisition_category');
    // }

    public static function getValidationRule () {
        return [
            'name' => 'required',
            'date' => 'required',
            'activation' => 'required',
        ];
    }

    public static function getEditValidationRule () {
        return [
           'name' => 'required',
            'date' => 'required',
            'activation' => 'required',
        ];
    }
}
