<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprGreater
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprGreater(\PHPParser_Node_Expr_Greater $node, Context $ctx)
    {
        return $ctx->phpGt($this->transpile($node->left, $ctx), $this->transpile($node->right, $ctx));
    }
}
