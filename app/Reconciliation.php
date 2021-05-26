<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reconciliation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'amount',
        'cover_image',
        'date',
        'requisition'

        
    ];

    protected $dates = ['deleted_at'];

    public static function getValidationRule () {
        return [
            'amount' => 'required',
            'date' => 'required',
            'cover_image' => 'nullable|dimensions:min_width=100,min_height=100'
        ];
    }

    public static function getEditValidationRule () {
        return [
            'amount' => 'required',
            'date' => 'required',
            'cover_image' => 'nullable|dimensions:min_width=100,min_height=100'
        ];
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function requisition()
    {
        return $this->belongsTo('App\Requisition', 'requisition');
    }
}
