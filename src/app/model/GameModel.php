<?php
declare(strict_types=1);

namespace GameScore\Model;

use GameScore\Utils\Random;
use Nette\Database\Table\ActiveRow;

/**
 * Class GameModel.
 *
 * @author David Jeřábek <dav.jerabek@gmail.com>
 * @package GameScore\Model
 */
class GameModel extends BaseModel
{

	const NAME_LIST = array(
		'Morticia',
		'Bewitch',
		'Eternity',
		'Aura',
		'Ebony',
		'Fawn',
		'Sybil',
		'Iris',
		'Blanca',
		'Brandywine',
		'Magnolia',
		'Ganna',
		'Chantel',
		'Weaver',
		'Opium',
		'Lotus'
	);

	/**
	 * @inheritdoc
	 */
	protected function getTable(): string
	{
		return 'game';
	}

	/**
	 * Returns the game find by specified gameId.
	 *
	 * @param int $gameId
	 *
	 * @return ActiveRow|null
	 */
	public function getGame(int $gameId): ActiveRow
	{
		return $this->find($gameId) ?: $this->createGame($gameId);
	}

	/**
	 * Creates a new game.
	 *
	 * @param int $gameId
	 * @return ActiveRow
	 */
	public function createGame(int $gameId)
	{
		return $this->create([
			'id' => $gameId,
			'name' => $this->getRandomName()
		]);
	}

	/**
	 * Returns the random name from list of names.
	 *
	 * @return string
	 */
	private function getRandomName(): string
	{
		return Random::fromArray(self::NAME_LIST);
	}

}
