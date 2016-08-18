
AliyunMns
======

使用阿里云消息服务（MNS ）作为Laravel5队列驱动。

## 安装

添加依赖包。

```
composer require icemanpro/aliyun-mns-laravel dev-master
```

## 使用

找到 `config/app.php` 配置文件中，key为 `providers` 的数组，在数组中添加服务提供者。

```php
	'providers' => [
		// ...
		Latrell\AliyunMns\MnsServiceProvider::class,
	]
```

打开队列配置文件 `config/queue.php`，修改驱动为 `mns` 。

服务地地址参考：https://help.aliyun.com/document_detail/mns/api_reference/invoke/request.html?spm=5176.docmns/introduction/concepts.6.150.uNuU4m

```php
	'connections' => [
		// ...
		'mns' => [
            'driver' => 'mns',
            'access_id' => 'your-access-key-id',
            'access_key' => 'your-access-key-secret',
            'security_token' => null,
            'end_point' => 'http(s)://{AccountId}.mns.cn-hangzhou.aliyuncs.com',
            'queue' => 'your-queue-name'
        ],
	],
```

## SDK 版本号
已含aliyunMNS SDK 1.3.1
