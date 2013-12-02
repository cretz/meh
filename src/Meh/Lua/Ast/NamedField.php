<?php
namespace Meh\Lua\Ast;

class NamedField extends Node implements Field
{
    /** @var Expression */
    public $expression;

    /** @var Name|null */
    public $name;

    /** @var string|null */
    public $separator;

    /**
     * @param Expression $expression
     * @param Name $name
     * @param string $separator
     */
    public function __construct(Expression $expression, Name $name = null, $separator = null)
    {
        $this->expression = $expression;
        $this->name = $name;
        $this->separator = $separator;
    }

    public function getSeparator()
    {
        return $this->separator;
    }
}
