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
        // $update = Telegram::getWebhookUpdate();
        $update = Telegram::bot()->getWebhookUpdate();

        Log::warning(json_encode($update['callback_query'] ?? null));

        $isCallBackQuery = '' !== trim($update->getCallbackQuery());
        $isCallBackQuery = (int)$isCallBackQuery;
        if ($isCallBackQuery) {
            Log::critical('Callback: ', (array)$update->getCallbackQuery());
        }
        $message = $update->getCallbackQuery() ? $update->getCallbackQuery()->getData() : $update->getMessage();

        $messageText = $update->getCallbackQuery() ? $update->getCallbackQuery()->getData() : $update->getMessage()->getText();
        $text = 'Is callback: ' . $isCallBackQuery . ', text: ' . $messageText;
        Log::info($text, (array)$message);



       Telegram::bot()->commandsHandler(true);



       if($callbackQuery = $update->getCallbackQuery()){
           $chat_id = $update->getChat()->getId();

           /**
            * @var string $data
            */
           $data = $callbackQuery->getData();
           $dataParsed = explode(' ',$data);
           $command = $dataParsed[0] ?? null;
           $action = $dataParsed[1] ?? null;

           switch ($command){
               case 'test':{
                   $cmd = new TestCommand;
                   $cmd->$action($update,$callbackQuery);
               }break;
           }
       }

    }
}
