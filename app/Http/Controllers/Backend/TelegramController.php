<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\TelegramUser;
use App\User;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function webhook()
    {
        $update = Telegram::getWebhookUpdates()['message'];
        if(!TelegramUser::find($update['from']['id']))
        {
            TelegramUser::create($update['from'], true);
        }
        Telegram::commandsHandler(true);
    }
}
