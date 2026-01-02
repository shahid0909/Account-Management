<?php

namespace App\Exceptions;

use Exception;

class MakeException extends Exception
{
    protected $dataId;

    public function __construct(string $message, $dataId = null)
    {
        parent::__construct($message);
        $this->dataId = $dataId;
    }

    public function getDataId()
    {
        return $this->dataId;
    }
}
