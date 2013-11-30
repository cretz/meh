<?php
namespace Meh\Compiler;

class VariableContext
{
    /** @var bool[] Keyed by variable name */
    public $neededLocals = [];
}
