<?php
declare(strict_types=1);

namespace GameScore\Utils;

use Nette\StaticClass;

/**
 * Class Random.
 *
 * @author David Jeřábek <david@thezech.io>
 * @package GameScore\Utils
 */
class Random
{

	use StaticClass;

	/**
	 * Returns a random row from list.
	 *
	 * @param array $list
	 * @return mixed
	 */
	public static function fromArray(array $list)
	{
		$values = array_values($list);
		return $values[rand(0, count($list) - 1)];
	}
}
