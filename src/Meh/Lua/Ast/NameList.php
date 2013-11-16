<?php
namespace Meh\Lua\Ast;

class NameList
{
    /** @var Name[] */
    public $names;

    /** @param Name[] $names */
    public function __construct(array $names)
    {
        $this->names = $names;
    }
}
