<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    protected function setUp(): void
    {
        parent::setUp();

        // Desactivar el middleware de autenticación para todos los tests
        $this->withoutMiddleware();
    }
}
