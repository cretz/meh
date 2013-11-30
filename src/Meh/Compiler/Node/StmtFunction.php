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
        // Tranpile statements
        $stmts = [];
        foreach ($node->stmts as $stmt) {
            $stmts[] = $this->transpile($stmt, $ctx);
        }
        // Now add statements and var arg if necessary
        if ($ctx->popFunc()->needsVarArg) $decl->body->parameters->variableArguments = new VariableArguments();
        $decl->body->block = $ctx->bld->block($stmts);
        return $decl;
    }
}
