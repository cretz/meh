<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;
use Meh\MehException;

trait StmtClassMethod
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    /**
     * @param \PHPParser_Node_Stmt_Function|\PHPParser_Node_Stmt_ClassMethod $node
     * @param Context $ctx
     * @param bool $prependThisParam
     * @return \Meh\Lua\Ast\AnonymousFunction
     */
    abstract public function transpileFunction(\PHPParser_Node_Stmt $node, Context $ctx, $prependThisParam = false);

    public function transpileStmtClassMethod(\PHPParser_Node_Stmt_ClassMethod $node, Context $ctx)
    {
        $func = $this->transpileFunction($node, $ctx, true);
        // Fields
        $fields = [
            'modifiers' => $ctx->bld->number($node->type),
            '__invoke' => $func
        ];
        return $ctx->bld->call(
            $ctx->bld->varName(['php', 'defineMethod']),
            [$ctx->bld->table($ctx->bld->fieldList($fields))]
        );
    }
}
