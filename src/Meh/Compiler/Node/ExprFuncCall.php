<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;
use Meh\MehException;

trait ExprFuncCall
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprFuncCall(\PHPParser_Node_Expr_FuncCall $node, Context $ctx)
    {
        $args = [];
        foreach ($node->args as $arg) {
            $args[] = $this->transpile($arg, $ctx);
        }
        // TODO: namespaces
        // TODO: var func names
        if (!($node->name instanceof \PHPParser_Node_Name)) throw new MehException('Must be literal name');
        if (count($node->name->parts) != 1) throw new MehException('Must be single name');
        return $ctx->phpCall([$node->name->parts[0]], $args);
    }
}
