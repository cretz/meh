<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait StmtInlineHTML
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileStmtInlineHTML(\PHPParser_Node_Stmt_InlineHTML $node, Context $ctx)
    {
        // Just an echo
        return $ctx->phpCall(['echo'], [$ctx->phpString($node->value)]);
    }
}
