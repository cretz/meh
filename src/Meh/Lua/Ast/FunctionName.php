<?php
namespace Meh\Lua\Ast;

class FunctionName extends Node
{
    /** @var Name[] */
    public $names;

    /** @var Name|null */
    public $colonName;

    /**
     * @param Name[] $names
     * @param Name $colonName
     */
    public function __construct(array $names, Name $colonName = null)
    {
        $this->names = $names;
        $this->colonName = $colonName;
    }

    public function __toString()
    {
        $str = '';
        if ($this->colonName !== null) $str = $this->colonName . ':';
        return $str . implode('.', $this->names);
    }
}
