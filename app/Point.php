<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 集點
 *
 * @property-read int id
 * @property int user_id
 * @property int booth_id
 * @property \Carbon\Carbon|null check_at
 * @property string ip
 *
 * @property Student student
 * @property Booth booth
 *
 * @property \Carbon\Carbon|null created_at
 * @property \Carbon\Carbon|null updated_at
 * @mixin \Eloquent
 */
class Point extends Model
{
    /** @var array $fillable 可大量指派的屬性 */
    protected $fillable = [
        'student_nid',
        'booth_id',
        'check_at',
        'ip',
    ];

    /** @var int $perPage 分頁時的每頁數量 */
    protected $perPage = 50;

    /** @var array $dates 自動轉換為Carbon的屬性 */
    protected $dates = ['check_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_nid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booth()
    {
        return $this->belongsTo(Booth::class);
    }
}
