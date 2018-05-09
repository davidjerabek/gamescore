<?php
declare(strict_types=1);

namespace GameScore\Api;

use Nette\Application\InvalidPresenterException;
use Nette\Utils\Strings;
use RuntimeException;

/**
 * Class EndpointLoader.
 *
 * @author David Jeřábek <dav.jerabek@gmail.com>
 * @package GameScore\Api
 */
class EndpointLoader
{

	/** @var array */
	private $endpoints = array();

	/**
	 * Adds the endpoint to loader.
	 *
	 * @param BaseEndpoint $endpoint
	 * @throws InvalidPresenterException
	 */
	public function addEndpoint(BaseEndpoint $endpoint)
	{

		$name = $endpoint->getName();

		if (!Strings::match($name, '#^[a-zA-Z\x7f-\xff][a-zA-Z0-9\x7f-\xff:]*\z#')) {

			throw new InvalidPresenterException("Endpoint name must be alphanumeric string, '$name' is invalid.");

		}

		if ($this->hasEndpoint($name)) {

			throw new RuntimeException(sprintf('Endpoint `%s` is already added.', $name));

		}

		$this->endpoints[$name] = $endpoint;

	}

	/**
	 * Returns a value indicating if the endpoint exist.
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function hasEndpoint(string $name): bool
	{
		return array_key_exists($name, $this->endpoints);
	}

	/**
	 * Returns the endpoint find by specified name.
	 *
	 * @param string $name
	 *
	 * @return BaseEndpoint|null
	 */
	public function getEndpoint(string $name): ?BaseEndpoint
	{
		return $this->hasEndpoint($name) ? $this->endpoints[$name] : null;
	}

	/**
	 * Returns an array of Endpoints.
	 *
	 * @return BaseEndpoint[]
	 */
	public function getEndpoints(): array
	{
		return $this->endpoints;
	}
}
