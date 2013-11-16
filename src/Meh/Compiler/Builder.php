<?php
namespace Meh\Compiler;

use Meh\Lua\Ast\ArgumentList;
use Meh\Lua\Ast\Assignment;
use Meh\Lua\Ast\BinaryExpression;
use Meh\Lua\Ast\BinaryOperator;
use Meh\Lua\Ast\Chunk;
use Meh\Lua\Ast\Expression;
use Meh\Lua\Ast\ExpressionList;
use Meh\Lua\Ast\FunctionCall;
use Meh\Lua\Ast\IfStatement;
use Meh\Lua\Ast\KeywordLiteral;
use Meh\Lua\Ast\LastStatement;
use Meh\Lua\Ast\Name;
use Meh\Lua\Ast\Number;
use Meh\Lua\Ast\ParenthesizedExpressionList;
use Meh\Lua\Ast\PrefixExpression;
use Meh\Lua\Ast\Statement;
use Meh\Lua\Ast\String;
use Meh\Lua\Ast\TableConstructor;
use Meh\Lua\Ast\Variable;
use Meh\Lua\Ast\VariableList;
use Meh\Lua\Ast\VariableName;
use Meh\MehException;

class Builder
{
    /**
     * @param ArgumentList|Expression[] $args
     * @return ArgumentList
     */
    public function args($args)
    {
        if (is_array($args)) return new ParenthesizedExpressionList(new ExpressionList($args));
        return $args;
    }

    /**
     * @param Variable|Variable[]|VariableList $variables
     * @param Expression|Expression[]|ExpressionList $expressions
     * @return Assignment
     */
    public function assign($variables, $expressions)
    {
        return new Assignment($this->varList($variables), $this->exprList($expressions));
    }

    /**
     * @param (Statement|LastStatement)[] $stmts
     * @return Chunk
     */
    public function block(array $stmts)
    {
        $lastStatement = null;
        if ($stmts[count($stmts) - 1] instanceof LastStatement) $lastStatement = array_pop($stmts);
        return new Chunk($stmts, $lastStatement);
    }

    /**
     * @param PrefixExpression $expr
     * @param ArgumentList|Expression[] $args
     * @return FunctionCall
     */
    public function call(PrefixExpression $expr, $args)
    {
        return new FunctionCall($expr, $this->args($args));
    }

    /**
     * @param Expression $left
     * @param Expression $right
     * @return BinaryExpression
     */
    public function eq(Expression $left, Expression $right)
    {
        return new BinaryExpression($left, new BinaryOperator('='), $right);
    }

    /**
     * @param Expression|Expression[]|ExpressionList $expressions
     * @return ExpressionList
     */
    public function exprList($expressions)
    {
        if (is_array($expressions)) return new ExpressionList($expressions);
        if ($expressions instanceof Expression) return new ExpressionList([$expressions]);
        return $expressions;
    }

    /**
     * @param Expression $expr
     * @param (Statement|LastStatement)[] $stmts
     * @return IfStatement
     */
    public function ifStmt(Expression $expr, array $stmts)
    {
        return new IfStatement($expr, $this->block($stmts));
    }

    /** @return KeywordLiteral */
    public function nil()
    {
        return new KeywordLiteral(null);
    }

    /**
     * @param float|int $val
     * @return Number
     */
    public function number($val)
    {
        return new Number($val);
    }

    /**
     * @param string $val
     * @return String
     */
    public function string($val)
    {
        return new String($val);
    }

    /** @return TableConstructor */
    public function table()
    {
        return new TableConstructor();
    }

    /**
     * @param Variable|Variable[]|VariableList $variables
     * @return VariableList
     */
    public function varList($variables)
    {
        if (is_array($variables)) return new VariableList($variables);
        if ($variables instanceof Variable) return new VariableList([$variables]);
        return $variables;
    }

    /**
     * Dotted var
     *
     * @param string|Variable|(string|Variable)[] $pieces
     * @return Variable
     */
    public function varName($pieces)
    {
        // Simple string is simple name
        if (is_string($pieces)) return new Name($pieces);
        // Must be an array
        if (!is_array($pieces)) throw new MehException('Unexpected type of variable name');
        // Go through each, adding the last to the one before
        $var = null;
        foreach ($pieces as $piece) {
            if ($var === null) $var = $this->varName($piece);
            else $var = new VariableName($var, $this->varName($piece));
        }
        return $var;
    }
}
