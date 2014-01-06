<?php
namespace Meh\LPeg;

MEH << 'local lpeg = require("lpeg")';
MEH << 'local patt = function(val) return php.resourceVal(val, "lpeg-pattern") end';

function C(Pattern $pattern)
{
    return $pattern->capture();
}

function Carg($n)
{
    // TODO
}

function Cb($name)
{
    // TODO
}

function Cc()
{
    // TODO
}

function Cf(Pattern $pattern, callable $func)
{
    return $pattern->captureFold($func);
}

function Cg(Pattern $pattern, $name = null)
{
    return $pattern->captureGroup($name);
}

function Cmt(Pattern $pattern, callable $func)
{
    return $pattern->captureMatchTime($func);
}

function Cp()
{
    // TODO
}

function Cs(Pattern $pattern)
{
    return $pattern->captureSub();
}

function Ct(Pattern $pattern)
{
    return $pattern->captureTable();
}

function match(Pattern $pattern, $string)
{
    return $pattern->match($string);
}

function P($stringOrNumber)
{
    return new Pattern(MEH << 'patt(lpeg.P(ctx.stringOrNumber.val))');
}

function R($chars)
{
    return new Pattern(MEH << 'patt(lpeg.R(ctx.chars.val))');
}

function S($string)
{
    return new Pattern(MEH << 'patt(lpeg.S(ctx.string.val))');
}
