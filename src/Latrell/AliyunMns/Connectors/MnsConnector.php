<?php
namespace Latrell\AliyunMns\Connectors;

use AliyunMNS\Client;
use Latrell\AliyunMns\MnsQueue;

class MnsConnector implements ConnectorInterface
{

	/**
	 * Establish a queue connection.
	 *
	 * @param  array  $config
	 * @return \Illuminate\Contracts\Queue\Queue
	 */
	public function connect(array $config)
	{
		$client = new Client($config['end_point'], $config['access_id'], $config['access_key'], $config['security_token']);

		return new MnsQueue($client, $config['queue']);
	}
}
