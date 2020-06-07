<?php

namespace Woaap\Tools\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ToolsController extends Controller {

    /**
     * 获取 WOAAP 内部接口凭证 ackey
     *
     * @param string $url 中控域名，默认取配置，支持传参覆盖
     * @param string $appid 公众号 APPID，默认取配置，支持传参覆盖
     * @param string $appkey Woaap 系统的内部 APPKey，默认取配置，支持传参覆盖
     * @return array|mixed
     * @throws \Exception
     */
    public static function getAckey($url = '', $appid = '', $appkey = '') {
        $config = config('tools');

        $url = empty($url) ? $config['WOAAP_API_IP'] : $url;
        if (!$url) {
            throw new \Exception('请配置 WOAAP_API_IP');
        }

        $appid = empty($appid) ? $config['WOAAP_APPID'] : $appid;
        if (!$appid) {
            throw new \Exception('请配置 WOAAP_APPID');
        }

        $appkey = empty($appkey) ? $config['WOAAP_APPKEY'] : $appkey;
        if (!$appkey) {
            throw new \Exception('请配置 WOAAP_APPKEY');
        }

        if (Cache::has($appid . ':ackey')) {
            return Cache::get($appid . ':ackey');
        }

        $url .= '/api/ackey';
        $params = [
            'appid'  => $appid,
            'appkey' => $appkey
        ];

        $data = self::sendHttp($url, $params, 'get');
        if ($data['errcode'] == 0) {
            Cache::put($appid . ':ackey', $data, 1);
        }

        return $data;
    }

    /**
     * 发送公众号模板消息
     *
     * @param string $openid 公众号 openid
     * @param int $template_id 产品模板 ID （int）
     * @param array $params 模板消息中的变量，数组形式
     * @param string $url 域名，默认取配置，可以传参覆盖
     * @param string $appid 公众号 APPID，默认取配置，可以传参覆盖
     * @return array|mixed
     * @throws \Exception
     */
    public static function sendTemplateInfo($openid, int $template_id, array $params, $url = '', $appid = '') {
        $config = config('tools');

        $url = empty($url) ? $config['WOAAP_HUB_IP'] : $url;
        if (!$url) {
            throw new \Exception('请配置 WOAAP_HUB_IP');
        }

        $appid = empty($appid) ? $config['WOAAP_APPID'] : $appid;
        if (!$appid) {
            throw new \Exception('请配置 WOAAP_APPID');
        }

        $url .= '/api-sys/template-send-task';
        $info = [
            'appid'              => $appid,
            'custom_template_id' => $template_id,
            'openid'             => $openid,
            'params'             => $params,
        ];

        $data = self::sendHttp($url, $info, 'post');
        if ($data['errcode'] != 0) {
            Log::info('sendTemplateInfoError', [
                'input'  => $info,
                'output' => $data,
            ]);
        }

        return $data;
    }

    public static function sendHttp($url, $params, $method = 'get') {
        $client = new Client(['timeout' => 10]);

        $response = $client->request($method, $url, [
            'query' => $params
        ]);

        if ($response->getStatusCode() != 200)
            return [];

        $envContent = $response->getBody()->getContents();
        return json_decode($envContent, true);
    }
}
