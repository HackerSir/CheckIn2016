<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 抽獎券
 *
 * @property-read int id
 * @property int user_id
 *
 * @property User user
 *
 * @property \Carbon\Carbon|null created_at
 * @property \Carbon\Carbon|null updated_at
 * @mixin \Eloquent
 */
class Ticket extends Model
{
    /** @var array $fillable 可大量指派的屬性 */
    protected $fillable = [
        'user_id',
    ];

    /** @var int $perPage 分頁時的每頁數量 */
    protected $perPage = 50;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
