<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;
use Meh\MehException;

trait ExprMethodCall
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprMethodCall(\PHPParser_Node_Expr_MethodCall $node, Context $ctx)
    {
        $args = [];
        foreach ($node->args as $arg) {
            $args[] = $this->transpile($arg, $ctx);
        }
        // TODO: var func names
        if (!is_string($node->name)) throw new MehException('Must be literal name');
        return $ctx->phpCallMethod($this->transpile($node->var, $ctx), $node->name, $args);
    }
}
