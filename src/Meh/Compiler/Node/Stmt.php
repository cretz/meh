<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;
use Meh\MehException;

trait Stmt
{
    public function transpileStmt(\PHPParser_Node_Stmt $node, Context $ctx)
    {
        switch (get_class($node)) {
            case 'PHPParser_Node_StmtStatic': return $this->transpileStmtStatic($node, $ctx);
            default: throw new MehException('Unrecognized stmt: ' . get_class($node));
        }
    }
}