
# 描述

ecrm/tools 封装了 woaap 的一些常用工具，具体功能如下

- 获取中控 ackey
- 发送公众号模板消息

# 安装

```$xslt
composer require ecrm/tools

使用了 guzzlehttp/guzzle，如果项目没有安装则需要安装

composer require guzzlehttp/guzzle:~6.0
```

将服务提供商注册到当前项目（如果使用laravel 5.5+，则不需要）：

```$xslt
Ecrm\Tools\ToolsServiceProvider::class,
```

发布配置：

```text
php artisan vendor:publish  // tools_config
```

# 使用说明
需要在 `config/tools.php` 中填写你的基本配置，接口支持传参覆盖配置，建议在配置文件 `config/tools.php` 中通过 `.env` 文件获取

注意：若为空，则会抛出异常

使用样例：
```text
use Ecrm\Tools\Controllers\Woaap;
Woaap::getAckey('param1', 'param2', 'param3');
```

## 获取中控 `Woaap::getAckey`

```text
    /**
     * 获取 WOAAP 内部接口凭证 ackey
     *
     * @param string $url    中控域名，默认取配置，支持传参覆盖
     * @param string $appid  公众号 APPID，默认取配置，支持传参覆盖
     * @param string $appkey Woaap 系统的内部 APPKey，默认取配置，支持传参覆盖
     * @return array|mixed
     * @throws \Exception
     */

注：会使用 `Cache` 缓存一分钟
```


## 发送公众号模板消息 `Woaap::sendTemplateInfo`

```text
    /**
     * 发送公众号模板消息
     *
     * @param string $openid 公众号 openid
     * @param int $template_id 模板 ID （int）
     * @param array $params 模板消息中的变量，数组形式
     * @param string $url 域名，默认取配置，可以传参覆盖
     * @param string $appid 公众号 APPID，默认取配置，可以传参覆盖
     * @return array|mixed
     * @throws \Exception
     */

注：模板发送失败会自动记录 `Log::info` 日志，`sendTemplateInfoError`
```

