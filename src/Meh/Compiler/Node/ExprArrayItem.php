<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprArrayItem
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprArrayItem(\PHPParser_Node_Expr_ArrayItem $node, Context $ctx)
    {
        // Build field as { key = ?, val = ? }
        $fields = ['val' => $this->transpile($node->value, $ctx)];
        if ($node->key !== null) {
            $fields['key'] = $this->transpile($node->key, $ctx);
        }
        return $ctx->bld->table($ctx->bld->fieldList($fields));
    }
}
