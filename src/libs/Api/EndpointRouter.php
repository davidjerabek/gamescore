<?php
declare(strict_types=1);

namespace GameScore\Api;

use Nette\Application\IRouter;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Nette\Utils\Strings;

/**
 * Class EndpointRouter.
 *
 * @author David Jeřábek <dav.jerabek@gmail.com>
 * @package GameScore\Api
 */
class EndpointRouter extends RouteList
{

	const DEFAULT_PREFIX = 'api';

	/**
	 * EndpointRouter constructor.
	 *
	 * @param EndpointLoader $endpointLoader
	 * @param string $prefix
	 */
	public function __construct(EndpointLoader $endpointLoader, string $prefix = self::DEFAULT_PREFIX)
	{
		parent::__construct(Strings::firstUpper($prefix));

		foreach ($endpointLoader->getEndpoints() as $key => $endpoint) {

			$presenterName = Strings::firstUpper($endpoint->getName());
			$this[] = new Route($prefix . '/' . $endpoint->getPath(), $presenterName . ':default');

		}
	}

	/**
	 * Inserts the EndpointRouter before a given router.
	 *
	 * @param IRouter $router
	 * @param EndpointRouter $endpointRouter
	 *
	 * @return RouteList
	 */
	public static function insertBefore(IRouter &$router, EndpointRouter $endpointRouter)
	{
		$router[] = $endpointRouter;

		$lastKey = count($router) - 1;

		foreach ($router as $i => $route) {

			if ($i === $lastKey) {
				break;
			}

			$router[$i + 1] = $route;
		}

		$router[0] = $endpointRouter;
	}
}
