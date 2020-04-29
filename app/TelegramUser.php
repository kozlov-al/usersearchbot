<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TelegramUser extends Model
{
    protected $guarded = [];
    protected $table = 'telegram_users';
    protected $primaryKey = 'id';
}
