<?php
namespace Meh\Lua\Ast;

class Name extends Node implements Variable
{
    /** @var string */
    public $name;

    /** @param string $name */
    public function __construct($name)
    {
        $this->name = $name;
    }

    public function __toString()
    {
        return $this->name;
    }
}
