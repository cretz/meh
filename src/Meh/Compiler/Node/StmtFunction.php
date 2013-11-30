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
            $params[] = $this->transpile($param, $ctx);
        }
        // Push decl w/ no statements
        $decl = $ctx->bld->funcDeclHead($node->name, $params);
        $ctx->pushFunc($decl);
        $stmts = [];
        // Put the local variable store at the top
        $stmts[] = $ctx->bld->localAssign($ctx->bld->nameList(['locals']), $ctx->bld->table());
        // Tranpile statements
        foreach ($node->stmts as $stmt) {
            $stmts[] = $this->transpile($stmt, $ctx);
        }
        // Now add var arg if necessary
        $funcCtx = $ctx->popFunc();
        if ($funcCtx->needsVarArg) $decl->body->parameters->variableArguments = new VariableArguments();
        // And all locals
        if (!empty($funcCtx->neededLocals)) {
            array_unshift($stmts, $ctx->bld->localAssign($ctx->bld->nameList(array_keys($funcCtx->neededLocals))));
        }
        $decl->body->block = $ctx->bld->block($stmts);
        return $decl;
    }
}
