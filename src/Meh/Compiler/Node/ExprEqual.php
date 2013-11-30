<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprEqual
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprEqual(\PHPParser_Node_Expr_Equal $node, Context $ctx)
    {
        return $ctx->phpEq($this->transpile($node->left, $ctx), $this->transpile($node->right, $ctx));
    }
}
