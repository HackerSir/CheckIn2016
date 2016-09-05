<?php

namespace App\Helpers;

class ImgurHelper
{
    /**
     * @var array 縮圖後綴
     *
     * Thumbnail Suffix | Thumbnail Name   | Thumbnail Size | Keeps Image Proportions
     * s                | Small Square     | 90x90          | No
     * b                | Big Square       | 160x160        | No
     * t                | Small Thumbnail  | 160x160        | Yes
     * m                | Medium Thumbnail | 320x320        | Yes
     * l                | Large Thumbnail  | 640x640        | Yes
     * h                | Huge Thumbnail   | 1024x1024      | Yes
     */
    protected static $validSuffix = ['s', 'b', 't', 'm', 'l', 'h'];

    public static function getImgurID($url)
    {
        $pattern = '/^(?:(?:https?:)?\/\/)?[iw\.]*imgur\.[^\/]*\/(?:gallery\/)?([^\?\s\.]*).*$/im';
        preg_match($pattern, $url, $matches);
        if (empty($matches) || count($matches) < 2) {
            return null;
        }

        return $matches[1];
    }

    public static function thumbnail($url, $suffix = null)
    {
        if (empty(self::getImgurID($url))) {
            return $url;
        }
        if (!empty($suffix) && !in_array($suffix, static::$validSuffix)) {
            return null;
        }
        //取得副檔名
        $extensionPattern = '/[^\\\\]*\.(\w+)$/';
        preg_match($extensionPattern, $url, $matches);
        $extension = isset($matches[1]) ? $matches[1] : 'jpg';
        $thumbnail = '//i.imgur.com/' . self::getImgurID($url) . $suffix . '.' . $extension;

        return $thumbnail;
    }
}
