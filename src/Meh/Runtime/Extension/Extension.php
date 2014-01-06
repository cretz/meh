<?php
namespace Meh\Runtime\Extension;

use Meh\Runtime\PhpRuntime;

abstract class Extension
{
    abstract public function getMetadata();

    public function initializeModule(PhpRuntime $runtime)
    {
    }

    public function initializeRequest(PhpRuntime $runtime)
    {
    }

    public function shutdownModule(PhpRuntime $runtime)
    {
    }

    public function shutdownRequest(PhpRuntime $runtime)
    {
    }
}