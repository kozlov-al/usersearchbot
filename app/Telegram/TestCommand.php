<?php

namespace App\Telegram;

use App\User;
use Illuminate\Support\Facades\Request;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

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
        $text ='';
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        $update = $this->getUpdate();
        $name = $update->getMessage()->from->firstName;
        $f_name = $update->getMessage()->from->lastName;
        $user = User::find(1);
        $email = 'Почта пользователя в laravel: ' . $user->email;
        $text = $name . ' ' . $f_name. '  '.PHP_EOL . $email;
        $this->replyWithMessage(compact('text'));


//        $keyboard = [
//            ['7', '8', '9'],
//            ['4', '5', '6'],
//            ['1', '2', '3'],
//            ['0']
//        ];
//
//        $reply_markup = $this->telegram->replyKeyboardMarkup([
//            'keyboard' => $keyboard,
//            'resize_keyboard' => true,
//            'one_time_keyboard' => true
//        ]);
//
//        $this->telegram->sendMessage([
//            'chat_id' => $this->telegram->getMe()->id,
//            'text' => 'Hello World',
//            'reply_markup' => $reply_markup
//        ]);




    }
}
