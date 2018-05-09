<?php
declare(strict_types=1);

namespace GameScore\Api;

/**
 * Class ScoreboardEndpoint.
 *
 * @author David Jeřábek <dav.jerabek@gmail.com>
 * @package GameScore\Api
 */
class ScoreboardEndpoint extends BaseEndpoint
{
	/**
	 * @inheritdoc
	 */
	public function getName(): string
	{
		return 'scoreboard';
	}

	/**
	 * @inheritdoc
	 */
	public function getPath(): string
	{
		return $this->getName();
	}
}
