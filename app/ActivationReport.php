<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivationReport extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'cover_image',
        'cover_image_two',
        'cover_image_three',
        'what_worked',
        'what_failed',
        'feedback',
        'activation'
    ];

    protected $dates = ['deleted_at'];

    public static function getValidationRule () {
        return [
            'what_worked' => 'required',
            'what_failed' => 'required',
            'feedback' => 'required',
            'cover_image' => 'nullable|dimensions:min_width=100,min_height=100',
            'cover_image_two' => 'nullable|dimensions:min_width=100,min_height=100',
            'cover_image_three' => 'nullable|dimensions:min_width=100,min_height=100'
        ];
    }

    public static function getEditValidationRule () {
        return [
            'what_worked' => 'required',
            'what_failed' => 'required',
            'feedback' => 'required',
            'cover_image' => 'nullable|dimensions:min_width=100,min_height=100',
            'cover_image_two' => 'nullable|dimensions:min_width=100,min_height=100',
            'cover_image_three' => 'nullable|dimensions:min_width=100,min_height=100'
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

    public function activation()
    {
        return $this->belongsTo('App\Activation', 'activation');
    }
    public function activationReport()
    {
        return $this->hasOne('App\ActivationReport', 'requisition');
    }
}
