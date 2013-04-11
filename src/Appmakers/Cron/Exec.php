<?php namespace Appmakers\Cron;

use Closure, Event;
use Cron\CronExpression;

/**
 * Execution helper for wrapping evaluation of cron expressions
 */
class Exec {
	const EVENT = 'appmakers.cron.tick';

	protected $cron;
	protected $callback;

	/**
	 * Create a scheduled execution
	 */
	public static function schedule($cron_expression, Closure $callback)
	{
		// Create the execution helper
		$exec = new Exec($cron_expression, $callback);

		// Register for cron tick event
		Event::listen(self::EVENT, function() use ($exec) {
			$exec->run();
		});

		// Return the helper
		return $exec;
	}

	/**
	 * This function triggers the cron event. Should be called every minute.
	 */
	public static function tick()
	{
		Event::fire(self::EVENT);
	}

	/**
	 * Evaluate the cron expression and run if task is due
	 */
	public function run()
	{
		if($this->cron->isDue()) {
			return call_user_func($this->callback, $this->cron);
		}
	}

	/**
	 * Only allow instantiation via schedule()
	 */
	private function __construct($cron_expression, Closure $callback)
	{
		$this->cron = CronExpression::factory($cron_expression);
		$this->callback = $callback;
	}
}