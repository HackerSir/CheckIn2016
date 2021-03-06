<?php

namespace App;

use App\Traits\ColorTagTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * 攤位類型
 *
 * @property-read int id
 * @property string name 類型名稱
 * @property int target 過關需求該類型攤位數量
 * @property string color 標籤顏色
 * @property bool counted 是否列入抽獎集點
 *
 * @property \Illuminate\Database\Eloquent\Collection|Booth[] booths
 *
 * @property \Carbon\Carbon|null created_at
 * @property \Carbon\Carbon|null updated_at
 * @mixin \Eloquent
 */
class Type extends Model
{
    use ColorTagTrait;

    /** @var array $fillable 可大量指派的屬性 */
    protected $fillable = [
        'name',
        'target',
        'color',
        'counted',
    ];

    /** @var int $perPage 分頁時的每頁數量 */
    protected $perPage = 20;

    protected $appends = [
        'tag',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function booths()
    {
        return $this->hasMany(Booth::class);
    }
}
