<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprPropertyFetch
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprPropertyFetch(\PHPParser_Node_Expr_PropertyFetch $node, Context $ctx)
    {
        return $ctx->bld->call(
            $ctx->bld->varName(['php', 'fetchProperty']),
            [
                $this->transpile($node->var, $ctx),
                is_string($node->name) ? $ctx->phpString($node->name) : $this->transpile($node->name, $ctx)
            ]
        );
    }
}
