<?php

namespace Aviator\Search\Exceptions;

use Aviator\Search\Interfaces\Searchable;
use Exception;

class ModelNotSearchable extends Exception
{
    /**
     * @param $caller
     * @param $callee
     */
    public static function assertSearchable ($caller, $callee)
    {
        if (! static::is($callee)(Searchable::class)) {
            static::fail(
                'Model %s does not implement Searchable. Called in %s.',
                get_class($callee),
                $caller
            );
        }
    }

    /**
     * @param $object
     * @return \Closure
     */
    public static function is ($object)
    {
        return function ($identity) use ($object) {
            return $object instanceof $identity;
        };
    }

    /**
     * @param $message
     * @param array ...$args
     */
    public static function fail ($message, ...$args)
    {
        throw new static(
            sprintf($message, ...$args)
        );
    }
}
