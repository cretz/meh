<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprVariable
{
    public function transpileExprVariable(\PHPParser_Node_Expr_Variable $node, Context $ctx)
    {
        return $ctx->bld->varName(['ctx', $node->name]);
    }
}
