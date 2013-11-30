<?php
namespace Meh\Compiler;

use Meh\Lua\Ast\ArgumentList;
use Meh\Lua\Ast\Assignment;
use Meh\Lua\Ast\BinaryExpression;
use Meh\Lua\Ast\BinaryOperator;
use Meh\Lua\Ast\Block;
use Meh\Lua\Ast\Chunk;
use Meh\Lua\Ast\ElseIfExpression;
use Meh\Lua\Ast\Expression;
use Meh\Lua\Ast\ExpressionList;
use Meh\Lua\Ast\FunctionBody;
use Meh\Lua\Ast\FunctionCall;
use Meh\Lua\Ast\FunctionDeclaration;
use Meh\Lua\Ast\FunctionName;
use Meh\Lua\Ast\IfStatement;
use Meh\Lua\Ast\KeywordLiteral;
use Meh\Lua\Ast\LastStatement;
use Meh\Lua\Ast\LocalAssignment;
use Meh\Lua\Ast\Name;
use Meh\Lua\Ast\NameList;
use Meh\Lua\Ast\Number;
use Meh\Lua\Ast\ParameterList;
use Meh\Lua\Ast\ParenthesizedExpressionList;
use Meh\Lua\Ast\PrefixExpression;
use Meh\Lua\Ast\ReturnStatement;
use Meh\Lua\Ast\Statement;
use Meh\Lua\Ast\StatementList;
use Meh\Lua\Ast\String;
use Meh\Lua\Ast\TableConstructor;
use Meh\Lua\Ast\Variable;
use Meh\Lua\Ast\VariableArguments;
use Meh\Lua\Ast\VariableList;
use Meh\Lua\Ast\VariableName;
use Meh\Lua\Ast\WhileStatement;
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
     * @return Block
     */
    public function block(array $stmts)
    {
        $returnStatement = null;
        if ($stmts[count($stmts) - 1] instanceof ReturnStatement) $returnStatement = array_pop($stmts);
        return new Block($stmts, $returnStatement);
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
    public function concat(Expression $left, Expression $right)
    {
        return new BinaryExpression($left, new BinaryOperator('..'), $right);
    }

    /**
     * @param Expression $expr
     * @param Block $block
     * @return ElseIfExpression
     */
    public function elseIfExpr(Expression $expr, Block $block)
    {
        return new ElseIfExpression($expr, $block);
    }

    /**
     * @param Expression $left
     * @param Expression $right
     * @return BinaryExpression
     */
    public function eq(Expression $left, Expression $right)
    {
        return new BinaryExpression($left, new BinaryOperator('=='), $right);
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
     * No statements, no var arg setting. Caller expected to set later
     *
     * @param string|string[] $name
     * @param string[] $params
     * @return FunctionDeclaration
     */
    public function funcDeclHead($name, array $params)
    {
        $funcName = null;
        if (is_string($name)) $funcName = new FunctionName([$this->varName($name)]);
        elseif (is_array($name)) {
            $names = [];
            foreach ($name as $piece) {
                $names[] = $this->varName($piece);
            }
            $funcName = new FunctionName($names);
        }
        return new FunctionDeclaration(
            $funcName,
            new FunctionBody($this->params($params, false), new Block([]))
        );
    }

    /**
     * @param Expression $expr
     * @param (Statement|LastStatement)[] $stmts
     * @param ElseIfExpression[] $elseIfs
     * @param Block|null $else
     * @return IfStatement
     */
    public function ifStmt(Expression $expr, array $stmts, array $elseIfs = [], Block $else = null)
    {
        return new IfStatement($expr, $this->block($stmts), $elseIfs, $else);
    }

    /**
     * @param NameList $names
     * @param Expression|Expression[]|ExpressionList $expressions
     * @return LocalAssignment
     */
    public function localAssign(NameList $names, $expressions = null)
    {
        return new LocalAssignment(
            $names,
            $expressions === null ? null : $this->exprList($expressions)
        );
    }

    /**
     * @param string[] $names
     * @return NameList
     */
    public function nameList(array $names)
    {
        $nameArr = array();
        foreach ($names as $name) {
            $nameArr[] = $this->varName($name);
        }
        return new NameList($nameArr);
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
     * @param Expression $left
     * @param Expression $right
     * @return BinaryExpression
     */
    public function orExpr(Expression $left, Expression $right)
    {
        return new BinaryExpression($left, new BinaryOperator('or'), $right);
    }

    /**
     * @param string[] $params
     * @param bool $varArg
     * @return ParameterList
     */
    public function params(array $params, $varArg)
    {
        return new ParameterList($this->nameList($params), $varArg ? new VariableArguments() : null);
    }

    /**
     * @param Statement[] $stmts
     * @return StatementList
     */
    public function stmts(array $stmts)
    {
        return new StatementList($stmts);
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
        // If it's already a variable name, good
        if ($pieces instanceof Variable) return $pieces;
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

    /**
     * @param Expression $cond
     * @param (Statement|LastStatement)[] $stmts
     * @return WhileStatement
     */
    public function whileStmt(Expression $cond, array $stmts)
    {
        return new WhileStatement($cond, $this->block($stmts));
    }
}
