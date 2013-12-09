<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprArray
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprArray(\PHPParser_Node_Expr_Array $node, Context $ctx)
    {
        // Build all pieces
        $fields = [];
        foreach ($node->items as $item) {
            $fields[] = $this->transpile($item, $ctx);
        }
        // Build array val
        return $ctx->phpArray($ctx->bld->fieldList($fields));
    }
}
