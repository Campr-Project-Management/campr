<?php

namespace AppBundle\Tests;

trait CallMethodTrait
{
    /**
     * @param mixed  $object
     * @param string $method
     * @param array  $params
     *
     * @return mixed
     */
    private function callMethod($object, $method, array $params = [])
    {
        $methodCall = \Closure::bind(
            function () use ($method) {
                return call_user_func_array([$this, $method], func_get_args());
            },
            $object,
            $object
        );

        return call_user_func_array($methodCall, $params);
    }
}
