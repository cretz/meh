<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprBitwiseOr
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprBitwiseOr(\PHPParser_Node_Expr_BitwiseOr $node, Context $ctx)
    {
        return $ctx->phpBitOr($this->transpile($node->left, $ctx), $this->transpile($node->right, $ctx));
    }
}
