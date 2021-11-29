<?php

namespace DummyNamespace;

use DummyModelClassNamespace;
use Illuminate\Http\Request;

class DummyRepositoryClass
{
    private $DummyModelVariable;

    public function __construct(DummyModelClass $DummyModelVariable)
    {
        $this->DummyModelVariable = $DummyModelVariable;
    }
}
