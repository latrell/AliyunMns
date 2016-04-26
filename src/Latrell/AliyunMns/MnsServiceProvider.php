<?php
namespace Latrell\AliyunMns;

use Illuminate\Support\ServiceProvider;

/**
 * 阿里云消息服务。
 *
 * @author Latrell Chan
 */
class MnsServiceProvider extends ServiceProvider
{

	public function boot()
	{
		$this->app['queue']->extend('mns', function () {
			return new MnsConnector();
		});
	}

	public function register()
	{
		// noop.
	}
}
