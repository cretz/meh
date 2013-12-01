<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;
use Meh\Lua\Ast\VariableArguments;

trait StmtReturn
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileStmtReturn(\PHPParser_Node_Stmt_Return $node, Context $ctx)
    {
        return $ctx->bld->returnExpr($node->expr === null ? null : $this->transpile($node->expr, $ctx));
    }
}
