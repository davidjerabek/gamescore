<?php
declare(strict_types=1);

namespace GameScore\Model;

use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use Nette\SmartObject;

/**
 * Class BaseModel.
 *
 * @author David Jeřábek <dav.jerabek@gmail.com>
 * @package GameScore\Model
 */
abstract class BaseModel
{
	use SmartObject;

	/** @var Context */
	private $context;

	/**
	 * BaseModel constructor.
	 *
	 * @param Context $context
	 */
	public function __construct(Context $context)
	{
		$this->context = $context;
	}

	/**
	 * @inheritdoc
	 */
	protected function getContext(): Context
	{
		return $this->context;
	}

	/**
	 * Returns a row find by specified id.
	 *
	 * @param int $id
	 *
	 * @return ActiveRow|null
	 */
	protected function find(int $id): ?ActiveRow
	{
		$row = $this->getTableSelection()
			->get($id);

		return $row ?: null;
	}

	/**
	 * Creates a new row.
	 *
	 * @param array <string,string> $columns
	 *
	 * @return ActiveRow
	 */
	protected function create(array $columns)
	{
		return $this->getTableSelection()
			->insert($columns);
	}

	/**
	 * Returns the name of the table.
	 *
	 * @return string
	 */
	protected abstract function getTable(): string;

	/**
	 * @inheritdoc
	 */
	protected function getTableSelection(): Selection
	{
		return $this->getContext()->table($this->getTable());
	}
}
