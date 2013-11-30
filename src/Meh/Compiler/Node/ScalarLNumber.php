<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ScalarLNumber
{
    public function transpileScalarLNumber(\PHPParser_Node_Scalar_LNumber $node, Context $ctx)
    {
        return $ctx->phpInt($node->value);
    }
}
