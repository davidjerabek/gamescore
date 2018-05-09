<?php
declare(strict_types=1);

namespace GameScore\Model;

use GameScore\Utils\Random;
use Nette\Database\Table\ActiveRow;

/**
 * Class UserModel.
 *
 * @author David Jeřábek <dav.jerabek@gmail.com>
 * @package GameScore\Model
 */
class UserModel extends BaseModel
{

	const NAME_LIST = array(
		'Jene',
		'Antonietta',
		'Alena',
		'Lucius',
		'Kizzy',
		'Kaleigh',
		'Jerica',
		'Exie',
		'Wilma',
		'Enid',
		'Phuong',
		'Jefferey',
		'Lola',
		'Jude',
		'Karma',
		'Celestina',
		'Matilda',
		'Monica',
		'Brittni',
		'Valorie',
		'Shila',
		'Tiffany',
		'Dierdre',
		'Sylvie',
		'Lorena',
		'Darci',
		'Rivka',
		'Chin',
		'Micheal',
		'Eugenio',
		'Jennifer',
		'Bob',
		'Queen',
		'Lue',
		'Eugena',
		'Yuk',
		'Dorothea',
		'Modesta',
		'Brooks',
		'Laurinda',
		'Jazmin',
		'Scottie',
		'Lavona',
		'Hassan',
		'Lyndon',
		'Mickey',
		'Tia',
		'Claude',
		'Alphonse',
		'Cecille'
	);

	/**
	 * Returns the user find by specified userId.
	 *
	 * @param int $userId
	 * @return ActiveRow
	 */
	public function getUser(int $userId): ActiveRow
	{
		return $this->find($userId) ?: $this->createUser($userId);
	}

	/**
	 * Creates a new user.
	 *
	 * @param int $userId
	 *
	 * @return ActiveRow
	 */
	public function createUser(int $userId): ActiveRow
	{
		return $this->create([
			'id' => $userId,
			'name' => $this->getRandomName()
		]);
	}

	/**
	 * @inheritdoc
	 */
	protected function getTable(): string
	{
		return 'user';
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
