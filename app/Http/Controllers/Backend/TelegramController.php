<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Telegram\TestCommand;
use App\TelegramUser;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Commands\CommandBus;
use Telegram\Bot\Commands\HelpCommand;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\EditedMessage;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;
use Faker\Provider\ru_RU\Payment;
class TelegramController extends Controller
{
    public function webhook()
    {
        /**
         * @var Update $update
         */
        $update = Telegram::bot()->getWebhookUpdate();
        $telegram = Telegram::bot();

       // $userId = $update->getMessage()->getFrom()->getId();
        $chatId = $update->getChat()->id;
$userId = $chatId;
       // Log::info('chat id: '. $chatId);
       // Log::info('user id: '. $userId);


        $chatMember = $telegram->getChatMember([
            'chat_id' => $chatId,
            'user_id' => $userId
        ]);

        //Log::info('member', (array)$chatMember);

        if (!TelegramUser::where('id', $userId)->exists()) {
            TelegramUser::create([
                'id' => $chatMember->user->id,
                'is_bot' => $chatMember->user->isBot,
                'first_name' => $chatMember->user->firstName,
                'last_name' => $chatMember->user->lastName,
                'username' => $chatMember->user->username,
                'language_code' => $chatMember->user->languageCode
            ]);
        }


        //  Log::warning(json_encode($update['callback_query'] ?? null));

        $isCallBackQuery = '' !== trim($update->callbackQuery);
        $isCallBackQuery = (int)$isCallBackQuery;
//        if ($isCallBackQuery) {
//            Log::critical('Callback: ', (array)$update->getCallbackQuery());
//        }
        $message = $update->callbackQuery ? $update->callbackQuery->data: $update->getMessage();

        $messageText = $update->callbackQuery ? $update->callbackQuery->data : $update->getMessage()->text;
        $text = 'Is callback: ' . $isCallBackQuery . ', text: ' . $messageText;
        //  Log::info($text, (array)$message);


        Telegram::bot()->commandsHandler(true);


        if ($callbackQuery = $update->callbackQuery) {
            $chat_id = $update->getChat()->id;

            /**
             * @var string $data
             */

            $data = $callbackQuery->data;
            $dataParsed = explode(' ', $data);
            $command = $dataParsed[0] ?? null;
            $action = $dataParsed[1] ?? null;

            switch ($command) {
                case 'test':
                    {
                        $cmd = new TestCommand;
                        $cmd->$action($update, $callbackQuery);
                    }
                    break;
            }
        }

    }
}
