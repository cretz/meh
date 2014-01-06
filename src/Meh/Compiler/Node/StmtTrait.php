<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;
use Meh\Lua\Ast\TableConstructor;
use Meh\MehException;

trait StmtTrait
{
    abstract public function getClassMembers(\PHPParser_Node_Stmt $node, Context $ctx);

    public function transpileStmtTrait(\PHPParser_Node_Stmt_Trait $node, Context $ctx)
    {
        // Fields
        $fields = ['name' => $ctx->bld->string($node->name)] + $this->getClassMembers($node, $ctx);
        return $ctx->phpDefineTrait([], $fields);
    }
}
