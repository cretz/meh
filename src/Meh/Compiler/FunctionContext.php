<?php
namespace Meh\Compiler;

class FunctionContext extends VariableContext
{
    /** @var string */
    public $name;

    /** @var bool */
    public $needsVarArg = false;

    /** @var bool */
    public $needsGlobalCtx = false;

    /** @var bool */
    public $needsStaticTbl = false;

    /** @param string $name */
    public function __construct($name)
    {
        $this->name = $name;
    }
}
