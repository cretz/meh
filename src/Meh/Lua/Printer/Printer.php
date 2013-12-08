<?php
namespace Meh\Lua\Printer;

use Meh\Lua\Ast\AnonymousFunction;
use Meh\Lua\Ast\ArgumentList;
use Meh\Lua\Ast\Assignment;
use Meh\Lua\Ast\BinaryExpression;
use Meh\Lua\Ast\BinaryOperator;
use Meh\Lua\Ast\Block;
use Meh\Lua\Ast\BracketedField;
use Meh\Lua\Ast\BreakStatement;
use Meh\Lua\Ast\Chunk;
use Meh\Lua\Ast\DoStatement;
use Meh\Lua\Ast\ElseIfExpression;
use Meh\Lua\Ast\EmptyStatement;
use Meh\Lua\Ast\Expression;
use Meh\Lua\Ast\ExpressionList;
use Meh\Lua\Ast\Field;
use Meh\Lua\Ast\FieldList;
use Meh\Lua\Ast\ForInStatement;
use Meh\Lua\Ast\ForStatement;
use Meh\Lua\Ast\FunctionBody;
use Meh\Lua\Ast\FunctionCall;
use Meh\Lua\Ast\FunctionDeclaration;
use Meh\Lua\Ast\FunctionName;
use Meh\Lua\Ast\GotoStatement;
use Meh\Lua\Ast\IfStatement;
use Meh\Lua\Ast\KeywordLiteral;
use Meh\Lua\Ast\Label;
use Meh\Lua\Ast\LocalAssignment;
use Meh\Lua\Ast\LocalFunctionDeclaration;
use Meh\Lua\Ast\Name;
use Meh\Lua\Ast\NamedField;
use Meh\Lua\Ast\NameList;
use Meh\Lua\Ast\Node;
use Meh\Lua\Ast\Number;
use Meh\Lua\Ast\ParameterList;
use Meh\Lua\Ast\ParenthesizedExpression;
use Meh\Lua\Ast\ParenthesizedExpressionList;
use Meh\Lua\Ast\PrefixExpression;
use Meh\Lua\Ast\RepeatStatement;
use Meh\Lua\Ast\ReturnStatement;
use Meh\Lua\Ast\Statement;
use Meh\Lua\Ast\StatementList;
use Meh\Lua\Ast\String;
use Meh\Lua\Ast\TableConstructor;
use Meh\Lua\Ast\UnaryExpression;
use Meh\Lua\Ast\UnaryOperator;
use Meh\Lua\Ast\Variable;
use Meh\Lua\Ast\VariableArguments;
use Meh\Lua\Ast\VariableExpression;
use Meh\Lua\Ast\VariableList;
use Meh\Lua\Ast\VariableName;
use Meh\Lua\Ast\WhileStatement;
use Meh\MehException;

class Printer
{
    public function printAnonymousFunction(AnonymousFunction $ast, Context $ctx)
    {
        $ctx->append('function ');
        $this->printFunctionBody($ast->body, $ctx);
    }

    public function printArgumentList(ArgumentList $ast, Context $ctx)
    {
        if ($ast instanceof ParenthesizedExpressionList) $this->printParenthesizedExpressionList($ast, $ctx);
        elseif ($ast instanceof TableConstructor) $this->printTableConstructor($ast, $ctx);
        elseif ($ast instanceof String) $this->printString($ast, $ctx);
    }

    public function printAssignment(Assignment $ast, Context $ctx)
    {
        $ctx->newLine();
        $this->printVariableList($ast->variables, $ctx);
        $ctx->append(' = ');
        $this->printExpressionList($ast->expressions, $ctx);
    }

    public function printBinaryExpression(BinaryExpression $ast, Context $ctx)
    {
        $this->printExpression($ast->left, $ctx);
        $ctx->append(' ');
        $this->printBinaryOperator($ast->operator, $ctx);
        $ctx->append(' ');
        $this->printExpression($ast->right, $ctx);
    }

    public function printBinaryOperator(BinaryOperator $ast, Context $ctx)
    {
        $ctx->append($ast->operator);
    }

    public function printBlock(Block $ast, Context $ctx)
    {
        $ctx->indent();
        foreach ($ast->statements as $stmt) {
            $this->printStatement($stmt, $ctx);
        }
        if ($ast->returnStatement !== null) $this->printReturnStatement($ast->returnStatement, $ctx);
        $ctx->dedent();
    }

    public function printBracketedField(BracketedField $ast, Context $ctx)
    {
        $ctx->append('[');
        $ast->printExpression($ast->left, $ctx);
        $ctx->append('] = ');
        $this->printExpression($ast->right, $ctx);
        if ($ast->separator !== null) $ctx->append($ast->separator);
    }

