<?php
declare(strict_types=1);

namespace GameScore\Api;

/**
 * Class BaseMethod.
 *
 * @author David Jeřábek <dav.jerabek@gmail.com>
 * @package GameScore\Api
 */
abstract class BaseMethod
{
    /**
     * @inheritdoc
     */
    protected abstract function assertParams(array $params);

    /**
     * Process the access to the method.
     *
     * @return mixed
     */
    protected abstract function process();

    /**
     * Process the action before access to the method.
     */
    protected function beforeProcess()
    {
		//run own code before process
    }

    /**
     * Process the action after access to the method.
     */
    protected function afterProcess()
    {
		//run own code after process
    }

    /**
     * Returns the unique name of method.
     *
     * @return string
     */
    public abstract function getName(): string;

    /**
     * Access to the method.
     *
     * @param array $params
     * @return mixed
     */
    public function access(array $params = array())
    {
        $this->assertParams($params);
        $this->beforeProcess();
        $result = $this->process();
        $this->afterProcess();

        return $result;
    }
}
