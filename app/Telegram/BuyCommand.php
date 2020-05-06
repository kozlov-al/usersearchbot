<?php

namespace App\Telegram;

use App\TelegramUser;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use PHPUnit\ExampleExtension\Comparator;
use Telegram\Bot\Actions;
use Telegram\Bot\Api;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Objects\CallbackQuery;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Payments\LabeledPrice;
use Telegram\Bot\Methods\Payments;
use Telegram\Bot\Objects\Update;
use function GuzzleHttp\Promise\all;

/**
 * Class HelpCommand.
 */
class BuyCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'buy';


    /**
     * @var string Command Description
     */
    protected $description = 'But command';

    public function __construct()
    {
        if ($this->telegram === null) {
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
        Log::info($telegram->getMe()->id);
        /**
         * profile info
         */
        $text = 'hello';
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        $update = $this->getUpdate();


        $chatId = $update->getMessage()->chat->id;

        Log::info('info' . $update->getMessage()->chat->id);

        $price = LabeledPrice::make(['label' => 'Оплата', 'amount' => 7373]);

        $pay = $this->telegram->sendInvoice([
            'title' => 'Супер мега модные кроссы',
            'photo_url' => 'https://avatars.yandex.net/get-music-content/118603/6ea1ae39.a.5663315-2/m1000x1000',
            'description' => 'В названии все сказано',
            'payload' => 'tranzzo',
            'provider_token' => env('TELEGRAM_PROVIDER_TOKEN'),
            'start_parameter' => 'pay',
            'currency' => 'RUB',
            'prices' => $price,
            'chat_id' => $chatId,
        ]);
        Log::info('pay' . $pay);
    }

}

