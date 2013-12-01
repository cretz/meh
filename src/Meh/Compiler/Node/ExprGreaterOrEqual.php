<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprGreaterOrEqual
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprGreaterOrEqual(\PHPParser_Node_Expr_GreaterOrEqual $node, Context $ctx)
    {
        return $ctx->phpGte($this->transpile($node->left, $ctx), $this->transpile($node->right, $ctx));
    }
}
