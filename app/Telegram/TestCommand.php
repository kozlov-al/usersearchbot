<?php

namespace App\Telegram;

use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Objects\CallbackQuery;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\MessageEntity;
use Telegram\Bot\Objects\Update;

/**
 * Class HelpCommand.
 */
class TestCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'test';


    /**
     * @var string Command Description
     */
    protected $description = 'Test command';


    /**
     * {@inheritdoc}
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function handle($arguments)
    {

        /**
         * @var Update $update
         */
        $update = $this->getUpdate();

        /**
         * @var Message $message
         */


        $callbackQuery = $update->getCallbackQuery();

        Log::info('$arguments: ' . json_encode($arguments) . ', callback:' . $callbackQuery);
        $message = isset($callbackQuery) ? $callbackQuery->getMessage() : $update->getMessage();

        /**
         * profile info
         */

        if (null === $callbackQuery) {
            /**
             * @var Keyboard $keyboard
             */
            /**
             * @var string $params ['text']
             * @var string $params ['url']
             * @var string $params ['callback_data']
             * @var string $params ['switch_inline_query']
             */
            $params = [
                'text' => 'OK',
                'callback_data' => 'callback callback_data',
                'switch_inline_query' => 'switch_inline_query',
                'url' => "url",
            ];


            $keyboard = Keyboard::make()
                ->setResizeKeyboard(true)
                ->setOneTimeKeyboard(true)
                ->row(
                    Keyboard::inlineButton($params)
                );


            $this->replyWithMessage(['text' => 'Нажмите одну из кнопок', 'reply_markup' => $keyboard]);




        } else {
//            $text = $message->getText();
            $text = '2';
            $this->replyWithMessage(['text' => $text]);
        }


    }
}
