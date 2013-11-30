<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprFuncCall
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprFuncCall(\PHPParser_Node_Expr_FuncCall $node, Context $ctx)
    {
        $args = [];
        foreach ($node->args as $arg) {
            $args[] = $this->transpile($arg, $ctx);
        }
        return $ctx->bld->call($this->transpile($node->name, $ctx), $args);
    }
}
