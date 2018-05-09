<?php
declare(strict_types=1);

namespace GameScore\Model;


use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;

/**
 * Class ScoreboardModel.
 *
 * @author David Jeřábek <dav.jerabek@gmail.com>
 * @package GameScore\Model
 */
class ScoreboardModel extends BaseModel
{

	/** @var UserModel */
	private $userModel;

	/** @var GameModel */
	private $gameModel;

	/**
	 * @inheritdoc
	 */
	protected function getTable(): string
	{
		return 'scoreboard';
	}

	/**
	 * ScoreboardModel constructor.
	 *
	 * @param Context $context
	 * @param GameModel $gameModel
	 * @param UserModel $userModel
	 */
	public function __construct(Context $context, GameModel $gameModel, UserModel $userModel)
	{
		$this->userModel = $userModel;
		$this->gameModel = $gameModel;

		parent::__construct($context);
	}

	/**
	 * @inheritdoc
	 *
	 * @param int $gameId
	 * @param int $userId
	 * @param int $score
	 */
	public function add(int $gameId, int $userId, int $score)
	{
		$game = $this->gameModel->getGame($gameId);
		$user = $this->userModel->getUser($userId);

		$this->create(array(
			'game_id' => $game->offsetGet('id'),
			'user_id' => $user->offsetGet('id'),
			'value' => $score
		));

	}

	/**
	 * Returns an array of top rows for specified gameId.
	 *
	 * @param int $gameId
	 * @param int $limit
	 *
	 * @return array
	 */
	public function getTop(int $gameId, int $limit): array
	{
		$rows = $this->getTableSelection()
			->where(array(
				'game_id' => $gameId
			))
			->limit($limit)
			->order('value DESC, inserted DESC')
			->fetchAll();

		$currentScore = null;
		$result = array();
		$rank = 0;

		array_walk($rows, function (ActiveRow $row) use (&$currentScore, &$rank, &$result) {

			$score = $row->offsetGet('value');

			if ($score !== $currentScore || is_null($currentScore)) {

				$currentScore = $score;
				$rank++;

			}

			$result[] = array(
				'rank' => $rank,
				'score' => $row->offsetGet('value'),
				'created' => (string)$row->offsetGet('inserted'),
				'game' => $row->offsetGet('game')->offsetGet('name'),
				'user' => $row->offsetGet('user')->offsetGet('name'),
				'user_id' => $row->offsetGet('user_id'),
				'game_id' => $row->offsetGet('game_id')
			);


		});

		return $result;

	}
}
