<?php
declare(strict_types=1);

namespace GameScore\Api\DI;

use Nette\DI\CompilerExtension;
use Nette\Utils\ArrayHash;
use Nette\Utils\Strings;

/**
 * Class ApiExtension.
 *
 * @author David Jeřábek <dav.jerabek@gmail.com>
 * @package GameScore\DI
 */
class ApiExtension extends CompilerExtension
{
	/**
	 * @inheritdoc
	 */
	public function loadConfiguration()
	{
		$configuration = $this->getConfig();
		$builder = $this->getContainerBuilder();

		$loader = $builder->addDefinition($this->prefix('loader'))
			->setFactory($configuration->offsetGet('loader'));

		$builder->addDefinition($this->prefix('router'))
			->setFactory($configuration->offsetGet('router'), array($loader, $this->name))
			->setAutowired(false);

		if ($configuration->offsetExists('endpoints') && $endpoints = $configuration->offsetGet('endpoints')) {

			/** @var ArrayHash $endpoint */
			foreach ($endpoints as $key => $endpoint) {

				$class = $endpoint->offsetGet('class');
				$methods = $endpoint->offsetGet('methods');

				//TODO: endpoint maybe an instance of Nette\DI\Statement
				$endpointDefinition = $builder->addDefinition($this->prefix($key))
					->setFactory($class);

				foreach ($methods as $method) {

					//TODO: method maybe an instance of Nette\DI\Statement
					$methodDefinition = $builder->addDefinition($this->getPrefixedNameFromNamespace($method))
						->setFactory($method);

					$endpointDefinition->addSetup('addMethod', array($methodDefinition));

				}

				$loader->addSetup('addEndpoint', array($endpointDefinition));

			}
		}


	}

	/**
	 * @inheritdoc
	 */
	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();

		$builder->getDefinition($builder->getByType('Nette\Application\IRouter') ?: 'router')
			->addSetup('GameScore\Api\EndpointRouter::insertBefore($service, ?)', array($this->prefix('@router')));

		$builder->getDefinition($builder->getByType('Nette\Application\IPresenterFactory') ?: 'nette.presenterFactory')
			->addSetup('setMapping', array(array(Strings::firstUpper($this->name) => 'GameScore\Api\*Endpoint')));
	}

	/**
	 * @inheritdoc
	 */
	public function getConfig(): ArrayHash
	{
		$privateConfiguration = $this->loadFromFile(__DIR__ . '/config.neon');
		$publicConfiguration = parent::getConfig();

		return ArrayHash::from(array_merge($privateConfiguration, $publicConfiguration));
	}

	/**
	 * Returns the prefixed name parsed from the given namespace.
	 *
	 * @param string $namespace
	 *
	 * @return string
	 */
	private function getPrefixedNameFromNamespace(string $namespace): string
	{
		$matches = Strings::match($namespace, '~([^\\\]+)$~');
		return $this->prefix(Strings::lower($matches[1]));

	}
}
