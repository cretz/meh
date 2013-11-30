<?php
namespace Meh\Compiler;

use Meh\Lua\Ast\FunctionDeclaration;
use Meh\Lua\Ast\LastStatement;

class FunctionContext extends VariableContext
{
    /** @var FunctionDeclaration */
    public $decl;

    /** @var bool */
    public $needsVarArg = false;

    /** @var bool */
    public $needsGlobalCtx = false;

    /** @param FunctionDeclaration $decl */
    public function __construct(FunctionDeclaration $decl)
    {
        $this->decl = $decl;
    }
}
