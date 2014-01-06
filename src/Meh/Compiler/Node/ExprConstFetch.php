<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprConstFetch
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprConstFetch(\PHPParser_Node_Expr_ConstFetch $node, Context $ctx)
    {
        return $ctx->bld->call(
            $ctx->bld->varName(['php', 'fetchConst']),
            [
                $ctx->bld->table(),
                // TODO: namespaces
                $ctx->bld->string($node->name->parts[0])
            ]
        );
    }
}
