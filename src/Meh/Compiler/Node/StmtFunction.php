<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;
use Meh\Lua\Ast\VariableArguments;

trait StmtFunction
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileStmtFunction(\PHPParser_Node_Stmt_Function $node, Context $ctx)
    {
        $params = [];
        foreach ($node->params as $param) {
            $params[] = $param->name;
        }
        // Push decl w/ no statements
//        $decl = $ctx->bld->funcDeclHead(['php', $node->name], $params);
        $ctx->pushFunc($node->name);
        $stmts = [];
        // Put the local context
        $stmts[] = $ctx->bld->localAssign(
            $ctx->bld->nameList(['ctx']),
            $ctx->bld->call($ctx->bld->varName(['php', 'varCtx']), [])
        );
        // Setup params
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
        // Tranpile statements
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
        return $ctx->phpDefineFuncs(
            [],
            [$node->name => $ctx->bld->anonFunc($params, $funcCtx->needsVarArg, $stmts)]
        );
    }
}
