<?php
declare(strict_types=1);

namespace GameScore\Api;

use Exception;
use Throwable;

class ApiException extends Exception
{


}

class ServerErrorException extends ApiException
{
	public function __construct(string $message = "Server error.", int $code = JsonRpc::SERVER_ERROR, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}

class InternalErrorException extends ApiException
{
	public function __construct(string $message = "", int $code = JsonRpc::INTERNAL_ERROR, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}

class InvalidParameterException extends ApiException
{
	public function __construct(string $message = "", int $code = JsonRpc::INVALID_PARAMS, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}

class InvalidRequestException extends ApiException
{

	public function __construct(string $message = "", int $code = JsonRpc::INVALID_REQUEST, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

}

class MethodNotFoundException extends ApiException
{

	public function __construct(string $message = "", int $code = JsonRpc::METHOD_NOT_FOUND, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

}

class ParseErrorException extends ApiException
{
	public function __construct(string $message = "", int $code = JsonRpc::PARSE_ERROR, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}