<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprMul
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprMul(\PHPParser_Node_Expr_Mul $node, Context $ctx)
    {
        return $ctx->phpMult($this->transpile($node->left, $ctx), $this->transpile($node->right, $ctx));
    }
}