    public function printBreakStatement(BreakStatement $ast, Context $ctx)
    {
        $ctx->newLine('break');
    }

    public function printChunk(Chunk $ast, Context $ctx)
    {
        if ($ast instanceof Block) $this->printBlock($ast, $ctx);
        else throw new MehException('Unrecognized chunk: ' . get_class($ast));
    }

    public function printDoStatement(DoStatement $ast, Context $ctx)
    {
        $ctx->newLine('do');
        $this->printBlock($ast->block, $ctx);
        $ctx->newLine('end');
    }

    public function printElseIfExpression(ElseIfExpression $ast, Context $ctx)
    {
        $ctx->append('elseif ');
        $this->printExpression($ast->expression, $ctx);
        $ctx->append(' then');
        $this->printBlock($ast->block, $ctx);
    }

    public function printEmptyStatement(EmptyStatement $ast, Context $ctx)
    {
        $ctx->newLine(';');
    }

    public function printExpression(Expression $ast, Context $ctx)
    {
        if ($ast instanceof KeywordLiteral) $this->printKeywordLiteral($ast, $ctx);
        elseif ($ast instanceof Number) $this->printNumber($ast, $ctx);
        elseif ($ast instanceof String) $this->printString($ast, $ctx);
        elseif ($ast instanceof VariableArguments) $this->printVariableArguments($ast, $ctx);
        elseif ($ast instanceof AnonymousFunction) $this->printAnonymousFunction($ast, $ctx);
        elseif ($ast instanceof PrefixExpression) $this->printPrefixExpression($ast, $ctx);
        elseif ($ast instanceof TableConstructor) $this->printTableConstructor($ast, $ctx);
        elseif ($ast instanceof BinaryExpression) $this->printBinaryExpression($ast, $ctx);
        elseif ($ast instanceof UnaryExpression) $this->printUnaryExpression($ast, $ctx);
        else throw new MehException('Unrecognized expression: ' . get_class($ast));
    }

    public function printExpressionList(ExpressionList $ast, Context $ctx)
    {
        foreach ($ast->expressions as $index => $expression) {
            if ($index > 0) $ctx->append(', ');
            $this->printExpression($expression, $ctx);
        }
    }

    public function printField(Field $ast, Context $ctx)
    {
        if ($ast instanceof BracketedField) $this->printBracketedField($ast, $ctx);
        elseif ($ast instanceof NamedField) $this->printNamedField($ast, $ctx);
        else throw new MehException('Unrecognized field: ' . get_class($ast));
    }

    public function printFieldList(FieldList $ast, Context $ctx)
    {
        // Two or more fields means multiline
        $multiline = count($ast->fields) > 1;
        if ($multiline) $ctx->indent();
        foreach ($ast->fields as $index => $field) {
            if ($multiline) $ctx->newLine();
            $this->printField($field, $ctx);
            // If we're not the last but have no separator, use a comma
            if ($field->getSeparator() === null && $index !== count($ast->fields) - 1) {
                $ctx->append(',');
            }
            // Non multiline deserves a space
            if (!$multiline) $ctx->append(' ');
        }
        if ($multiline) $ctx->dedent()->newLine();
    }

    public function printForStatement(ForStatement $ast, Context $ctx)
    {
        $ctx->newLine('for ');
        $this->printName($ast->name, $ctx);
        $ctx->append(' = ');
        $this->printExpression($ast->initializer, $ctx);
        $ctx->append(', ');
        $this->printExpression($ast->conditional, $ctx);
        if ($ast->increment !== null) {
            $ctx->append(', ');
            $this->printExpression($ast->increment, $ctx);
        }
        $ctx->append(' do');
        $this->printBlock($ast->block, $ctx);
        $ctx->newLine('end');
    }

    public function printForInStatement(ForInStatement $ast, Context $ctx)
    {
        $ctx->newLine('for ');
        $this->printNameList($ast->names, $ctx);
        $ctx->append(' in ');
        $this->printExpressionList($ast->expressions, $ctx);
        $ctx->append(' do');
        $this->printBlock($ast->block, $ctx);
        $ctx->newLine('end');
    }

    public function printFunctionBody(FunctionBody $ast, Context $ctx)
    {
        $ctx->append('(');
        $this->printParameterList($ast->parameters, $ctx);
        $ctx->append(') ');
        $this->printBlock($ast->block, $ctx);
        $ctx->newLine('end');
    }

    public function printFunctionCall(FunctionCall $ast, Context $ctx, $newLine)
    {
        if ($newLine) $ctx->newLine();
        $this->printPrefixExpression($ast->prefixExpression, $ctx);
        if ($ast->name !== null) {
            $ctx->append(':');
            $this->printName($ast->name, $ctx);
        }
        $this->printArgumentList($ast->arguments, $ctx);
    }

