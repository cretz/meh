<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;
use Meh\MehException;

trait StmtBreak
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileStmtBreak(\PHPParser_Node_Stmt_Break $node, Context $ctx)
    {
        $breakDepth = 1;
        if ($node->num !== null) {
            // I only understand LNumber's
            if (!($node->num instanceof \PHPParser_Node_Scalar_LNumber)) {
                throw new MehException('Break num must be LNumber');
            }
            $breakDepth = $node->num->value;
        }
        // Grab the loop context
        $loop = $ctx->peekLoop($breakDepth);
        // No loop, we should die, but for now we can just throw
        if ($loop === null) throw new MehException('Cannot find loop to break out of');
        // Send back goto
        return $ctx->bld->gotoExpr($loop->breakLabel($ctx->peekVarCtx()));
    }
}
