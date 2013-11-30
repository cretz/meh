<?php
namespace Meh\Compiler;

use Meh\Compiler\Node\ExprBooleanOr;
use Meh\Compiler\Node\ExprConcat;
use Meh\Compiler\Node\ExprFuncCall;
use Meh\Compiler\Node\ExprIsset;
use Meh\Compiler\Node\ExprPostDec;
use Meh\Compiler\Node\ExprPostInc;
use Meh\Compiler\Node\ExprVariable;
use Meh\Compiler\Node\Name;
use Meh\Compiler\Node\ScalarLNumber;
use Meh\Compiler\Node\ScalarString;
use Meh\Compiler\Node\StmtEcho;
use Meh\Compiler\Node\StmtFunction;
use Meh\Compiler\Node\StmtIf;
use Meh\Compiler\Node\StmtStatic;

class Transpiler
{
    use ExprBooleanOr,
        ExprConcat,
        ExprFuncCall,
        ExprIsset,
        ExprPostDec,
        ExprPostInc,
        ExprVariable,
        Name,
        ScalarLNumber,
        ScalarString,
        StmtEcho,
        StmtFunction,
        StmtIf,
        StmtStatic;

    public function transpile(\PHPParser_Node $node, Context $ctx)
    {
        $funcName = 'transpile' . str_replace(['PHPParser_Node_', '_'], '', get_class($node));
        $result = $this->{$funcName}($node, $ctx);
        $ctx->debug('Transpiled ' . get_class($node) . ' to ' . get_class($result));
        return $result;
    }

    public function transpileAll(array $nodes, Context $ctx)
    {
        $ret = array();
        foreach ($nodes as $node) {
            $ret[] = $this->transpile($node, $ctx);
        }
        return $ret;
    }
}