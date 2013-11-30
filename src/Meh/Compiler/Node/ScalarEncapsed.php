<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ScalarEncapsed
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileScalarEncapsed(\PHPParser_Node_Scalar_Encapsed $node, Context $ctx)
    {
        $concatPieces = [];
        foreach ($node->parts as $part) {
            if (is_string($part)) $concatPieces[] = $ctx->phpString($part);
            else $concatPieces[] = $this->transpile($part, $ctx);
        }
        return $ctx->phpConcat($concatPieces);
    }
}
