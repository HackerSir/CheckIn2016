<?php

namespace App;

use App\Helpers\ImgurHelper;
use App\Services\ImageService;
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
 * @property-read string QR
 * @property-read bool counted 是否列入抽獎集點
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

    /** @var array 追加到toArray()的自訂屬性 */
    protected $appends = [
        'thumbnail',
    ];

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

    /**
     * 打卡QR碼
     *
     * @link https://developers.google.com/chart/infographics/docs/qr_codes
     *
     * @return string
     */
    public function getQRAttribute()
    {
        $checkUrl = route('check.booth', $this->code);
        $size = 450;
        $query = [
            'cht'  => 'qr',
            'chs'  => $size . 'x' . $size,
            'chl'  => $checkUrl,
            'chld' => 'M',
        ];

        $url = 'https://chart.googleapis.com/chart';
        $url .= '?' . http_build_query($query);

        return $url;
    }

    /**
     * 是否列入抽獎集點的「全部」進度當中
     *
     * @return bool
     */
    public function getCountedAttribute()
    {
        //若無類別，強制計算至「全部」
        if (!$this->type) {
            return true;
        }

        //根據類別決定
        return $this->type->counted;
    }

    public function getThumbnailAttribute()
    {
        //嘗試取得Imgur縮圖（若非Imgur，則直接回傳原網址）
        $imageUrl = ImgurHelper::thumbnail($this->image, 't');

        return $imageUrl;
    }
}
