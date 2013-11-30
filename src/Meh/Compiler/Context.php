<?php
namespace Meh\Compiler;

use Meh\Lua\Ast\FunctionCall;
use Meh\Lua\Ast\FunctionDeclaration;
use Meh\Lua\Ast\Variable;
use Meh\MehException;

class Context
{
    /** @var Builder */
    public $bld;

    /** @var mixed[] Stack (but reversed, a "FILO" of sorts) */
    public $childContexts = [];

    /** @var bool */
    public $debugEnabled = false;

    public function __construct()
    {
        $this->bld = new Builder();
    }

    /** @param string $string */
    public function debug($string)
    {
        // TODO: make this smarter w/ more params and format
        if ($this->debugEnabled) {
            echo("DEBUG: " . $string . "\n");
        }
    }

    /** @return FunctionContext|null */
    public function peekFunc()
    {
        foreach ($this->childContexts as $context) {
            if ($context instanceof FunctionContext) return $context;
        }
        return null;
    }

    /**
     * @param bool $val
     * @return Variable
     */
    public function phpBool($val)
    {
        return $val ? $this->phpTrue() : $this->phpFalse();
    }

    /** @return Variable */
    public function phpFalse()
    {
        return $this->bld->varName(['php', 'falseVal']);
    }

    /**
     * @param float $val
     * @return FunctionCall
     */
    public function phpFloat($val)
    {
        return $this->bld->call($this->bld->varName(['php', 'floatVal']), [$this->bld->number($val)]);
    }

    /**
     * @param int $val
     * @return FunctionCall
     */
    public function phpInt($val)
    {
        return $this->bld->call($this->bld->varName(['php', 'intVal']), [$this->bld->number($val)]);
    }

    /** @return Variable */
    public function phpNull()
    {
        return $this->bld->varName(['php', 'nullVal']);
    }

    /**
     * @param string $val
     * @return FunctionCall
     */
    public function phpString($val)
    {
        return $this->bld->call($this->bld->varName(['php', 'stringVal']), [$this->bld->string($val)]);
    }

    /** @return Variable */
    public function phpTrue()
    {
        return $this->bld->varName(['php', 'trueVal']);
    }

    /**
     * @param mixed $val
     * @return Variable
     */
    public function phpVal($val)
    {
        if ($val === null) return $this->phpNull();
        if (is_bool($val)) return $this->phpBool($val);
        if (is_int($val)) return $this->phpInt($val);
    }

    /** @return FunctionContext */
    public function popFunc()
    {
        if (empty($this->childContexts) || !($this->childContexts[0] instanceof FunctionContext)) {
            throw new MehException('Function context not at top of stack');
        }
        return $this->childContexts[0];
    }

    /**
     * @param FunctionDeclaration $decl
     * @return FunctionContext
     */
    public function pushFunc(FunctionDeclaration $decl)
    {
        $ctx = new FunctionContext($decl);
        array_unshift($this->childContexts, $ctx);
        return $ctx;
    }
}