    public function printFunctionDeclaration(FunctionDeclaration $ast, Context $ctx)
    {
        $ctx->newLine('function ');
        $this->printFunctionName($ast->name, $ctx);
        $ctx->append(' ');
        $this->printFunctionBody($ast->body, $ctx);
    }

    public function printFunctionName(FunctionName $ast, Context $ctx)
    {
        foreach ($ast->names as $index => $name) {
            if ($index > 0) $ctx->append('.');
            $this->printName($name, $ctx);
        }
        if ($ast->colonName !== null) {
            $ctx->append(':');
            $this->printName($ast->colonName, $ctx);
        }
    }

    public function printGotoStatement(GotoStatement $ast, Context $ctx)
    {
        $ctx->newLine('goto ');
        $this->printName($ast->name, $ctx);
    }

    public function printIfStatement(IfStatement $ast, Context $ctx)
    {
        $ctx->newLine('if ');
        $this->printExpression($ast->expression, $ctx);
        $ctx->append(' then ');
        $this->printBlock($ast->block, $ctx);
        foreach ($ast->elseIfExpressions as $elseIfExpression) {
            $ctx->newLine();
            $this->printElseIfExpression($elseIfExpression, $ctx);
        }
        if ($ast->elseBlock !== null) {
            $ctx->newLine('else');
            $this->printBlock($ast->elseBlock, $ctx);
        }
        $ctx->newLine('end');
    }

    public function printKeywordLiteral(KeywordLiteral $ast, Context $ctx)
    {
        if ($ast->value === null) $ctx->append('nil');
        else $ctx->append($ast->value ? 'true' : 'false');
    }

    public function printLabel(Label $ast, Context $ctx)
    {
        $ctx->newLine('::');
        $this->printName($ast->name, $ctx);
        $ctx->append('::');
    }

    public function printLocalAssignment(LocalAssignment $ast, Context $ctx)
    {
        $ctx->newLine('local ');
        $this->printNameList($ast->names, $ctx);
        if ($ast->expressions !== null) {
            $ctx->append(' = ');
            $this->printExpressionList($ast->expressions, $ctx);
        }
    }

    public function printLocalFunctionDeclaration(LocalFunctionDeclaration $ast, Context $ctx)
    {
        $ctx->newLine('function ');
        $this->printName($ast->name, $ctx);
        $ctx->append(' ');
        $this->printFunctionBody($ast->body, $ctx);
    }

    public function printName(Name $ast, Context $ctx)
    {
        $ctx->append($ast->name);
    }

    public function printNamedField(NamedField $ast, Context $ctx)
    {
        if ($ast->name !== null) {
            $this->printName($ast->name, $ctx);
            $ctx->append(' = ');
        }
        $this->printExpression($ast->expression, $ctx);
        if ($ast->separator !== null) $ctx->append($ast->separator);
    }

    public function printNameList(NameList $ast, Context $ctx)
    {
        foreach ($ast->names as $index => $name) {
            if ($index > 0) $ctx->append(', ');
            $this->printName($name, $ctx);
        }
    }

    public function printNumber(Number $ast, Context $ctx)
    {
        $ctx->append($ast->value);
    }

    public function printParameterList(ParameterList $ast, Context $ctx)
    {
        if ($ast->names !== null) $this->printNameList($ast->names, $ctx);
        if ($ast->variableArguments !== null) {
            if ($ast->names !== null) $ctx->append(', ');
            $this->printVariableArguments($ast->variableArguments, $ctx);
        }
    }

    public function printParenthesizedExpression(ParenthesizedExpression $ast, Context $ctx)
    {
        $ctx->append('(');
        $this->printExpression($ast->expression, $ctx);
        $ctx->append(')');
    }

    public function printParenthesizedExpressionList(ParenthesizedExpressionList $ast, Context $ctx)
    {
        $ctx->append('(');
        if ($ast->expressions !== null) $this->printExpressionList($ast->expressions, $ctx);
        $ctx->append(')');
    }

    public function printPrefixExpression(PrefixExpression $ast, Context $ctx)
    {
        if ($ast instanceof Variable) $this->printVariable($ast, $ctx);
        elseif ($ast instanceof FunctionCall) $this->printFunctionCall($ast, $ctx, false);
        elseif ($ast instanceof ParenthesizedExpression) $this->printParenthesizedExpression($ast, $ctx);
        else throw new MehException('Unrecognized prefix expression: ' . get_class($ast));
    }

