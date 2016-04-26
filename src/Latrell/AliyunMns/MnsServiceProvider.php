<?php
namespace Latrell\AliyunMns;

use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\QueueManager;

/**
 * 阿里云消息服务。
 *
 * @author Latrell Chan
 */
class MnsServiceProvider extends ServiceProvider
{

	public function boot(QueueManager $queue)
	{
		$queue->extend('mns', function () {
			return new MnsConnector();
		});
	}
}
