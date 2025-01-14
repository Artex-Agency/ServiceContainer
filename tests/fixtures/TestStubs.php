<?php

namespace Tests\Fixtures;

class SomeDependency {}

class DependentClass
{
    public function __construct(public SomeDependency $dependency) {}
}

class ClassWithOptionalDependency
{
    public function __construct(public ?SomeDependency $optionalDependency = null) {}
}