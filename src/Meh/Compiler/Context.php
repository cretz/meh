<?php
namespace Meh\Compiler;

use Meh\Lua\Ast\FunctionDeclaration;
use Meh\MehException;

class Context
{
    use PhpHelper;

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

    /** @return VariableContext|null */
    public function peekGlobalVarCtx()
    {
        if (empty($this->childContexts)) return null;
        return $this->childContexts[count($this->childContexts) - 1];
    }

    /**
     * @param int $depth
     * @return LoopContext|null
     */
    public function peekLoop($depth = 1)
    {
        $currDepth = 0;
        foreach ($this->childContexts as $context) {
            if ($context instanceof LoopContext && ++$currDepth == $depth) return $context;
        }
        return null;
    }

    /** @return VariableContext|null */
    public function peekVarCtx()
    {
        foreach ($this->childContexts as $context) {
            if ($context instanceof VariableContext) return $context;
        }
        return null;
    }

    /** @return FunctionContext */
    public function popFunc()
    {
        if (empty($this->childContexts) || !($this->childContexts[0] instanceof FunctionContext)) {
            throw new MehException('Function context not at top of stack');
        }
        return array_shift($this->childContexts);
    }

    /** @return LoopContext */
    public function popLoop()
    {
        if (empty($this->childContexts) || !($this->childContexts[0] instanceof LoopContext)) {
            throw new MehException('Loop context not at top of stack');
        }
        return array_shift($this->childContexts);
    }

    /** @return VariableContext */
    public function popVarCtx()
    {
        if (empty($this->childContexts) || !($this->childContexts[0] instanceof VariableContext)) {
            throw new MehException('Variable context not at top of stack');
        }
        return array_shift($this->childContexts);
    }

    /**
     * @param string $name
     * @return FunctionContext
     */
    public function pushFunc($name)
    {
        $ctx = new FunctionContext($name);
        array_unshift($this->childContexts, $ctx);
        return $ctx;
    }

    /** @return LoopContext */
    public function pushLoop()
    {
        $ctx = new LoopContext();
        array_unshift($this->childContexts, $ctx);
        return $ctx;
    }

    /** @return VariableContext */
    public function pushVarCtx()
    {
        $ctx = new VariableContext();
        array_unshift($this->childContexts, $ctx);
        return $ctx;
    }
}
