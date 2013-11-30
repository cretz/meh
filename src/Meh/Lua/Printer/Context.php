<?php
namespace Meh\Lua\Printer;

class Context
{
    /** @var int */
    public $depth = 0;

    /** @var string */
    public $indent = '  ';

    /** @var string[] */
    public $lines = [];

    public function append($contents)
    {
        $this->lines[count($this->lines) - 1] .= $contents;
        return $this;
    }

    public function asString()
    {
        return implode("\n", $this->lines);
    }

    public function currIndent()
    {
        return str_repeat($this->indent, $this->depth);
    }

    public function dedent()
    {
        $this->depth--;
        return $this;
    }

    public function indent()
    {
        $this->depth++;
        return $this;
    }

    public function newLine($contents = '')
    {
        $this->lines[] = $this->currIndent() . $contents;
        return $this;
    }
}
