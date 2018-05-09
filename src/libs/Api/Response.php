<?php
declare(strict_types=1);

namespace GameScore\Api;

/**
 * The basic representation of JSON-RPC response object.
 *
 * @author David Jeřábek <dav.jerabek@gmail.com>
 * @package GameScore\Api
 */
abstract class Response extends JsonRpc
{

	/** @var Request */
	private $request;

	/**
	 * Response constructor.
	 *
	 * @param Request $request
	 * @throws InvalidRequestException
	 */
	public function __construct(Request $request = null)
	{
		$version = $request ? $request->getVersion() : self::VERSION;
		$id = $request ? $request->getId() : null;

		parent::__construct($version, $id);

		$this->request = $request;
	}

	/**
	 * @inheritdoc
	 */
	protected function getRequest(): Request
	{
		return $this->request;
	}
}
