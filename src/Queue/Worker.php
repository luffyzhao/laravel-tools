<?php


namespace LTools\Queue;


use Closure;
use Exception;
use Illuminate\Queue\WorkerOptions;
use Throwable;

class Worker extends \Illuminate\Queue\Worker
{
    protected $callBack;

    /**
     * @param $connectionName
     * @param $queue
     * @param WorkerOptions $options
     * @param Closure $callBack
     * @author luffyzhao@vip.126.com
     */
    public function daemonCallBack($connectionName, $queue, WorkerOptions $options, Closure $callBack){
        if ($this->supportsAsyncSignals()) {
            $this->listenForSignals();
        }

        $lastRestart = $this->getTimestampOfLastQueueRestart();

        $this->callBack = $callBack;

        while (true) {
            // Before reserving any jobs, we will make sure this queue is not paused and
            // if it is we will just pause this worker for a given amount of time and
            // make sure we do not need to kill this worker process off completely.
            if (! $this->daemonShouldRun($options, $connectionName, $queue)) {
                $this->pauseWorker($options, $lastRestart);

                continue;
            }

            // First, we will attempt to get the next job off of the queue. We will also
            // register the timeout handler and reset the alarm for this job so it is
            // not stuck in a frozen state forever. Then, we can fire off this job.
            $job = $this->getNextJob(
                $this->manager->connection($connectionName), $queue
            );

            if ($this->supportsAsyncSignals()) {
                $this->registerTimeoutHandler($job, $options);
            }

            // If the daemon should run (not in maintenance mode, etc.), then we can run
            // fire off this job for processing. Otherwise, we will need to sleep the
            // worker so no more jobs are processed until they should be processed.
            if ($job) {
                $this->runJob($job, $connectionName, $options);
            } else {
                $this->sleep($options->sleep);
            }

            if ($this->supportsAsyncSignals()) {
                $this->resetTimeoutHandler();
            }

            // Finally, we will check to see if we have exceeded our memory limits or if
            // the queue should restart based on other indications. If so, we'll stop
            // this worker and let whatever is "monitoring" it restart the process.
            $this->stopIfNecessary($options, $lastRestart, $job);
        }
    }

    /**
     * @param string $connectionName
     * @param \Illuminate\Contracts\Queue\Job $job
     * @param WorkerOptions $options
     * @throws Exception
     * @throws Throwable
     * @author luffyzhao@vip.126.com
     */
    public function process($connectionName, $job, WorkerOptions $options)
    {
        $callBack = $this->callBack;
        try{
            $callBack($job);
            $job->delete();
        }catch (Throwable | Exception $exception){
            if($job->attempts() <= config('csRabbitMQ.attempts', 5)){
                $job->release(30 * $job->attempts());
            }
            throw $exception;
        }
    }
}
