<?php

namespace App\Traits;

trait ColorTagTrait
{
    public static $validColors = [
        'grey',
        'red',
        'orange',
        'yellow',
        'olive',
        'green',
        'teal',
        'blue',
        'violet',
        'purple',
        'pink',
        'brown',
        'black',
    ];

    public function getColorAttribute()
    {
        $color = $this->getOriginal('color');
        $color = strtolower($color);
        if (!in_array($color, static::$validColors)) {
            $color = array_first(static::$validColors);
        }

        return $color;
    }

    public function getTagAttribute()
    {
        $tagTextField = $this->tagTextField ?: 'name';
        $tagText = $this->$tagTextField;
        return "<span class=\"ui tag label single line {$this->color}\">{$tagText}</span>";
    }
}
