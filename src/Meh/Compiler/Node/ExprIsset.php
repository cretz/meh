<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprIsset
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprIsset(\PHPParser_Node_Expr_Isset $node, Context $ctx)
    {
        $vars = [];
        foreach ($node->vars as $var) {
            $vars[] = $this->transpile($var, $ctx);
        }
        return $ctx->phpCall(['isset'], $vars);
    }
}
