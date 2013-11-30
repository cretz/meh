<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprConcat
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprConcat(\PHPParser_Node_Expr_Concat $node, Context $ctx)
    {
        return $ctx->phpConcat([$this->transpile($node->left, $ctx), $this->transpile($node->right, $ctx)]);
    }
}
