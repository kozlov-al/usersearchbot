<?php

namespace App\Telegram;

use App\User;
use Illuminate\Support\Facades\Request;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Keyboard\Keyboard;

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
     */
    public function handle()
    {

        $telegram = $this->telegram;
        /**
         * profile info
         */
        $text = '';
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        $update = $this->getUpdate();
        $name = $update->getMessage()->from->firstName;
        $f_name = $update->getMessage()->from->lastName;
        $user = User::find(1);
        $email = 'Почта пользователя в laravel: ' . $user->email;
        $text = $name . ' ' . $f_name . '  ' . PHP_EOL . $email;
        /**
         * users
         */
        $users = User::all();
        $users_info = 'Список пользователей Laravel: ' . PHP_EOL;
        foreach ($users as $user) {
            $users_info .= $user->name . ' ' . $user->email . PHP_EOL;
        }
        /**
         * keyboard
         */
        $keyboard = Keyboard::make()
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true)
            ->row(
                Keyboard::inlineButton(['text' => 'Список пользователей на сайте', 'callback_data' => '1']),
                Keyboard::inlineButton(['text' => 'Ясно', 'callback_data' => '/butt2'])
            );
        $this->replyWithMessage(['text' => $text, 'reply_markup' => $keyboard]);


        $arUpdates = $telegram->getUpdates();


            foreach ($arUpdates['result'] as $arResult) {
                if (array_key_exists('callback_query',$arResult)) {

                    $userId = $arResult['callback_query']['from']['id'];

                    if ($arResult['callback_query']['data'] == 1) {
                        $telegram->sendMessage([$userId, 'Its ok!']);
                    } else {
                        $telegram->sendMessage([$userId, 'Its not ok!']);
                    }
                }
            }
        }


        //$this->telegram->sendMessage(['text' => $users_info])
        //$this->replyWithMessage(['text' => $users_info])


    }
}
