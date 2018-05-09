<?php
declare(strict_types=1);

namespace GameScore\Api\Scoreboard;

use GameScore\Api\BaseMethod;
use GameScore\Api\InvalidParameterException;
use GameScore\Model\ScoreboardModel;

/**
 * Class TopRatedMethod.
 *
 * @author David Jeřábek <dav.jerabek@gmail.com>
 * @package GameScore\Api\Scoreboard
 */
class TopRatedMethod extends BaseMethod
{

	/** @var ScoreboardModel */
	private $scoreboardModel;

	/** @var int */
	private $gameId;

	/** @var int */
	private $limit = 10;

	/**
	 * TopRatedMethod constructor.
	 *
	 * @param ScoreboardModel $scoreboardModel
	 */
	public function __construct(ScoreboardModel $scoreboardModel)
	{
		$this->scoreboardModel = $scoreboardModel;
	}

	/**
	 * @inheritdoc
	 */
	public function getName(): string
	{
		return 'top';
	}

	/**
	 * @inheritdoc
	 */
	protected function assertParams(array $params)
	{
		$hasGameId = isset($params['gameId']);

		if ($hasGameId && is_int($params['gameId'])) {

			$this->gameId = $params['gameId'];

		} elseif ($hasGameId) throw new InvalidParameterException('Parameter `gameId` must be int.');
		else throw new InvalidParameterException('Parameter `gameId` is missing.');

		if (isset($params['limit']) && is_int($params['limit'])) {

			$this->limit = $params['limit'];

		}

	}

	/**
	 * @inheritdoc
	 */
	public function process(): array
	{
		return $this->scoreboardModel->getTop($this->gameId, $this->limit);
	}


}
