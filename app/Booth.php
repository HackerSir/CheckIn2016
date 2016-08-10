<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 攤位
 *
 * @property-read int id
 * @property int type_id 類型
 * @property string name 攤位名稱
 * @property string description 攤位簡介
 * @property string url 攤位（社團或學會）的網址
 * @property string image 攤位圖片網址
 * @property string code 打卡代碼（隨機字串，生成網址和QR碼使用）
 *
 * @property Type type
 * @property \Illuminate\Database\Eloquent\Collection|Point[] points
 *
 * @property-read string displayDescription
 *
 * @property \Carbon\Carbon|null created_at
 * @property \Carbon\Carbon|null updated_at
 * @mixin \Eloquent
 */
class Booth extends Model
{
    /** @var array $fillable 可大量指派的屬性 */
    protected $fillable = [
        'type_id',
        'name',
        'description',
        'url',
        'image',
        'code',
    ];

    /** @var int $perPage 分頁時的每頁數量 */
    protected $perPage = 20;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function points()
    {
        return $this->hasMany(Point::class);
    }

    public function getDisplayDescriptionAttribute()
    {
        if ($this->description) {
            $html = nl2br(htmlspecialchars($this->description));
        } else {
            $html = "<sapn style='color: gray'>（未提供簡介）</sapn>";
        }
        return $html;
    }
}
