<?php
namespace Meh\Lua\Ast;

class ForStatement extends Node implements Statement
{
    /** @var Name */
    public $name;

    /** @var Expression */
    public $initializer;

    /** @var Expression */
    public $conditional;

    /** @var Block */
    public $block;

    /** @var Expression|null */
    public $increment;

    /**
     * @param Name $name
     * @param Expression $initializer
     * @param Expression $conditional
     * @param Block $block
     * @param Expression $increment
     */
    function __construct(
        Name $name,
        Expression $initializer,
        Expression $conditional,
        Block $block,
        Expression $increment = null
    ) {
        $this->name = $name;
        $this->initializer = $initializer;
        $this->conditional = $conditional;
        $this->block = $block;
        $this->increment = $increment;
    }
}