    public function printRepeatStatement(RepeatStatement $ast, Context $ctx)
    {
        $ctx->newLine('repeat');
        $this->printBlock($ast->block, $ctx);
        $ctx->newLine('until ');
        $this->printExpression($ast->expression, $ctx);
    }

    public function printReturnStatement(ReturnStatement $ast, Context $ctx)
    {
        $ctx->newLine('return');
        if ($ast->expressions !== null) {
            $ctx->append(' ');
            $this->printExpressionList($ast->expressions, $ctx);
        }
    }

    public function printStatement(Statement $ast, Context $ctx)
    {
        if ($ast instanceof EmptyStatement) $this->printEmptyStatement($ast, $ctx);
        elseif ($ast instanceof Assignment) $this->printAssignment($ast, $ctx);
        elseif ($ast instanceof FunctionCall) $this->printFunctionCall($ast, $ctx, true);
        elseif ($ast instanceof Label) $this->printLabel($ast, $ctx);
        elseif ($ast instanceof BreakStatement) $this->printBreakStatement($ast, $ctx);
        elseif ($ast instanceof GotoStatement) $this->printGotoStatement($ast, $ctx);
        elseif ($ast instanceof DoStatement) $this->printDoStatement($ast, $ctx);
        elseif ($ast instanceof WhileStatement) $this->printWhileStatement($ast, $ctx);
        elseif ($ast instanceof RepeatStatement) $this->printRepeatStatement($ast, $ctx);
        elseif ($ast instanceof IfStatement) $this->printIfStatement($ast, $ctx);
        elseif ($ast instanceof ForStatement) $this->printForStatement($ast, $ctx);
        elseif ($ast instanceof ForInStatement) $this->printForInStatement($ast, $ctx);
        elseif ($ast instanceof FunctionDeclaration) $this->printFunctionDeclaration($ast, $ctx);
        elseif ($ast instanceof LocalFunctionDeclaration) $this->printLocalFunctionDeclaration($ast, $ctx);
        elseif ($ast instanceof LocalAssignment) $this->printLocalAssignment($ast, $ctx);
        elseif ($ast instanceof StatementList) $this->printStatementList($ast, $ctx);
        else throw new MehException('Unrecognized statement: ' . get_class($ast));
    }

    public function printStatementList(StatementList $ast, Context $ctx)
    {
        foreach ($ast->statements as $statement) {
            $this->printStatement($statement, $ctx);
        }
    }

    public function printString(String $ast, Context $ctx)
    {
        // TODO: properly stringify
        $ctx->append('"' . $ast->unescape() . '"');
    }

    public function printTableConstructor(TableConstructor $ast, Context $ctx)
    {
        $ctx->append('{ ');
        if ($ast->fields !== null) $this->printFieldList($ast->fields, $ctx);
        $ctx->append('}');
    }

    public function printUnaryExpression(UnaryExpression $ast, Context $ctx)
    {
        $this->printUnaryOperator($ast->operator, $ctx);
        $this->printExpression($ast->expression, $ctx);
    }

    public function printUnaryOperator(UnaryOperator $ast, Context $ctx)
    {
        if ($ast->operator === 'not') $ctx->append('not ');
        else $ctx->append($ast->operator);
    }

    public function printVariable(Variable $ast, Context $ctx)
    {
        if ($ast instanceof Name) $this->printName($ast, $ctx);
        elseif ($ast instanceof VariableExpression) $this->printVariableExpression($ast, $ctx);
        elseif ($ast instanceof VariableName) $this->printVariableName($ast, $ctx);
        else throw new MehException('Unrecognized variable: ' . get_class($ast));
    }

    public function printVariableArguments(VariableArguments $ast, Context $ctx)
    {
        $ctx->append('...');
    }

    public function printVariableExpression(VariableExpression $ast, Context $ctx)
    {
        $this->printPrefixExpression($ast->prefixExpression, $ctx);
        $ctx->append('[');
        $this->printExpression($ast->expression, $ctx);
        $ctx->append(']');
    }

    public function printVariableList(VariableList $ast, Context $ctx)
    {
        foreach ($ast->variables as $index => $variable) {
            if ($index > 0) $ctx->append(', ');
            $this->printVariable($variable, $ctx);
        }
    }

    public function printVariableName(VariableName $ast, Context $ctx)
    {
        $this->printPrefixExpression($ast->prefixExpression, $ctx);
        $ctx->append('.');
        $this->printName($ast->name, $ctx);
    }

    public function printWhileStatement(WhileStatement $ast, Context $ctx)
    {
        $ctx->newLine('while ');
        $this->printExpression($ast->expression, $ctx);
        $ctx->append(' do');
        $this->printBlock($ast->block, $ctx);
        $ctx->newLine('end');
    }
}
