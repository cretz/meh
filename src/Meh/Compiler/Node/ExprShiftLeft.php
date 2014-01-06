<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;
use Meh\MehException;

trait ExprShiftLeft
{
    public function transpileExprShiftLeft(\PHPParser_Node_Expr_ShiftLeft $node, Context $ctx)
    {
        // Using MEH << 'some code' does a literal injection
        if ($node->left instanceof \PHPParser_Node_Expr_ConstFetch
            && $node->left->name->parts[0] === 'MEH'
            && $node->right instanceof \PHPParser_Node_Scalar_String
        ) {
            return $ctx->bld->luaCode($node->right->value);
        }
        // TODO: other stuff
        throw new MehException('Unimplemented');
    }
}
