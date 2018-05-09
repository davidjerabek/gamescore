<?php
declare(strict_types=1);

namespace GameScore\Api\Scoreboard;

use GameScore\Api\BaseMethod;
use GameScore\Api\InvalidParameterException;
use GameScore\Model\ScoreboardModel;

/**
 * Class SaveScoreMethod.
 *
 * @author David Jeřábek <dav.jerabek@gmail.com>
 * @package GameScore\Api\Scoreboard
 */
class SaveScoreMethod extends BaseMethod
{
	/** @var ScoreboardModel */
	private $scoreboardModel;

	/** @var int */
	private $userId;

	/** @var int */
	private $gameId;

	/** @var int */
	private $score;

	/**
	 * SaveScorePoint constructor.
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
		return 'save';
	}

	/**
	 * @inheritdoc
	 */
	protected function assertParams(array $params)
	{
		$requiredParams = array('userId', 'gameId', 'score');

		foreach ($requiredParams as $key => $parameter) {

			$hasParameter = array_key_exists($parameter, $params);

			if ($hasParameter && is_int($params[$parameter])) {

				$this->{$parameter} = $params[$parameter];
				unset($requiredParams[$key]);

			} else if ($hasParameter) {

				throw new InvalidParameterException(sprintf('Parameter `%s` must be int.', $parameter));

			} else {

				throw new InvalidParameterException(sprintf('Parameter `%s` is missing.', $parameter));

			}

		}

		if (count($requiredParams)) {

			$message = vsprintf('Missing required parameter%s %s.', array(
				count($requiredParams) === 1 ? null : 's',
				implode(', ', $requiredParams)
			));

			throw new InvalidParameterException($message);
		}

	}

	/**
	 * @inheritdoc
	 */
	protected function process()
	{
		$this->scoreboardModel->add($this->gameId, $this->userId, $this->score);
	}
}
