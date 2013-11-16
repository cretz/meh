<?php
namespace Meh\Util;

class UtilBase
{
    public function __callStatic($name, array $arguments)
    {
        return call_user_func_array([new static(), $name], $arguments);
    }
}
