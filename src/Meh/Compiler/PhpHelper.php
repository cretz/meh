<?php
namespace Meh\Compiler;

use Meh\Lua\Ast\Expression;
use Meh\Lua\Ast\FunctionCall;
use Meh\Lua\Ast\Name;
use Meh\Lua\Ast\NamedField;
use Meh\Lua\Ast\Variable;

/**
 * @property Builder $bld
 */
trait PhpHelper
{
    /**
     * @param Expression $left
     * @param Expression $right
     * @return FunctionCall
     */
    public function phpAdd(Expression $left, Expression $right)
    {
        return $this->bld->call($this->bld->varName(['php', 'add']), [$left, $right]);
    }

    /**
     * @param Expression $left
     * @param Expression $right
     * @return FunctionCall
     */
    public function phpAssign(Expression $left, Expression $right)
    {
        return $this->bld->call($this->bld->varName(['php', 'assign']), [$left, $right]);
    }

    /**
     * @param Expression $left
     * @param Expression $right
     * @return FunctionCall
     */
    public function phpBitAnd(Expression $left, Expression $right)
    {
        return $this->bld->call($this->bld->varName(['php', 'bitAnd']), [$left, $right]);
    }

    /**
     * @param Expression $left
     * @param Expression $right
     * @return FunctionCall
     */
    public function phpBitOr(Expression $left, Expression $right)
    {
        return $this->bld->call($this->bld->varName(['php', 'bitOr']), [$left, $right]);
    }

    /**
     * @param bool $val
     * @return Variable
     */
    public function phpBool($val)
    {
        return $val ? $this->phpTrue() : $this->phpFalse();
    }

    /**
     * @param string[] $nsPath
     * @param Expression[] $arguments
     * @return FunctionCall
     */
    public function phpCall(array $nsPath, array $arguments)
    {
        $name = array_pop($nsPath);
        return $this->bld->call(
            $this->bld->varName(['php', 'call']),
            [
                $this->bld->tableStrArr($nsPath),
                $this->phpString($name),
                $this->bld->table($this->bld->fieldList($arguments))
            ]
        );
    }

    /**
     * @param Expression[] $pieces
     * @return FunctionCall
     */
    public function phpConcat(array $pieces)
    {
        return $this->bld->call($this->bld->varName(['php', 'concat']), $pieces);
    }

    /**
     * @param string[] $ns
     * @param Expression[] $funcs Keyed by func name
     * @return FunctionCall
     */
    public function phpDefineFuncs(array $ns, array $funcs)
    {
        return $this->bld->call(
            $this->bld->varName(['php', 'defineFuncs']),
            [
                $this->bld->tableStrArr($ns),
                $this->bld->table($this->bld->fieldList($funcs))
            ]
        );
    }

    /**
     * @param Expression $left
     * @param Expression $right
     * @return FunctionCall
     */
    public function phpDiv(Expression $left, Expression $right)
    {
        return $this->bld->call($this->bld->varName(['php', 'div']), [$left, $right]);
    }

    /**
     * @param Expression $left
     * @param Expression $right
     * @return FunctionCall
     */
    public function phpEq(Expression $left, Expression $right)
    {
        return $this->bld->call($this->bld->varName(['php', 'eq']), [$left, $right]);
    }

    /** @return Variable */
    public function phpFalse()
    {
        return $this->bld->varName(['php', 'falseVal']);
    }

    /**
     * @param float $val
     * @return FunctionCall
     */
    public function phpFloat($val)
    {
        return $this->bld->call($this->bld->varName(['php', 'floatVal']), [$this->bld->number($val)]);
    }

    /**
     * @param Expression $left
     * @param Expression $right
     * @return FunctionCall
     */
    public function phpGt(Expression $left, Expression $right)
    {
        return $this->bld->call(
            $this->bld->varName(['php', 'gt']),
            [$left, $right]
        );
    }

    /**
     * @param Expression $left
     * @param Expression $right
     * @return FunctionCall
     */
    public function phpGte(Expression $left, Expression $right)
    {
        return $this->bld->call(
            $this->bld->varName(['php', 'gte']),
            [$left, $right]
        );
    }

    /**
     * @param int $val
     * @return FunctionCall
     */
    public function phpInt($val)
    {
        return $this->bld->call($this->bld->varName(['php', 'intVal']), [$this->bld->number($val)]);
    }

    /**
     * @param Expression $val
     * @return FunctionCall
     */
    public function phpIsFalse(Expression $val)
    {
        return $this->bld->call($this->bld->varName(['php', 'isFalse']), [$val]);
    }

    /**
     * @param Expression $val
     * @return FunctionCall
     */
    public function phpIsTrue(Expression $val)
    {
        return $this->bld->call($this->bld->varName(['php', 'isTrue']), [$val]);
    }

    /**
     * @param Expression $left
     * @param Expression $right
     * @return FunctionCall
     */
    public function phpLt(Expression $left, Expression $right)
    {
        return $this->bld->call(
            $this->bld->varName(['php', 'lt']),
            [$left, $right]
        );
    }

    /**
     * @param Expression $left
     * @param Expression $right
     * @return FunctionCall
     */
    public function phpLte(Expression $left, Expression $right)
    {
        return $this->bld->call(
            $this->bld->varName(['php', 'lte']),
            [$left, $right]
        );
    }

    /**
     * @param Expression $left
     * @param Expression $right
     * @return FunctionCall
     */
    public function phpMult(Expression $left, Expression $right)
    {
        return $this->bld->call($this->bld->varName(['php', 'mult']), [$left, $right]);
    }

    /**
     * @param string[] $pieces
     * @return FunctionCall
     */
    public function phpNs(array $pieces)
    {
        return $this->bld->call(
            $this->bld->varName(['php', 'ns']),
            [$this->bld->tableStrArr($pieces)]
        );
    }

    /** @return Variable */
    public function phpNull()
    {
        return $this->bld->varName(['php', 'nullVal']);
    }

    /**
     * @param string[] $pieces
     * @return FunctionCall
     */
    public function phpStaticNs(array $pieces)
    {
        return $this->bld->call(
            $this->bld->varName(['php', 'staticNs']),
            [$this->bld->tableStrArr($pieces)]
        );
    }

    /**
     * @param string $val
     * @return FunctionCall
     */
    public function phpString($val)
    {
        return $this->bld->call($this->bld->varName(['php', 'stringVal']), [$this->bld->string($val)]);
    }

    /**
     * @param Expression $left
     * @param Expression $right
     * @return FunctionCall
     */
    public function phpSub(Expression $left, Expression $right)
    {
        return $this->bld->call($this->bld->varName(['php', 'sub']), [$left, $right]);
    }

    /** @return Variable */
    public function phpTrue()
    {
        return $this->bld->varName(['php', 'trueVal']);
    }

    /**
     * @param mixed $val
     * @return Variable
     */
    public function phpVal($val)
    {
        if ($val === null) return $this->phpNull();
        if (is_bool($val)) return $this->phpBool($val);
        if (is_int($val)) return $this->phpInt($val);
    }
}
