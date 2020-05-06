<?php

namespace App\Telegram;

use App\Basket;
use App\TelegramUser;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use PHPUnit\ExampleExtension\Comparator;
use Telegram\Bot\Actions;
use Telegram\Bot\Api;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Objects\CallbackQuery;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;
use Telegram\Bot\Answers\Answerable;
use App\Product;
use function GuzzleHttp\Promise\all;

/**
 * Class HelpCommand.
 */
class ProductListCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'list';


    /**
     * @var string Command Description
     */
    protected $description = 'Список товаров';

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
        Log::info('Handle 1');


        $telegram = $this->telegram;

        $keyboard = Keyboard::make()
            ->inline()
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true)
            ->row(
                Keyboard::inlineButton(['text' => 'Список товаров', 'callback_data' => 'list callbackList']),
                Keyboard::inlineButton(['text' => 'Моя корзина', 'callback_data' => 'list callbackMyBasket'])

            );

        $this->replyWithMessage(['text' => 'Главная', 'reply_markup' => $keyboard]);

    }


    /**
     * @param Update $update
     * @param CallbackQuery $query
     * @return Message
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function callbackList(Update $update, CallbackQuery $query): Message
    {
        $this->update = $update;
        $products = Product::all();
        $chat_id = $update->getChat()->id;

        foreach ($products as $product) {
            $text = $product->getId() . ' ' . $product->getName() . PHP_EOL;
            $text .= $product->getDescription() . PHP_EOL;
            $text .= 'Цена: ' . $product->getPrice() . PHP_EOL;
            $keyboard = Keyboard::make()
                ->inline()
                ->setResizeKeyboard(true)
                ->setOneTimeKeyboard(true)
                ->row(
                    Keyboard::inlineButton(['text' => 'Добавить в корзину', 'callback_data' => 'list callbackAddToBasket'])
                );
            $reply_markup = $keyboard;

            $this->telegram->sendPhoto(['chat_id' => $chat_id, 'photo' => new InputFile($product->getPhotoUrl())]);

            $this->telegram->sendMessage(['text' => $text, 'chat_id' => $chat_id, 'reply_markup' => $reply_markup]);
        }

        Log::info('Клавиши: ', $keyboard->all());

        $keyboard = Keyboard::make()
            ->inline()
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true)
            ->row(
                Keyboard::inlineButton(['text' => 'На главную', 'callback_data' => 'list callbackBack'])
            );

        return $this->replyWithMessage(['text' => 'На главную', 'reply_markup' => $keyboard]);
    }


    public function callbackAddToBasket(Update $update, CallbackQuery $query)
    {
        $this->update = $update;
        $product = Product::all();

        Basket::create([
            'user_id' => $update->getChat()->id,
            'product_id' => $product->where('id', )
        ]);

    }


    /**
     * @param Update $update
     * @param CallbackQuery $query
     * @return Message
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function callbackBack(Update $update, CallbackQuery $query): Message
    {
        $this->update = $update;
        $keyboard = Keyboard::make()
            ->inline()
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true)
            ->row(
                Keyboard::inlineButton(['text' => 'Список товаров', 'callback_data' => 'list callbackList']),
                Keyboard::inlineButton(['text' => 'Моя корзина', 'callback_data' => 'list callbackMyBasket'])
            );
        return $this->telegram->sendMessage([
            'text' => 'Главная',
            'reply_markup' => $keyboard,
            'chat_id' => $update->getChat()->id,
        ]);

    }


    public function callbackMyBasket(Update $update, CallbackQuery $query): Message
    {
        $this->update = $update;
        $basket = Basket::where('user_id', $update->getChat()->id)->first();

        if ($basket !== null && $basket->count() > 0) {

            $products = Product::where('id', $basket->getProductId())->get();

            foreach ($products as $product) {
                $pay = $this->telegram->sendInvoice([
                    'title' => $product->getName(),
                    'photo_url' => $product->getPhotoUrl(),
                    'description' => $product->getDescription(),
                    'payload' => 'tranzzo',
                    'provider_token' => env('TELEGRAM_PROVIDER_TOKEN'),
                    'start_parameter' => 'pay',
                    'currency' => 'RUB',
                    'prices' => $product->getPrice(),
                    'chat_id' => $update->getChat()->id,
                ]);
            }
        } else {
            $this->replyWithMessage(['text' => 'Вы еще не добавляли товары в корзину.']);
        }

        $keyboard = Keyboard::make()
            ->inline()
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true)
            ->row(
                Keyboard::inlineButton(['text' => 'Вернуться', 'callback_data' => 'list callbackBack'])
            );
        return $this->replyWithMessage(['text' => 'Вернуться', 'reply_markup' => $keyboard]);
    }


}

