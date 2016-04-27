<?php
namespace Latrell\AliyunMns;

use Illuminate\Queue\QueueServiceProvider;
use Latrell\AliyunMns\Connectors\MnsConnector;

/**
 * 阿里云消息服务。
 *
 * @author Latrell Chan
 */
class MnsServiceProvider extends QueueServiceProvider
{

	/**
	 * Register the connectors on the queue manager.
	 *
	 * @param  \Illuminate\Queue\QueueManager  $manager
	 * @return void
	 */
	public function registerConnectors($manager)
	{
		parent::registerConnectors($manager);

		$this->registerMnsConnector($manager);
	}

	/**
	 * Register the Aliyun Mns queue connector.
	 *
	 * @param  \Illuminate\Queue\QueueManager  $manager
	 * @return void
	 */
	protected function registerMnsConnector($manager)
	{
		$manager->addConnector('mns', function () {
			return new MnsConnector();
		});
	}
}
