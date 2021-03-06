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
 * @property \Illuminate\Database\Eloquent\Collection|Point[] points
 * @property Ticket ticket
 *
 * @property-read string displayName
 * @property-read bool isQualified 是否具備抽獎資格
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
    protected $appends = [
        'displayName',
    ];
    protected $perPage = 50;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function points()
    {
        return $this->hasMany(Point::class, 'student_nid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ticket()
    {
        return $this->hasOne(Ticket::class, 'student_nid');
    }

    public function getDisplayNameAttribute()
    {
        return $this->nid . ' ' . $this->name;
    }

    /**
     * 是否具備抽獎資格
     *
     * @return bool
     */
    public function getIsQualifiedAttribute()
    {
        //TODO: 改為可靈活設定
        //檢查入學年度
        if ($this->in_year == 105) {
            return true;
        }
        //檢查年級
        if (str_contains($this->class, '一年級')) {
            return true;
        }

        return false;
    }
}
