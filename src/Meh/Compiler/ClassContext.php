<?php
namespace Meh\Compiler;

class ClassContext extends VariableContext
{
    /** @var \PHPParser_Node_Stmt_Class */
    public $node;

    public function __construct(\PHPParser_Node_Stmt_Class $node)
    {
        $this->node = $node;
    }

    public function getName()
    {
        return $this->node->name;
    }

    public function getParentName()
    {
        if ($this->node->extends === null) return null;
        // TODO: namespaces
        return $this->node->extends->parts[0];
    }
}
