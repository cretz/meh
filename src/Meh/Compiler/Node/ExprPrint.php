<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprPrint
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprPrint(\PHPParser_Node_Expr_Print $node, Context $ctx)
    {
        return $ctx->phpCall(['print'], [$this->transpile($node->expr, $ctx)]);
    }
}
