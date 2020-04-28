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
     * @var array Command Aliases
     */
    protected $aliases = ['listcommands'];

    /**
     * @var string Command Description
     */
    protected $description = 'Test command';


    /**
     * {@inheritdoc}
     */
    public function handle()
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        $user = User::find(1);
        $this->replyWithMessage(['text' => 'Почта пользователя в laravel: ' . $user->email]);

        $telegram_user = Telegram::getWebhookUpdates()['message'];
        $text = sprintf('$s: $s' . PHP_EOL, 'Ваш номер чата', $telegram_user['from']['id']);
        $text .= sprintf('$s: $s' . PHP_EOL, 'Ваше имя пользователя телеграм ', $telegram_user['from']['username']);

        $this->replyWithMessage(compact('text'));



    }
}
