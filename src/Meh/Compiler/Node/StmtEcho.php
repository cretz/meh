<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait StmtEcho
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileStmtEcho(\PHPParser_Node_Stmt_Echo $node, Context $ctx)
    {
        // Build expressions
        $exprs = [];
        foreach ($node->exprs as $expr) {
            $exprs[] = $this->transpile($expr, $ctx);
        }
        // Create echo call
        return $ctx->phpCall(['echo'], $exprs);
    }
}
