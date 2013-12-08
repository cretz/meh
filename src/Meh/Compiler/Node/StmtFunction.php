<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;
use Meh\Lua\Ast\VariableArguments;

trait StmtFunction
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    /**
     * @param \PHPParser_Node_Stmt_Function|\PHPParser_Node_Stmt_ClassMethod $node
     * @param Context $ctx
     * @param bool $prependThisParam
     * @return \Meh\Lua\Ast\AnonymousFunction
     */
    public function transpileFunction(\PHPParser_Node_Stmt $node, Context $ctx, $prependThisParam = false)
    {
        $params = [];
        if ($prependThisParam) $params[] = 'this';
        foreach ($node->params as $param) {
            $params[] = $param->name;
        }
        // Push decl w/ no statements
        $ctx->pushFunc($node->name);
        $stmts = [];
        // Put the local context
        $stmts[] = $ctx->bld->localAssign(
            $ctx->bld->nameList(['ctx']),
            $ctx->bld->call($ctx->bld->varName(['php', 'varCtx']), [])
        );
        // Setup params
        if ($prependThisParam) {
            $stmts[] = $ctx->phpAssign($ctx->bld->varName(['ctx', 'this']), $ctx->bld->varName('this'));
        }
        foreach ($node->params as $param) {
            // TODO: defaults and references
            if ($param->byRef) {
                $stmts[] = $ctx->bld->assign(
                    $ctx->bld->varName(['ctx', $param->name]),
                    $ctx->bld->varName($param->name)
                );
            } else {
                $stmts[] = $ctx->phpAssign(
                    $ctx->bld->varName(['ctx', $param->name]),
                    $ctx->bld->varName($param->name)
                );
            }
        }
        // Transpile statements
        foreach ($node->stmts as $stmt) {
            $stmts[] = $this->transpile($stmt, $ctx);
        }
        // Pop context
        $funcCtx = $ctx->popFunc();
        // Add static table
        if ($funcCtx->needsStaticTbl) {
            array_unshift(
                $stmts,
                $ctx->bld->localAssign(
                    $ctx->bld->nameList(['_staticTbl']),
                    // TODO: namespace
                    $ctx->phpStaticNs([])
                )
            );
        }
        // Add global context
        if ($funcCtx->needsGlobalCtx) {
            array_unshift(
                $stmts,
                $ctx->bld->localAssign($ctx->bld->nameList(['_globalCtx']), $ctx->bld->varName('ctx'))
            );
        }
        // Define func
        return $ctx->bld->anonFunc($params, $funcCtx->needsVarArg, $stmts);
    }

    public function transpileStmtFunction(\PHPParser_Node_Stmt_Function $node, Context $ctx)
    {
        // Define func
        return $ctx->phpDefineFuncs(
            [],
            [$node->name => $this->transpileFunction($node, $ctx)]
        );
    }
}
