<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 學生
 *
 * @property-read int nid
 * @property string name
 * @property string class
 * @property string unit_name
 * @property string dept_name
 * @property int in_year
 * @property string sex
 *
 * @property User|null user
 *
 * @property \Carbon\Carbon|null created_at
 * @property \Carbon\Carbon|null updated_at
 * @mixin \Eloquent
 */
class Student extends Model
{
    public $incrementing = false;
    protected $primaryKey = 'nid';
    protected $fillable = [
        'nid',
        'name',
        'class',
        'unit_name',
        'dept_name',
        'in_year',
        'sex',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
