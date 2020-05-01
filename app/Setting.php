<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Setting
 *
 * @property string $key
 * @property string $value
 * @property int $serialized
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setting whereSerialized($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Setting whereValue($value)
 * @mixin \Eloquent
 */
class Setting extends Model
{
    public $timestamps = false;

    public static function getSettings($key = null)
    {
        $settings = $key ? self::where('key', $key)->first() : self::get();
        $collect = collect();
        foreach ($settings as $setting) {
            $collect->put($setting->key, $setting->value);
        }
        return $collect;
    }
}
