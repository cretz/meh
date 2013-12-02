<?php
namespace Meh\Compiler;

use Meh\Lua\Ast\FunctionDeclaration;
use Meh\Lua\Ast\LastStatement;

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
