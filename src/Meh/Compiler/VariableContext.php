<?php
namespace Meh\Compiler;

class VariableContext
{
    public $tmpVarCounter = 0;

    public function newLocalTmpVarName()
    {
        return '_tmpVar' . $this->tmpVarCounter++;
    }
}
