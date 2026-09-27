<?php

namespace App\Support;

use LogicException;

abstract class Result
{
    public static function success($value): Result
    {
        return new Success($value);
    }

    public static function failure($error): Result
    {
        return new Failure($error);
    }

    abstract public function bind(callable $fn): Result;
    abstract public function unwrap();
    abstract public function unwrapErr();

    public function isSuccess(): Bool 
    {
        return $this instanceof Success;
    }

    public function isFailure(): bool
    {
        return $this instanceof Failure;
    }

    public function unwrapOr($default) {
        return $this->isSuccess() ? $this->unwrap() : $default;
    }
}
