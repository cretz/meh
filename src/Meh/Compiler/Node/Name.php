<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait Name
{
    public function transpileName(\PHPParser_Node_Name $node, Context $ctx)
    {
        return $ctx->bld->varName($node->parts);
    }
}