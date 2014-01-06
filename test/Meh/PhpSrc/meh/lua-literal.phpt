--TEST--
Lua code injection
--FILE--
<?php
$a = 'World';
function addHello($val) {
    return MEH << 'php.stringVal("Hello " .. ctx.val.val)';
}
echo(addHello($a));
--EXPECT--
Hello World
