<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;
use Meh\MehException;

trait StmtProperty
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileStmtProperty(\PHPParser_Node_Stmt_Property $node, Context $ctx)
    {
        // We are going to return a table of all properties in this set
        $allProperties = [];
        foreach ($node->props as $prop) {
            $allProperties[$prop->name] = $ctx->bld->table(
                $ctx->bld->fieldList([
                    'modifiers' => $ctx->bld->number($node->type),
                    'default' => $prop->default === null ? $ctx->phpNull() : $this->transpile($prop->default, $ctx)
                ])
            );
        }
        return $ctx->bld->table($ctx->bld->fieldList($allProperties));
    }
}
