<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprBitwiseAnd
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprBitwiseAnd(\PHPParser_Node_Expr_BitwiseAnd $node, Context $ctx)
    {
        return $ctx->phpBitAnd($this->transpile($node->left, $ctx), $this->transpile($node->right, $ctx));
    }
}
