<?php
namespace Meh\Runtime\Extension\Basic\Other;

class SplDoublyLinkedListNode
{
    public $next;
    public $previous;
    public $value;

    public function getNextCount()
    {
        if (!isset($this->next)) return 0;
        return 1 + $this->next->getNextCount();
    }
}
