<?php

namespace App\Support;

use LogicException;

class Failure extends Result
{
    private $error;

    public function __construct($error)
    {
        $this->error = $error;
    }

    public function bind(callable $fn): Result
    {
        return $this;
    }

    public function unwrap()
    {
        throw new LogicException('Cannot unwrap a value from a Failure result.');
    }

    public function unwrapErr()
    {
        return $this->error;
    }
}
