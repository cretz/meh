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
        $funcName = str_replace(['PHPParser_', '_'], '', get_class($node));
        return $this->{$funcName}($node, $ctx);
    }
}