<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;
use Meh\MehException;

trait ExprStaticCall
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprStaticCall(\PHPParser_Node_Expr_StaticCall $node, Context $ctx)
    {
        // Only parent for now
        if (!($node->class instanceof \PHPParser_Node_Name) || $node->class->parts[0] !== 'parent') {
            throw new MehException('Must be parent');
        }
        // Convert args
        $args = [];
        foreach ($node->args as $arg) {
            $args[] = $this->transpile($arg, $ctx);
        }
        // Grab class context
        $currClass = $ctx->peekClass();
        if ($currClass === null || $currClass->getParentName() === null) throw new MehException('No parent');
        // TODO: var func names
        if (!is_string($node->name)) throw new MehException('Must be literal name');
        return $ctx->phpCallMethod(
            $currClass === null ? null : $currClass->getParentName(),
            $ctx->bld->varName('this'),
            $node->name,
            $args
        );
    }
}
