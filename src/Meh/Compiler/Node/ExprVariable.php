<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprVariable
{
    public function transpileExprVariable(\PHPParser_Node_Expr_Variable $node, Context $ctx)
    {
        // Put in func locals
        if ($ctx->peekFunc() !== null) $ctx->peekFunc()->neededLocals[$node->name] = true;
        return $ctx->bld->varName($node->name);
    }
}
