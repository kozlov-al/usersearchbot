<?php

namespace App\Telegram;

use App\TelegramUser;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Telegram\Bot\Actions;
use Telegram\Bot\Api;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Objects\CallbackQuery;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;
use function GuzzleHttp\Promise\all;

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

    public function __construct()
    {
        if ($this->getTelegram() === null) {
            $this->telegram = new Api();
        }
    }

    /**
     * {@inheritdoc}
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function handle()
    {

        $telegram = $this->telegram;

        /**
         * profile info
         */
        $text = 'hello';
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        $update = $this->getUpdate();


        $keyboard = Keyboard::make()
            ->inline()
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true)
            ->row(
                Keyboard::inlineButton(['text' => 'Мое имя', 'callback_data' => 'test callbackGetMyName']),
                Keyboard::inlineButton(['text' => 'google', 'callback_data' => 'test callbackGoogle'])
            )
            ->row(
                Keyboard::inlineButton(['text' => 'Список пользователей канала', 'callback_data' => 'test callbackUsers'])

            );

        Log::info('Keyboard: ' . $keyboard);
        $this->replyWithMessage(['text' => $text, 'reply_markup' => $keyboard, 'message_id' => $update->getMessage()->getMessageId()]);

    }


    /**
     * @param Update $update
     * @param CallbackQuery $query
     * @return Message
     */
    public function callbackGetMyName(Update $update, CallbackQuery $query): Message
    {
        $this->update = $update;
        $name = $query->getMessage()->getChat()->getLastName();
        $name .= ' ' . $query->getMessage()->getChat()->getFirstName();
        $name .= PHP_EOL . $query->getMessage()->getChat()->getUsername();
        $text = 'Ваше имя в телеграм:' . PHP_EOL . $name;

        /**
         * @var Message $message
         */
        return $this->replyWithMessage(compact('text'));
    }


    /**
     * @param Update $update
     * @param CallbackQuery $query
     * @return Message
     */
    public function callbackGoogle(Update $update, CallbackQuery $query): Message
    {
        $this->update = $update;
        $text = Carbon::now()->format('H:i:s ') . ' google';
        /**
         * @var Message $message
         */
        return $this->replyWithMessage(compact('text'));
    }


    /**
     * @param Update $update
     * @param CallbackQuery $query
     * @return Message
     */
    public function callbackUsers(Update $update, CallbackQuery $query): Message
    {
        $this->update = $update;

        $name = '';
        $tgUsers = TelegramUser::all();
        foreach ($tgUsers as $users)
$text = '';
        /**
         * @var Message $message
         */
        return $this->replyWithMessage(compact('text'));
    }
}

