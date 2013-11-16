<?php
namespace Meh\Lua\Ast;

class ForStatement implements Statement
{
    /** @var Name */
    public $name;

    /** @var Expression */
    public $initializer;

    /** @var Block */
    public $block;

    /** @var Expression|null */
    public $conditional;

    /** @var Expression|null */
    public $increment;

    /**
     * @param Name $name
     * @param Expression $initializer
     * @param Block $block
     * @param Expression $conditional
     * @param Expression $increment
     */
    function __construct(
        Name $name,
        Expression $initializer,
        Block $block,
        Expression $conditional = null,
        Expression $increment = null
    ) {
        $this->name = $name;
        $this->initializer = $initializer;
        $this->block = $block;
        $this->conditional = $conditional;
        $this->increment = $increment;
    }
}
