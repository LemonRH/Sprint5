<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    protected function setUp(): void
    {
        parent::setUp();

        //desactivar el middleware de autenticacion para todos los tests o.O
        $this->withoutMiddleware();
    }
}
