<?php
namespace Meh\LPeg;

MEH << 'local patt = function(val) return php.resourceVal(val, "lpeg-pattern") end';

class Pattern
{
    private $value;

    public function __construct($value)
    {
        if (get_resource_type($value) !== 'lpeg-pattern') throw new MehException('Invalid pattern resource');
        $this->value = $value;
    }

    public function atLeast($n)
    {
        return new Pattern(MEH << 'patt(ctx.this.properties.value.val ^ ctx.n.val)');
    }

    public function atMost($n)
    {
        return new Pattern(MEH << 'patt(ctx.this.properties.value.val ^ -ctx.n.val)');
    }

    public function butNot(Pattern $right)
    {
        return new Pattern(MEH << 'patt(ctx.this.properties.value.val - ctx.right.properties.value.val)');
    }

    public function capture()
    {
        // TODO: C
    }

    public function captureFold(callable $func)
    {
        // TODO: Cf
    }

    public function captureGroup($name = null)
    {
        // TODO: Cg
    }

    public function captureMatchTime(callable $func)
    {
        // TODO: Cmt
    }

    public function captureSub()
    {
        // TODO: Cs
    }

    public function captureTable()
    {
        // TODO: Ct
    }

    public function captureWithFunction($val)
    {
        // TODO: slash
    }

    public function captureWithNumber($val)
    {
        // TODO: slash
    }

    public function captureWithString($val)
    {
        // TODO: slash
    }

    public function captureWithTable($val)
    {
        // TODO: slash
    }

    public function match($string)
    {
        // TODO
    }

    public function oneOrMore()
    {
        return $this->atLeast(1);
    }

    public function orThen(Pattern $right)
    {
        return new Pattern(MEH << 'patt(ctx.this.properties.value.val + ctx.right.properties.value.val)');
    }

    public function then(Pattern $right)
    {
        return new Pattern(MEH << 'patt(ctx.this.properties.value.val * ctx.right.properties.value.val)');
    }

    public function zeroOrMore()
    {
        return $this->atLeast(0);
    }

    public function zeroOrOne()
    {
        return $this->atMost(1);
    }
}