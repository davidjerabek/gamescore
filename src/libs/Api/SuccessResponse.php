<?php
declare(strict_types=1);

namespace GameScore\Api;

/**
 * The basic representation of the JSON-RPC success response object.
 *
 * @see http://www.jsonrpc.org/specification#response_object
 *
 * @author David Jeřábek <dav.jerabek@gmail.com>
 * @package GameScore\Api
 *
 */
class SuccessResponse extends Response
{
	/** @var mixed */
	private $result;

	/**
	 * SuccessResponse constructor.
	 *
	 * @param Request $request
	 * @param int|string|array $result
	 *
	 * @throws InvalidRequestException
	 */
	public function __construct(Request $request, $result = null)
	{
		parent::__construct($request);
		$this->result = $result ?: 'ok';
	}

	/**
	 * @inheritdoc
	 */
	protected function buildParams(): array
	{
		$params = parent::buildParams();
		$params['result'] = $this->getResult();

		return $params;
	}

	/**
	 * Returns the result of success response.
	 *
	 * @return mixed
	 */
	public function getResult()
	{
		return $this->result;
	}
}
