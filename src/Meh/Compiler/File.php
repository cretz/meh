<?php
namespace Meh\Compiler;

use Meh\Lua\Ast\StatementList;

trait File
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    /**
     * @param \PHPParser_Node_Stmt[] $nodes
     * @param Context $ctx
     * @return StatementList
     */
    public function transpileFile(array $nodes, Context $ctx)
    {
        // Push global variable context
        $ctx->pushVarCtx();
        $stmts = [];
        // Require php module
        $stmts[] = $ctx->bld->localAssign(
            $ctx->bld->nameList(['php']),
            $ctx->bld->call($ctx->bld->varName('require'), [$ctx->bld->string('php')])
        );
        // Add main context
        $stmts[] = $ctx->bld->localAssign(
            $ctx->bld->nameList(['ctx']),
            $ctx->bld->call($ctx->bld->varName(['php', 'VarCtx', '__new']), [])
        );
        // Transpile all nodes
        foreach ($nodes as $node) {
            $stmts[] = $this->transpile($node, $ctx);
        }
        // Pop var context
        $ctx->popVarCtx();
        return $ctx->bld->stmts($stmts);
    }
}
