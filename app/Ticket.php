<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 抽獎券
 *
 * @property-read int id
 * @property int user_id
 *
 * @property Student student
 *
 * @property \Carbon\Carbon|null created_at
 * @property \Carbon\Carbon|null updated_at
 * @mixin \Eloquent
 */
class Ticket extends Model
{
    /** @var array $fillable 可大量指派的屬性 */
    protected $fillable = [
        'student_nid',
    ];

    /** @var int $perPage 分頁時的每頁數量 */
    protected $perPage = 50;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_nid');
    }
}
