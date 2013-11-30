<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait Arg
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileArg(\PHPParser_Node_Arg $node, Context $ctx)
    {
        // Not handling references for now
        return $this->transpile($node->value, $ctx);
    }
}
