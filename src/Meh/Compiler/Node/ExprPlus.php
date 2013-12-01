<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprPlus
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprPlus(\PHPParser_Node_Expr_Plus $node, Context $ctx)
    {
        return $ctx->phpAdd($this->transpile($node->left, $ctx), $this->transpile($node->right, $ctx));
    }
}
