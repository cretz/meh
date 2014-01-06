<?php
namespace Meh\Runtime\Extension\Basic\VarType;

interface Reflector
{
    /** @return string */
    public static function export();

    /** @return string */
    public function __toString();
}
