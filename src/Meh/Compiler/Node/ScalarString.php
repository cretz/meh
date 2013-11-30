<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ScalarString
{
    public function transpileScalarString(\PHPParser_Node_Scalar_String $node, Context $ctx)
    {
        return $ctx->bld->string($node->value);
    }
}
