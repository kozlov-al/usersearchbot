<?php

namespace App\Http\Controllers\Backend;

use App\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Telegram\Bot\Traits\Telegram;

class SettingController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('backend.setting', Setting::getSettings());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        Setting::where('key', '!=', null)->delete();
        foreach ($request->except('_token') as $key => $value) {
            $setting = new Setting;
            $setting->key = $key;
            $setting->value = $request->$key;
            $setting->save();
        }
        return redirect()->route('admin.setting.index');
    }

    public function setwebhook(Request $request)
    {
        $result = $this->sendTelegramData('setwebhook', [
            'query' => ['url' => $request->url() . '/' . \Telegram\Bot\Laravel\Facades\Telegram::getAccessToken()]
        ]);
        return redirect()->route('admin.setting.index')->with('status', $result);
    }

    public function getwebhookinfo(Request $request)
    {
        $result = $this->sendTelegramData('getwebhookinfo');
        return redirect()->route('admin.setting.index')->with('status', $result);

    }

    public function sendTelegramData($route = '', $params = [], $method = 'POST')
    {
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.telegram.org/bot' .\Telegram\Bot\Laravel\Facades\Telegram::getAccessToken() . '/']);
        $result = $client->request($method, $route, $params);
        return (string)$result->getBody();
    }
}
