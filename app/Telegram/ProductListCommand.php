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
use Telegram\Bot\Objects\Payments\LabeledPrice;
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
     * Полный список товаров
     * TODO категории
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

            if (!Basket::where('product_id', $product->getId())->exists()) {
                $keyboard = Keyboard::make()
                    ->inline()
                    ->setResizeKeyboard(true)
                    ->setOneTimeKeyboard(true)
                    ->row(
                        Keyboard::inlineButton(['text' => 'Добавить в корзину', 'callback_data' => 'list callbackAddToBasket', 'id' => $product->getId()]),
                        Keyboard::inlineButton(['text' => 'Посмотреть на сайте', 'url' => 'wwww.google.com'])
                    );
                $caption = $product->getName() . PHP_EOL . $product->getDescription() . PHP_EOL . 'Цена: ' . $product->getPrice();

                $this->telegram->sendPhoto([
                    'chat_id' => $chat_id,
                    'photo' => new InputFile($product->getPhotoUrl()),
                    'caption' => $caption,
                    'reply_markup' => $keyboard,
                ]);
            } else {
                $keyboard = Keyboard::make()
                    ->inline()
                    ->setResizeKeyboard(true)
                    ->setOneTimeKeyboard(true)
                    ->row(
                        Keyboard::inlineButton(['text' => 'Посмотреть на сайте', 'url' => 'wwww.google.com'])
                    );
                $caption = $product->getName() . PHP_EOL . $product->getDescription() . PHP_EOL . 'Цена: ' . $product->getPrice() . PHP_EOL . 'Добавлено в корзину.';

                $this->telegram->sendPhoto([
                    'chat_id' => $chat_id,
                    'photo' => new InputFile($product->getPhotoUrl()),
                    'caption' => $caption,
                    'reply_markup' => $keyboard,
                ]);
            }
        }

        $keyboard = Keyboard::make()
            ->inline()
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true)
            ->row(
                Keyboard::inlineButton(['text' => 'На главную', 'callback_data' => 'list callbackBack'])
            );
        return $this->replyWithMessage(['text' => 'На главную', 'reply_markup' => $keyboard]);
    }


    /**
     * Кнопка добавления товара в корзину
     * Инициализация товара происходит по его имени
     * @param Update $update
     * @param CallbackQuery $query
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function callbackAddToBasket(Update $update, CallbackQuery $query)
    {
        $this->update = $update;

        $caption = explode(PHP_EOL, $query->message->caption);

        $name = $caption[0];

        $product = Product::where('name', $name)->first();

        Basket::create([
            'user_id' => $update->getChat()->id,
            'product_id' => $product->getId()
        ]);


        $keyboard = Keyboard::make()
            ->inline()
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true)
            ->row(
                Keyboard::inlineButton(['text' => 'Посмотреть на сайте', 'url' => 'www.google.com'])
            );

        /**
         * когда добавляешь товар в корзину, меняется описание (добавляется строка *добавлено в корзину*), кнопка перестает быть доступной
         */
        $this->telegram->editMessageCaption([
            'chat_id' => $query->message->chat->id,
            'message_id' => $query->message->messageId,
            'caption' => $query->message->caption . PHP_EOL . 'Добавлено в корзину.',
            'reply_markup' => $keyboard
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

//        foreach ($messages as $message){
//            $this->telegram->deleteMessage([
//               'chat_id' => $update->message->chat->id,
//               'message_id' => $message->id
//            ]);
//        }

// TODO: Удаление предыдущих сообщений

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


    /**
     * Корзина пользователя
     * Здесь происходит оплата
     * @param Update $update
     * @param CallbackQuery $query
     * @return Message
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function callbackMyBasket(Update $update, CallbackQuery $query): Message
    {
        $this->update = $update;
        $basket = Basket::where('user_id', $update->getChat()->id)->get();
        $this->replyWithMessage(['text' => 'Ваши покупки: ']);

        if ($basket !== null && $basket->count() > 0) {

            foreach ($basket as $item) {
                $products = Product::where('id', $item->getProductId())->get();

                foreach ($products as $product) {

                    $keyboard = Keyboard::make()
                        ->inline()
                        ->setResizeKeyboard(true)
                        ->setOneTimeKeyboard(true)
                        ->row(
                            Keyboard::inlineButton(['text' => 'Оплатить', 'pay' => true]),
                            Keyboard::inlineButton(['text' => 'Убрать из корзины', 'callback_data' => 'list callbackDeleteProduct'])
                        );
                    $price = LabeledPrice::make([
                        'label' => $product->getName(),
                        'amount' => $product->getPrice()
                    ]);
// TODO: пробемы с id, с каким id, понятия не имею
                    $this->telegram->sendInvoice([
                        'title' => $product->getName(),
                        'photo_url' => $product->getPhotoUrl(),
                        'description' => $product->getDescription(),
                        'payload' => 'tranzzo',
                        'provider_token' => env('TELEGRAM_PROVIDER_TOKEN'),
                        'start_parameter' => 'pay',
                        'currency' => 'RUB',
                        'prices' => $price,
                        'chat_id' => $update->getChat()->id,
                        'reply_markup' => $keyboard
                    ]);
                }
            }
        } else {
            $this->replyWithMessage(['text' => 'Ваша корзина пуста.']);
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


    /**
     * Убрать продукт из корзины
     * @param Update $update
     * @param CallbackQuery $query
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function callbackDeleteProduct(Update $update, CallbackQuery $query)
    {
        $this->update = $update;

        $caption = explode(PHP_EOL, $query->message->caption);

        $name = $caption[0];
        $title = $query->message->invoice->title;
        $product = Product::where('name', $title)->first();
        Basket::where('product_id', $product->getId())->delete();

        $this->telegram->deleteMessage([
            'chat_id' => $query->message->chat->id,
            'message_id' => $query->message->messageId
        ]);

        if (Basket::where('user_id',$update->getChat()->id)){
            $this->telegram->sendMessage([
                'text' => 'Ваша корзина пуста.',
                'chat_id' => $update->getChat()->id
            ]);
        }

    }


}

