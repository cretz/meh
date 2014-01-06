<?php
namespace Meh\LPeg;

// Ref: http://lua-users.org/wiki/LpegTutorial

assert(P('a')->match('aaa') === 2);
assert(P('a')->match('123') === null);

assert(R('09')->match('123') === 2);
assert(S('123')->match('1234') === 2);

assert(P('a')->oneOrMore()->match('aaa') === 4);
assert(P('a')->then(P('b')->zeroOrMore())->match('abbc') === 4);

$maybe_a = P('a')->zeroOrOne();
$match_ab = $maybe_a->then(P('b'));
assert($match_ab->match('ab') === 3);
assert($match_ab->match('b') === 2);
assert($match_ab->match('aaab') === null);

$either_ab = P('a')->orThen(P('b'))->oneOrMore();
assert($either_ab->match('aaa') === 4);
assert($either_ab->match('bbaa') === 5);

$digit = R('09');
$digits = $digit->oneOrMore();
$cdigits = C($digits);
assert($cdigits->match('123') === 123);

$int = S('+-')->zeroOrOne()->then($digits);
assert($int->match('+23') === '+23');

assert($int->captureWithFunction('intval')->match('+123') + 1 === 124);

assert(P('a')->oneOrMore()->capture()->then(P('b')->oneOrMore()->capture())->match('aabbbb') === ['aa', 'bbbb']);

function maybe(Pattern $p) { return $p->zeroOrOne(); }
$digits = R('09')->oneOrMore();
$mpm = maybe(S('+-'));
$dot = P('.');
$exp = S('eE');
$float = $mpm->then($digits)->then(maybe($dot->then($digits)))->then(maybe($exp->then($mpm)->then($digits)));
assert(match($float->capture(), '2.3') === '2.3');
assert(match($float->capture(), '-2') === '-2');
assert(match($float->capture(), '2e-02') === '2e-02');

$listf = $float->capture()->then(P(',')->then($float->capture())->zeroOrMore());
assert($listf->match('2,3,4') === [2, 3, 4]);

assert($listf->captureTable()->match('1,2,3') === [1, 2, 3]);

$floatc = $float->captureWithFunction('floatval');
$listf = $floatc->then(P(',')->then($floatc)->zeroOrMore());

$sp = P(' ')->zeroOrMore();
function space(Pattern $pat) { global $sp; return $sp->then($pat)->then($sp); }
$floatc = space($float->captureWithFunction('floatval'));
$listc = $floatc->then(P(',')->then($floatc)->zeroOrMore());
assert($listc->captureTable()->match(' 1,2, 3') === [1, 2, 3]);

function list_(Pattern $pat)
{
    $pat = space($pat);
    return $pat->then(P(',')->then($pat)->zeroOrMore());
}

$idenchar = R('AZ', 'az')->orThen(P('_'));
$iden = $idenchar->then($idenchar->orThen(R('09'))->zeroOrMore());
assert(list_($iden->capture())->match('hello, dolly, _x, s23') === ['hello', 'dolly', '_x', 's23']);

// TODO: locale

$Q = P('"');
$str = $Q->then(P(1)->butNot($Q)->zeroOrMore())->then($Q);
assert($str->capture()->match('"hello"') === '"hello"');

$str2 = $Q->then(P(1)->butNot($Q)->zeroOrMore()->capture())->then($Q);
assert($str->capture()->match('"hello"') === 'hello');