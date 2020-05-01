<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TelegramUser
 *
 * @property int $id
 * @property int|null $is_bot
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $username
 * @property string $language_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramUser whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramUser whereIsBot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramUser whereLanguageCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramUser whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramUser whereUsername($value)
 * @mixin \Eloquent
 */
class TelegramUser extends Model
{
    protected $guarded = [];
    protected $table = 'telegram_users';
    protected $primaryKey = 'id';
}
