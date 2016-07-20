<?php
namespace Latrell\AliyunMns;

use Illuminate\Support\Arr;
use AliyunMNS\Client;
use AliyunMNS\Requests\SendMessageRequest;
use AliyunMNS\Exception\MessageNotExistException;
use Latrell\AliyunMns\Jobs\MnsJob;
use Illuminate\Queue\Queue;
use Illuminate\Contracts\Queue\Queue as QueueContract;

class MnsQueue extends Queue implements QueueContract
{

	/**
	 * The AliyunMNS instance.
	 *
	 * @var \AliyunMNS\Client
	 */
	protected $client;

	/**
	 * The name of the default tube.
	 *
	 * @var string
	 */
	protected $default;

	/**
	 * Create a new AliyunMNS queue instance.
	 *
	 * @param  \AliyunMNS\Client  $client
	 * @param  \Illuminate\Http\Request  $request
	 * @param  string  $default
	 * @return void
	 */
	public function __construct(Client $client, $default)
	{
		$this->client = $client;
		$this->default = $default;
	}

	/**
	 * Push a new job onto the queue.
	 *
	 * @param  string  $job
	 * @param  mixed   $data
	 * @param  string  $queue
	 * @return mixed
	 */
	public function push($job, $data = '', $queue = null)
	{
		return $this->pushRaw($this->createPayload($job, $data), $queue);
	}

	/**
	 * Push a raw payload onto the queue.
	 *
	 * @param  string  $payload
	 * @param  string  $queue
	 * @param  array   $options
	 * @return mixed
	 */
	public function pushRaw($payload, $queue = null, array $options = [])
	{
		$request = new SendMessageRequest($payload, Arr::get($options, 'delay'), null, false);

		return $this->getQueueRef($queue)
			->sendMessage($request)
			->getMessageId();
	}

	/**
	 * Push a new job onto the queue after a delay.
	 *
	 * @param  \DateTime|int  $delay
	 * @param  string  $job
	 * @param  mixed   $data
	 * @param  string  $queue
	 * @return mixed
	 */
	public function later($delay, $job, $data = '', $queue = null)
	{
		$payload = $this->createPayload($job, $data);

		$delay = $this->getSeconds($delay);

		return $this->pushRaw($payload, $queue, compact('delay'));
	}

	/**
	 * Pop the next job off of the queue.
	 *
	 * @param  string  $queue
	 * @return \Illuminate\Contracts\Queue\Job|null
	 */
	public function pop($queue = null)
    {
		$client = $this->getQueueRef($queue);
		try {
			$job = $client->receiveMessage();
		} catch (MessageNotExistException $e) {
			$job = null;
		}

		if (! is_null($job) && $job->isSucceed()) {
			return new MnsJob($this->container, $client, $job);
		}
	}

	/**
	 * Delete a message from the Iron queue.
	 *
	 * @param  string  $queue
	 * @param  string  $id
	 * @return void
	 */
	public function deleteMessage($queue, $receiptHandle)
	{
		$queueName = $this->getQueue($queue);
		$client = $this->getQueueRef($queueName, false);
		$client->deleteMessage($receiptHandle);
	}

	/**
	 * Create a payload string from the given job and data.
	 *
	 * @param  string  $job
	 * @param  mixed   $data
	 * @param  string  $queue
	 * @return string
	 */
	protected function createPayload($job, $data = '', $queue = null)
	{
		$payload = parent::createPayload($job, $data);

		$payload = $this->setMeta($payload, 'attempts', 1);

		$payload = $this->setMeta($payload, 'queue', $this->getQueue($queue));

		return $payload;
	}

	/**
	 * Get the AliyunMNS Queue instance.
	 *
	 * @param  string  $queue
	 * @return \AliyunMNS\Queue
	 */
	public function getQueueRef($queue)
	{
		return $this->client->getQueueRef($this->getQueue($queue), false);
	}

	/**
	 * Get the queue or return the default.
	 *
	 * @param  string|null  $queue
	 * @return string
	 */
	public function getQueue($queue)
	{
		return $queue ?: $this->default;
	}

	/**
	 * Get the AliyunMNS Client instance.
	 *
	 * @return \AliyunMNS\Client
	 */
	public function getClient()
	{
		return $this->client;
	}
}
