<?php
namespace Meh\Compiler;

class LoopContext
{
    /** @var string|null Call method to lazily create */
    public $breakLabel;

    /** @var string|null Call method to lazily create */
    public $continueLabel;

    /**
     * @param VariableContext $varCtx
     * @return string
     */
    public function breakLabel(VariableContext $varCtx)
    {
        if ($this->breakLabel === null) {
            $this->breakLabel = $varCtx->newLocalTmpVarName();
        }
        return $this->breakLabel;
    }

    /**
     * @param VariableContext $varCtx
     * @return string
     */
    public function continueLabel(VariableContext $varCtx)
    {
        if ($this->continueLabel === null) {
            $this->continueLabel = $varCtx->newLocalTmpVarName();
        }
        return $this->continueLabel;
    }
}
