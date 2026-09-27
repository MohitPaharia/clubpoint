<?php

namespace App\Support;

use LogicException;

class Success extends Result
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function bind(callable $fn): Result
    {
        try {
            $result = $fn($this->value);
            return $result instanceof Result ? $result : Result::success($result);
        }

        catch (\Throwable $e) {
            return Result::failure($e->getMessage());
        }
    }

    public function unwrap()
    {
        return $this->value;
    }

    public function unwrapErr()
    {
        throw new LogicException('Cannot unwrap error on a Success result.');
    }
}
