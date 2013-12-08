<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprNew
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprNew(\PHPParser_Node_Expr_New $node, Context $ctx)
    {
        $params = [];
        // TODO: proper namespacing please
        // NS is first param
        $params[] = $ctx->bld->table();
        // Class name is second param
        if ($node->class instanceof \PHPParser_Node_Name) $params[] = $ctx->phpString($node->class->parts[0]);
        else $params[] = $this->transpile($node->class, $ctx);
        // Now the actual params
        foreach ($node->args as $arg) {
            $params[] = $this->transpile($arg, $ctx);
        }
        return $ctx->bld->call($ctx->bld->varName(['php', 'new']), $params);
    }
}
