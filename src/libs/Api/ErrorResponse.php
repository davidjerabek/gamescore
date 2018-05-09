<?php
declare(strict_types=1);

namespace GameScore\Api;

/**
 * The basic representation of the JSON-RPC error response object.
 *
 * @see http://www.jsonrpc.org/specification#error_object
 *
 * @author David Jeřábek <dav.jerabek@gmail.com>
 * @package GameScore\Api
 *
 */
class ErrorResponse extends Response
{
	/** @var int */
	private $code;

	/** @var string */
	private $message;

    /**
     * ErrorResponse constructor.
     *
     * @param Request $request
     * @param int $code$
     * @param string $message
     *
     * @throws InvalidRequestException
     */
	public function __construct(Request $request = null, int $code = JsonRpc::INTERNAL_ERROR, string $message = 'Internal error.')
	{
		parent::__construct($request);

		$this->message = $message;
		$this->code = $code;

	}

	/**
	 * Returns an array with error params.
	 *
	 * @return array
	 */
	protected function createError(): array
	{
		return array(
			'code' => $this->getCode(),
			'message' => $this->getMessage()
		);
	}

	/**
	 * @inheritdoc
	 */
	protected function buildParams(): array
	{
		$params = parent::buildParams();
		$params['error'] = $this->createError();
		return $params;
	}

	/**
	 * Returns the error code.
	 *
	 * @return int
	 */
	public function getCode(): int
	{
		return $this->code;
	}

	/**
	 * Returns the short description of error.
	 *
	 * @return string
	 */
	public function getMessage(): string
	{
		return $this->message;
	}
}
