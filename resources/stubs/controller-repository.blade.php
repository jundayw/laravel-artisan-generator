<?php

namespace DummyNamespace;

use DummyRootNamespaceHttp\Controllers\Controller;
use DummyRepositoryClassNamespace;
use Illuminate\Http\Request;

/**
 * @module DummyLabel
 * @controller DummyLabel管理
 */
class DummyControllerClass extends Controller
{
    private $repository;

    public function __construct(DummyRepositoryClass $repository)
    {
        $this->repository = $repository;
    }
}
