<?php
declare(strict_types=1);

namespace GameScore\Api;

use Nette\SmartObject;
use Nette\Utils\Json;

/**
 * Provides basic implementation of JSON-RPC.
 *
 * @see http://www.jsonrpc.org/specification
 *
 * @author David Jeřábek <dav.jerabek@gmail.com>
 * @package GameScore\Api
 */
abstract class JsonRpc
{
	use SmartObject;

	const JSON_RPC = 'application/json-rpc';
	const JSON = 'application/json';
	const JSON_REQUEST = 'application/jsonrequest';

	const ALLOWED_CONTENT_TYPES = array(self::JSON_RPC, self::JSON, self::JSON_REQUEST);
	const ALLOWED_ACCEPT = self::ALLOWED_CONTENT_TYPES;

	const VERSION = "2.0";

	const PARSE_ERROR = -32700;
	const INVALID_REQUEST = -32600;
	const METHOD_NOT_FOUND = -32601;
	const INVALID_PARAMS = -32602;
	const INTERNAL_ERROR = -32603;
	const SERVER_ERROR = -32000;

	/**
	 * Asserts that the content type is valid.
	 *
	 * @param string $value
	 *
	 * @throws InvalidRequestException
	 */
	public static function assetValidContentType(string $value)
	{
		if (in_array($value, self::ALLOWED_CONTENT_TYPES)) return;

		throw new InvalidRequestException('Unsupported content type.');
	}

	/**
	 * Asserts that accept type is valid.
	 *
	 * @param string $value
	 *
	 * @throws InvalidRequestException
	 */
	public static function assetValidAccept(string $value)
	{
		$match = array_filter(explode(',', $value), function ($part) {
			return strlen($part) > 0 && in_array($part, self::ALLOWED_ACCEPT, true);
		});

		if (count($match) === 0) throw new InvalidRequestException('Unsupported accept type.');
	}

	/**
	 * Asserts that the version is valid.
	 *
	 * @param string $value
	 *
	 * @throws InvalidRequestException
	 */
	public static function assetValidVersion(string $value)
	{
		if ($value !== self::VERSION) {

			throw new InvalidRequestException('Unsupported version of JSON-RPC.');

		}
	}

	/** @var string */
	private $version;

	/** @var int|null */
	private $id;

	/**
	 * JsonRpc constructor.
	 *
	 * @param string $version
	 * @param int|null $id
	 *
	 * @throws InvalidRequestException
	 */
	public function __construct(string $version = self::VERSION, int $id = null)
	{
		self::assetValidVersion($version);

		$this->version = $version;
		$this->id = $id;
	}

	/**
	 * Returns the version of JSON-RPC.
	 *
	 * @return string
	 */
	public function getVersion(): string
	{
		return $this->version;
	}

	/**
	 * Returns the unique id of request.
	 *
	 * @return int|null
	 */
	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * Builds params of JSON-RPC object.
	 *
	 * @return string[]
	 */
	protected function buildParams(): array
	{
		return array(
			'jsonrpc' => $this->getVersion(),
			'id' => $this->getId()
		);
	}

	/**
	 * Returns the string representation of JSON-RPC object.
	 *
	 * @return string
	 */
	public function getJson(): string
	{
		return Json::encode($this->buildParams());
	}

	/**
	 * @inheritdoc
	 */
	public function __toString()
	{
		return $this->getJson();
	}
}
