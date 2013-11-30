<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprSmaller
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprSmaller(\PHPParser_Node_Expr_Smaller $node, Context $ctx)
    {
        return $ctx->phpLt($this->transpile($node->left, $ctx), $this->transpile($node->right, $ctx));
    }
}
