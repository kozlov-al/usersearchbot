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
        $chatId = $update->getChat()->getId();
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
                'id' => $chatMember->getUser()->getId(),
                'is_bot' => $chatMember->getUser()->getIsBot(),
                'first_name' => $chatMember->getUser()->getFirstName(),
                'last_name' => $chatMember->getUser()->getLastName(),
                'username' => $chatMember->getUser()->getUsername(),
                'language_code' => $chatMember->getUser()['language_code']
            ]);
        }


        //  Log::warning(json_encode($update['callback_query'] ?? null));

        $isCallBackQuery = '' !== trim($update->getCallbackQuery());
        $isCallBackQuery = (int)$isCallBackQuery;
//        if ($isCallBackQuery) {
//            Log::critical('Callback: ', (array)$update->getCallbackQuery());
//        }
        $message = $update->getCallbackQuery() ? $update->getCallbackQuery()->getData() : $update->getMessage();

        $messageText = $update->getCallbackQuery() ? $update->getCallbackQuery()->getData() : $update->getMessage()->getText();
        $text = 'Is callback: ' . $isCallBackQuery . ', text: ' . $messageText;
        //  Log::info($text, (array)$message);


        Telegram::bot()->commandsHandler(true);


        if ($callbackQuery = $update->getCallbackQuery()) {
            $chat_id = $update->getChat()->getId();

            /**
             * @var string $data
             */
            $data = $callbackQuery->getData();
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
                case 'help':
                    {
                        $cmd = new HelpCommand();
                        $cmd->$action($update, $callbackQuery);
                    }
                    break;
            }
        }

    }
}
