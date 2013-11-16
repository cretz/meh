<?php
namespace Meh\Lua\Ast;

class BracketedField implements Field
{
    /** @var Expression */
    public $left;

    /** @var Expression */
    public $right;

    /** @var string|null */
    public $separator;

    /**
     * @param Expression $left
     * @param Expression $right
     * @param string|null $separator
     */
    public function __construct(Expression $left, Expression $right, $separator = null)
    {
        $this->left = $left;
        $this->right = $right;
        $this->separator = $separator;
    }
}
