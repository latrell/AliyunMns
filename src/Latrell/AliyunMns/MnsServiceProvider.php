<?php
namespace Latrell\AliyunMns;

use Queue;
use Illuminate\Support\ServiceProvider;
use Latrell\AliyunMns\Connectors\MnsConnector;

/**
 * 阿里云消息服务。
 *
 * @author Latrell Chan
 */
class MnsServiceProvider extends ServiceProvider
{

	public function boot()
	{
		Queue::extend('mns', function () {
			return new MnsConnector();
		});
	}

	public function register()
	{
		//
	}
}
