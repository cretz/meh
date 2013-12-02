<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprMinus
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprMinus(\PHPParser_Node_Expr_Minus $node, Context $ctx)
    {
        return $ctx->phpSub($this->transpile($node->left, $ctx), $this->transpile($node->right, $ctx));
    }
}
