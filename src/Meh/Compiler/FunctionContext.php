<?php
namespace Meh\Compiler;

use Meh\Lua\Ast\FunctionDeclaration;
use Meh\Lua\Ast\LastStatement;

class FunctionContext
{
    /** @var FunctionDeclaration */
    public $decl;

    /** @var bool */
    public $needsVarArg = false;

    /** @param FunctionDeclaration $decl */
    public function __construct(FunctionDeclaration $decl)
    {
        $this->decl = $decl;
    }
}
