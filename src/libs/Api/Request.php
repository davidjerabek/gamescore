<?php
declare(strict_types=1);

namespace GameScore\Api;

use Nette\Utils\ArrayHash;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

/**
 * The basic implementation of JSON-RPC request object.
 *
 * @see http://www.jsonrpc.org/specification#request_object
 *
 * @author David Jeřábek <dav.jerabek@gmail.com>
 * @package GameScore\Api
 *
 */
class Request extends JsonRpc
{

	/**
	 * Attempts to parse the specified input value as ArrayHash.
	 *
	 * @param string $input
	 *
	 * @return ArrayHash
	 *
	 * @throws ParseErrorException
	 */
	private static function tryParse(string $input): ArrayHash
	{
		try {

			return ArrayHash::from(Json::decode($input));

		} catch (JsonException $e) {

			//TODO: Use tokenizer to get more specific error.
			throw new ParseErrorException('An error occurred while parsing the JSON.');
		}
	}

	/**
	 * Creates a new instance of Request from array.
	 *
	 * @param array $params
	 *
	 * @return Request
	 * @throws InvalidRequestException
	 */
	public static function fromArray(array $params)
	{
		$properties = array();

		foreach ($requiredProperties = array('jsonrpc', 'method', 'id') as $index => $property) {

			if (isset($params[$property])) {

				$properties[$index] = $params[$property];

			} else throw new InvalidRequestException(sprintf("The property `%s` is missing.", $property));

		}

		list($version, $method, $id) = $properties;

		$params = isset($params['params']) ? $params['params'] : array();

		if (is_string($params) && strlen($params) > 0) {

			$params = self::tryParse($params);

		}

		return new self((string)$version, (string)$method, (array)$params, (int)$id);
	}

	/**
	 * Creates a new instances of Request from JSON-RPC.
	 *
	 * @param string $json
	 *
	 * @return Request[]
	 *
	 * @throws ParseErrorException
	 * @throws InvalidRequestException
	 */
	public static function fromJson(string $json): array
	{
		$json = strlen($json) > 0 ? self::tryParse($json) : array();

		if ($json->offsetExists('jsonrpc')) {

			return array(self::fromArray((array)$json));

		}

		return array_map(function ($request) {

			return self::fromArray((array)$request);

		}, (array)$json);
	}

	/** @var string */
	private $method;

	/** @var mixed */
	private $params;

	/**
	 * Request constructor.
	 *
	 * @param string $version
	 * @param string $method
	 * @param array $params
	 * @param int $id
	 *
	 * @throws InvalidRequestException
	 */
	public function __construct(string $version, string $method, array $params = array(), int $id)
	{
		parent::__construct($version, $id);

		$this->method = $method;
		$this->params = $params;

	}

	/**
	 * @inheritdoc
	 */
	protected function buildParams(): array
	{
		$params = parent::buildParams();

		$params['method'] = $this->getMethod();
		$params['params'] = $this->getParams();

		return $params;
	}

	/**
	 * Returns the name of method to be invoked.
	 *
	 * @return string
	 */
	public function getMethod(): string
	{
		return $this->method;
	}

	/**
	 * Returns the request params.
	 *
	 * @return mixed
	 */
	public function getParams()
	{
		return $this->params;
	}
}

