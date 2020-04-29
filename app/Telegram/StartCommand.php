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
class StartCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'start';


    /**
     * @var string Command Description
     */
    protected $description = 'Start command.';


    /**
     * {@inheritdoc}
     */
    public function handle()
    {
        $update = $this->getUpdate();
        $name = $update->getMessage()->from->firstName;
        $text = "Hello, $name! Welcome to our bot!\nType /help to get a list of available commands.";
        $this->replyWithMessage(compact('text'));
    }
}
