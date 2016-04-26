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

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['queue']->addConnector('mns', function () {
			return new MnsConnector();
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [
			'queue',
			'queue.worker',
			'queue.listener',
			'queue.failer',
			'command.queue.work',
			'command.queue.listen',
			'command.queue.restart',
			'command.queue.subscribe',
			'queue.connection'
		];
	}
}
