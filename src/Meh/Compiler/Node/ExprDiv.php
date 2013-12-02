<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprDiv
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprDiv(\PHPParser_Node_Expr_Div $node, Context $ctx)
    {
        return $ctx->phpDiv($this->transpile($node->left, $ctx), $this->transpile($node->right, $ctx));
    }
}
