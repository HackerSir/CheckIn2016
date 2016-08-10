<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 設定
 *
 *
 * @property-read int id
 * @property string name
 * @property string type
 * @property string desc
 * @property string data
 *
 * @property \Carbon\Carbon|null created_at
 * @property \Carbon\Carbon|null updated_at
 * @mixin \Eloquent
 */
class Setting extends Model
{
    /** @var array $fillable 可大量指派的屬性 */
    protected $fillable = [
        'id',
        'name',
        'type',
        'desc',
        'data',
    ];

    /** @static array $types 有效型態與對應簡介 */
    public static $types = [
        'text'      => '單行文字',
        'multiline' => '多行文字',
        'boolean'   => '布林值',
        'int'       => '整數',
    ];

    /**
     * 取得設定值
     *
     * @param $name
     * @param $default
     * @return null
     */
    public static function get($name, $default = null)
    {
        /** @var Setting $setting */
        $setting = self::where('name', $name)->first();
        if (!$setting) {
            //找不到設定時，回傳預設值
            return $default;
        }

        return $setting->getData();
    }

    /**
     * 取得設定值（原始內容）
     *
     * @param $name
     * @return null
     */
    public static function getRaw($name)
    {
        /** @var Setting $setting */
        $setting = self::where('name', $name)->first();
        if (!$setting) {
            return;
        }

        return $setting->data;
    }

    /**
     * 更因設定
     *
     * @param $name
     * @param $data
     * @return null
     */
    public static function set($name, $data)
    {
        /** @var Setting $setting */
        $setting = self::where('name', $name)->first();
        if (!$setting) {
            return;
        }
        $setting->update([
            'data' => $data,
        ]);
    }

    /**
     * 取得型態
     *
     * @return string
     */
    public function getType()
    {
        //檢查是否為有效型態
        if (!in_array($this->type, array_keys(static::$types))) {
            //若不是，則自動選擇第一個型態
            return head(array_keys(static::$types));
        }

        return $this->type;
    }

    /**
     * 取得型態簡介文字
     *
     * @return string
     */
    public function getTypeDesc()
    {
        return static::$types[$this->getType()];
    }

    /**
     * 取得資料
     *
     * @return string
     */
    public function getData()
    {
        //依照型態進行不同處理
        if ($this->getType() == 'text') {
            return htmlspecialchars($this->data);
        }
        if ($this->getType() == 'multiline') {
            return nl2br(htmlspecialchars($this->data));
        }
        if ($this->getType() == 'boolean') {
            $bool = filter_var($this->data, FILTER_VALIDATE_BOOLEAN);

            return $bool;
        }
        if ($this->getType() == 'int') {
            return intval($this->data);
        }
        //無效型態
        return 'Invalid Setting Type';
    }

    /**
     * 取得欄位型態（讓X-editable識別）
     *
     * @return string
     */
    public function getHtmlFieldType()
    {
        //依照型態進行不同處理
        if ($this->getType() == 'text') {
            return 'text';
        }
        if ($this->getType() == 'multiline') {
            return 'textarea';
        }
        if ($this->getType() == 'boolean') {
            return 'select';
        }
        if ($this->getType() == 'text') {
            return 'text';
        }
        //無效型態
        return 'Invalid Setting Type';
    }
}
