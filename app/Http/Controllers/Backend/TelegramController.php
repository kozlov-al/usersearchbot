<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\TelegramUser;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Commands\CommandBus;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\EditedMessage;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;

class TelegramController extends Controller
{
    public function webhook()
    {
        /**
         * @var Update $update
         */
        $update = Telegram::getWebhookUpdates();

        Log::warning(json_encode($update['callback_query'] ?? null));

        $isCallBackQuery = '' !== trim($update->getCallbackQuery());
        $isCallBackQuery = (int)$isCallBackQuery;
        if ($isCallBackQuery) {
            Log::critical('Callback: ', (array)$update->getCallbackQuery());
        }

        $message = $update->getCallbackQuery() ? $update->getCallbackQuery()->getMessage() : $update->getMessage();
        $messageText = $update->getCallbackQuery() ? $update->getCallbackQuery()->getMessage()->getText() : $update->getMessage()->getText();
        $text = 'Is callback: ' . $isCallBackQuery . ', text:' . $messageText;
        Log::info($text, (array)$message);


        Telegram::commandsHandler(true);


        $tgbot = Telegram::bot();
        if (isset($message)) {
            if (isset($message['text']) === 'OK')
                $tgbot->sendMessage([
                    'chat_id' => $tgbot->getChat()->getId(),
                    'message' => 'Privet'
                ]);
            Log::info('kek', (array)$message['text']);
        }


    }
}
