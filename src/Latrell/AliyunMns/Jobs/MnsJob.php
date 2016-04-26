<?php
namespace Latrell\AliyunMns\Jobs;

use AliyunMNS\Queue;
use AliyunMNS\Responses\ReceiveMessageResponse;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Container\Container;
use Illuminate\Contracts\Queue\Job as JobContract;

class MnsJob extends Job implements JobContract
{

	/**
	 * The underlying AliyunMNS Queue instance.
	 *
	 * @var \AliyunMNS\Queue
	 */
	protected $client;

	/**
	 * The underlying AliyunMNS ReceiveMessageResponse job instance.
	 *
	 * @var \AliyunMNS\Responses\ReceiveMessageResponse
	 */
	protected $job;

	/**
	 * Create a new job instance.
	 *
	 * @param  \Illuminate\Container\Container  $container
	 * @param  \AliyunMNS\Queue  $client
	 * @param  \AliyunMNS\Responses\ReceiveMessageResponse   $job
	 * @return void
	 */
	public function __construct(Container $container, Queue $client, ReceiveMessageResponse $job)
	{
		$this->container = $container;
		$this->client = $client;
		$this->job = $job;
	}

	/**
	 * Fire the job.
	 *
	 * @return void
	 */
	public function fire()
	{
		$this->resolveAndFire(json_decode($this->getRawBody(), true));
	}

	/**
	 * Get the raw body string for the job.
	 *
	 * @return string
	 */
	public function getRawBody()
	{
		return $this->job->getMessageBody();
	}

	/**
	 * Delete the job from the queue.
	 *
	 * @return void
	 */
	public function delete()
	{
		parent::delete();

		$this->client->deleteMessage($this->job->getReceiptHandle());
	}

	/**
	 * Release the job back into the queue.
	 *
	 * @param  int   $delay
	 * @return void
	 */
	public function release($delay = 0)
	{
		parent::release($delay);

		$this->client->changeMessageVisibility($this->job->getReceiptHandle(), $delay);
	}

	/**
	 * Get the number of times the job has been attempted.
	 *
	 * @return int
	 */
	public function attempts()
	{
		return (int) $this->job->getDequeueCount();
	}

	/**
	 * Get the job identifier.
	 *
	 * @return string
	 */
	public function getJobId()
	{
		return $this->job->getMessageId();
	}

	/**
	 * Get the IoC container instance.
	 *
	 * @return \Illuminate\Container\Container
	 */
	public function getContainer()
	{
		return $this->container;
	}

	/**
	 * Get the underlying AliyunMNS Queue instance.
	 *
	 * @return \AliyunMNS\Queue
	 */
	public function getClient()
	{
		return $this->client;
	}

	/**
	 * Get the underlying AliyunMNS raw ReceiveMessageResponse job.
	 *
	 * @return array
	 */
	public function getQueueJob()
	{
		return $this->job;
	}
}
