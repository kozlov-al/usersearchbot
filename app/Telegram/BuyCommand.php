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
        if ($this->getTelegram() === null) {
            $this->telegram = new Api();
        }
    }

    /**
     * {@inheritdoc}
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function handle($arguments)
    {

        $telegram = $this->telegram;
        Log::info($telegram->getMe()->getId());
        /**
         * profile info
         */
        $text = 'hello';
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        $update = $this->getUpdate();




     //   $labeledPrice = LabeledPrice::make();

    //    Log::info('pay', $labeledPrice);

//        $pay = $this->telegram->sendInvoice([
//            'title' => 'Супер мега модные кроссы',
//            'description' => 'В названии все сказано',
//            'payload' => 'tranzzo',
//            'provider_token' => env('TELEGRAM_PROVIDER_TOKEN'),
//            'start_parameter' => 'pay',
//            'currency' => 'ru',
//            'prices' => [
//                ['label' => 'edkmfl', 'amount'=>1535235432],
//                ['label' => 'ed2kmfl', 'amount'=>1535235432],
//
//            ]
//        ]);
    }

}

