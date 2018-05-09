<?php
declare(strict_types=1);

namespace GameScore\Api;

use Exception;
use Nette\Application\IPresenter;
use Nette\Application\Request as NetteRequest;
use Nette\Http\IRequest;
use Nette\Http\IResponse;
use RuntimeException;
use Tracy\Debugger;

/**
 * Class BaseEndpoint.
 *
 * @author David Jeřábek <dav.jerabek@gmail.com>
 * @package GameScore\Api
 */
abstract class BaseEndpoint implements IPresenter
{
	/** @var BaseMethod[] */
	private $methods = array();

	/** @var  IRequest $request */
	private $request;

	/** @var  IResponse $response */
	private $response;

	/**
	 * BaseEndpoint constructor.
	 *
	 * @param IRequest $request
	 * @param IResponse $response
	 */
	public function __construct(IRequest $request, IResponse $response)
	{
		$this->request = $request;
		$this->response = $response;
	}

	/**
	 * Returns the unique name of endpoint.
	 * @return string
	 */
	public abstract function getName(): string;

	/**
	 * Returns the path of the endpoint.
	 *
	 * @return string
	 */
	public abstract function getPath(): string;

	/**
	 * @param BaseMethod $method
	 */
	public function addMethod(BaseMethod $method)
	{
		$name = $method->getName();

		if (!$this->hasMethod($name)) {

			$this->methods[$name] = $method;

		} else throw new RuntimeException(sprintf('Method `%s` is already added.', $name));
	}

	/**
	 * Asserts that the IRequest is valid.
	 *
	 * @throws InvalidRequestException
	 */
	private function assertValidRequest()
	{
		$request = $this->request;
		JsonRpc::assetValidAccept($request->getHeader('Accept'));
		JsonRpc::assetValidContentType($request->getHeader('Content-Type'));
	}

	/**
	 * @inheritdoc
	 */
	public function run(NetteRequest $netteRequest)
	{

		$results = array();

		try {

			$this->assertValidRequest();
			$requests = $this->getRequestObjects();

			foreach ($requests as $request) {

				try {

					$method = $request->getMethod();

					if ($this->hasMethod($method)) {

						$result = $this->getMethod($method)
							->access($request->getParams());

						$response = new SuccessResponse($request, $result ?: null);

					} else throw new MethodNotFoundException("Method `{$method}` not found");

				} catch (ApiException $e) {

					$response = new ErrorResponse($request, $e->getCode(), $e->getMessage());

				} catch (Exception $e) {

					$response = new ErrorResponse();

				}

				$results[] = $response;

			}

		} catch (ApiException $e) {

			$results = array(new ErrorResponse(null, $e->getCode(), $e->getMessage()));

		} catch (Exception $e) {

			$results = array(new ErrorResponse());
			Debugger::log($e);

		}


		$this->send($results);

	}

	/**
	 * Returns a value indicating if the method with specified name exist.
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function hasMethod(string $name): bool
	{
		return array_key_exists($name, $this->methods);
	}

	/**
	 * Returns the method find by given name.
	 *
	 * @param string $name
	 *
	 * @return BaseMethod|null
	 */
	public function getMethod(string $name): ?BaseMethod
	{
		return $this->hasMethod($name) ? $this->methods[$name] : null;
	}

	/**
	 * Returns an array of JSON-RPC request object parsed from Request.
	 *
	 * @return Request[]
	 * @throws InvalidRequestException
	 */
	private function getRequestObjects(): array
	{
		switch ($this->request->getMethod()) {
			case IRequest::GET:
				return array(Request::fromArray($this->request->getQuery()));
				break;
			case IRequest::POST:
				return Request::fromJson($this->request->getRawBody());
				break;
			default:
				throw new InvalidRequestException('Unaccepted request method.');

		}
	}

	/**
	 * Sends response to client.
	 *
	 * @param Response[] $results
	 */
	protected function send(array $results = array())
	{
		$this->response->setContentType(JsonRpc::JSON_RPC);
		echo $this->prepareBatchResponse($results);
		exit;
	}

	/**
	 * Returns a string representation of response.
	 *
	 * @param array $results
	 *
	 * @return string
	 */
	private function prepareBatchResponse(array $results = array()): string
	{
		if (count($results) === 1) {
			return reset($results)->getJson();
		}

		$result = implode(',', array_map(function (Response $response) {

			return $response->getJson();

		}, $results));

		return "[{$result}]";
	}
}
