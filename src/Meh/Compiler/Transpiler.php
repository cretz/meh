<?php
namespace Meh\Compiler;

use Meh\Compiler\Node\ExprAssign;
use Meh\Compiler\Node\ExprBooleanOr;
use Meh\Compiler\Node\ExprConcat;
use Meh\Compiler\Node\ExprFuncCall;
use Meh\Compiler\Node\ExprGreater;
use Meh\Compiler\Node\ExprIsset;
use Meh\Compiler\Node\ExprPostDec;
use Meh\Compiler\Node\ExprPostInc;
use Meh\Compiler\Node\ExprSmaller;
use Meh\Compiler\Node\ExprVariable;
use Meh\Compiler\Node\Name;
use Meh\Compiler\Node\ScalarLNumber;
use Meh\Compiler\Node\ScalarString;
use Meh\Compiler\Node\StmtEcho;
use Meh\Compiler\Node\StmtFunction;
use Meh\Compiler\Node\StmtIf;
use Meh\Compiler\Node\StmtStatic;
use Meh\Compiler\Node\StmtWhile;

class Transpiler
{
    use ExprAssign,
        ExprBooleanOr,
        ExprConcat,
        ExprFuncCall,
        ExprGreater,
        ExprIsset,
        ExprPostDec,
        ExprPostInc,
        ExprSmaller,
        ExprVariable,
        File,
        Name,
        ScalarLNumber,
        ScalarString,
        StmtEcho,
        StmtFunction,
        StmtIf,
        StmtStatic,
        StmtWhile;

    public function transpile(\PHPParser_Node $node, Context $ctx)
    {
        $funcName = 'transpile' . str_replace(['PHPParser_Node_', '_'], '', get_class($node));
        $result = $this->{$funcName}($node, $ctx);
        $ctx->debug('Transpiled ' . get_class($node) . ' to ' . get_class($result));
        return $result;
    }
}