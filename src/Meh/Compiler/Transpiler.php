<?php
namespace Meh\Compiler;

use Meh\Compiler\Node\Arg;
use Meh\Compiler\Node\ExprAssign;
use Meh\Compiler\Node\ExprBitwiseAnd;
use Meh\Compiler\Node\ExprBitwiseOr;
use Meh\Compiler\Node\ExprBooleanOr;
use Meh\Compiler\Node\ExprConcat;
use Meh\Compiler\Node\ExprDiv;
use Meh\Compiler\Node\ExprEqual;
use Meh\Compiler\Node\ExprFuncCall;
use Meh\Compiler\Node\ExprGreater;
use Meh\Compiler\Node\ExprGreaterOrEqual;
use Meh\Compiler\Node\ExprIsset;
use Meh\Compiler\Node\ExprMethodCall;
use Meh\Compiler\Node\ExprMinus;
use Meh\Compiler\Node\ExprMul;
use Meh\Compiler\Node\ExprNew;
use Meh\Compiler\Node\ExprPlus;
use Meh\Compiler\Node\ExprPostDec;
use Meh\Compiler\Node\ExprPostInc;
use Meh\Compiler\Node\ExprPrint;
use Meh\Compiler\Node\ExprPropertyFetch;
use Meh\Compiler\Node\ExprSmaller;
use Meh\Compiler\Node\ExprSmallerOrEqual;
use Meh\Compiler\Node\ExprVariable;
use Meh\Compiler\Node\Name;
use Meh\Compiler\Node\ScalarEncapsed;
use Meh\Compiler\Node\ScalarLNumber;
use Meh\Compiler\Node\ScalarString;
use Meh\Compiler\Node\StmtBreak;
use Meh\Compiler\Node\StmtClass;
use Meh\Compiler\Node\StmtClassMethod;
use Meh\Compiler\Node\StmtDo;
use Meh\Compiler\Node\StmtEcho;
use Meh\Compiler\Node\StmtElse;
use Meh\Compiler\Node\StmtElseIf;
use Meh\Compiler\Node\StmtFor;
use Meh\Compiler\Node\StmtFunction;
use Meh\Compiler\Node\StmtGlobal;
use Meh\Compiler\Node\StmtIf;
use Meh\Compiler\Node\StmtProperty;
use Meh\Compiler\Node\StmtReturn;
use Meh\Compiler\Node\StmtStatic;
use Meh\Compiler\Node\StmtSwitch;
use Meh\Compiler\Node\StmtWhile;

class Transpiler
{
    use Arg,
        ExprAssign,
        ExprBitwiseAnd,
        ExprBitwiseOr,
        ExprBooleanOr,
        ExprConcat,
        ExprDiv,
        ExprEqual,
        ExprFuncCall,
        ExprGreater,
        ExprGreaterOrEqual,
        ExprIsset,
        ExprMethodCall,
        ExprMinus,
        ExprMul,
        ExprNew,
        ExprPlus,
        ExprPostDec,
        ExprPostInc,
        ExprPrint,
        ExprPropertyFetch,
        ExprSmaller,
        ExprSmallerOrEqual,
        ExprVariable,
        File,
        Name,
        ScalarEncapsed,
        ScalarLNumber,
        ScalarString,
        StmtBreak,
        StmtClass,
        StmtClassMethod,
        StmtDo,
        StmtEcho,
        StmtElse,
        StmtElseIf,
        StmtFor,
        StmtFunction,
        StmtGlobal,
        StmtIf,
        StmtProperty,
        StmtReturn,
        StmtStatic,
        StmtSwitch,
        StmtWhile;

    public function transpile(\PHPParser_Node $node, Context $ctx)
    {
        $funcName = 'transpile' . str_replace(['PHPParser_Node_', '_'], '', get_class($node));
        $result = $this->{$funcName}($node, $ctx);
        $ctx->debug('Transpiled ' . get_class($node) . ' to ' . get_class($result));
        return $result;
    }
}