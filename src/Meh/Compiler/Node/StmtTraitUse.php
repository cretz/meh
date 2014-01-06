<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;
use Meh\MehException;

trait StmtTraitUse
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileStmtTraitUse(\PHPParser_Node_Stmt_TraitUse $node, Context $ctx)
    {
        // We are going to return a table of all traits in this set
        $allTraits = [];
        foreach ($node->traits as $trait) {
            // TODO: namespaces
            $allTraits[$trait->parts[0]] = $ctx->bld->table($ctx->bld->fieldList([]));
        }
        // TODO: adaptations
        return $ctx->bld->table($ctx->bld->fieldList($allTraits));
    }
}
