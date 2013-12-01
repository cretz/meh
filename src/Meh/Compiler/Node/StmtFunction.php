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
        $decl = $ctx->bld->funcDeclHead(['php', $node->name], $params);
        $ctx->pushFunc($decl);
        $stmts = [];
        // Put the local context
        $stmts[] = $ctx->bld->localAssign(
            $ctx->bld->nameList(['ctx']),
            $ctx->bld->call($ctx->bld->varName(['php', 'VarCtx', '__new']), [])
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
        // Now add var arg if necessary
        $funcCtx = $ctx->popFunc();
        if ($funcCtx->needsVarArg) $decl->body->parameters->variableArguments = new VariableArguments();
        // And global context
        if ($funcCtx->needsGlobalCtx) {
            array_unshift(
                $stmts,
                $ctx->bld->localAssign($ctx->bld->nameList(['_globalCtx']), $ctx->bld->varName('ctx'))
            );
        }
        $decl->body->block = $ctx->bld->block($stmts);
        return $decl;
    }
}
