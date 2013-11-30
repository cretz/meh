<?php
namespace Meh\Lua\Ast;

class GotoStatement extends Node implements Statement
{
    /** @var Name */
    public $name;

    public function __construct(Name $name)
    {
        $this->name = $name;
    }
}
