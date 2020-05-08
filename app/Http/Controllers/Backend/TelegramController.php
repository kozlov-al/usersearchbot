<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Telegram\ProductListCommand;
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
use Telegram\Bot\Methods\Payments;

class TelegramController extends Controller
{
    public function webhook()
    {
        /**
         * @var Update $update
         */
        $update = Telegram::bot()->getWebhookUpdate();
        $telegram = Telegram::bot();


        if ($update->preCheckoutQuery) {
            Log::info('query', (array)$update->preCheckoutQuery);
            $telegram->answerPreCheckoutQuery([
                'pre_checkout_query_id' => $update->preCheckoutQuery->id,
                'ok' => true,
                'error_message' => 'Oops:( something was wrong!'
            ]);
        }

//        if (!TelegramUser::where('id', $update->getChat()->id)->exists()) {
//            $chatId = $update->getChat()->id;
//            $userId = $chatId;
//
//            $chatMember = $telegram->getChatMember([
//                'chat_id' => $chatId,
//                'user_id' => $userId
//            ]);
//
//            TelegramUser::create([
//                'id' => $chatMember->user->id,
//                'is_bot' => $chatMember->user->isBot,
//                'first_name' => $chatMember->user->firstName,
//                'last_name' => $chatMember->user->lastName,
//                'username' => $chatMember->user->username,
//                'language_code' => $chatMember->user->languageCode
//            ]);
//        }


        $isCallBackQuery = '' !== trim($update->callbackQuery);
        $isCallBackQuery = (int)$isCallBackQuery;
//        if ($isCallBackQuery) {
//            Log::critical('Callback: ', (array)$update->getCallbackQuery());
//        }
        $message = $update->callbackQuery ? $update->callbackQuery->data : $update->getMessage();

        $messageText = $update->callbackQuery ? $update->callbackQuery->data : $update->getMessage()->text;
        $text = 'Is callback: ' . $isCallBackQuery . ', text: ' . $messageText;
//        Log::info($text, (array)$message);


        Telegram::bot()->commandsHandler(true);


        if ($callbackQuery = $update->callbackQuery) {
            // $chat_id = $update->getChat()->id;

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
                case 'list':
                    {
                        $cmd = new ProductListCommand;
                        $cmd->$action($update, $callbackQuery);
                    }
                    break;
            }
        }

    }
}
