<?php
namespace Meh\Runtime\Extension\Basic\Other;

class SplDoublyLinkedList implements \Iterator, \ArrayAccess, \Countable
{
    const IT_MODE_DELETE = 1;
    const IT_MODE_FIFO = 0;
    const IT_MODE_KEEP = 0;
    const IT_MODE_LIFO = 2;

    private $bottom;
    private $current;
    private $iteratorMode = 0;
    private $top;

    public function bottom()
    {
        if (!isset($this->bottom)) throw new \RuntimeException('List empty');
        return $this->bottom->value;
    }

    public function count()
    {
        if (!isset($this->top)) return 0;
        return $this->top->getNextCount();
    }

    public function current()
    {
        if (!isset($this->current)) return null;
        return $this->current->value;
    }

    public function getIteratorMode()
    {
        return $this->iteratorMode;
    }

    private function getNodeAtOffset($index)
    {
        if (!is_numeric($index) || $this->isEmpty()) return null;
        $int = intval($index);
        $curr = $this->top;
        for ($i = 1; isset($curr->next) && $i <= $index; $i++) $curr = $curr->next;
        if ($i !== $int) return null;
        return $curr;
    }

    private function getOffsetForNode($node)
    {
        if ($node === null) return false;
        $index = 0;
        $curr = $this->top;
        while ($curr !== null && $curr !== $this->current) {
            $curr = $curr->next;
            $index++;
        }
        return $curr === $node ? $index : false;
    }

    public function isEmpty()
    {
        return !isset($this->top);
    }

    public function key()
    {
        $index = $this->getOffsetForNode($this->current);
        if ($index !== false) return $index;
        $this->current = null;
        return null;
    }

    public function next()
    {
        if (isset($this->current->next)) $this->current = $this->current->next;
    }

    public function offsetExists($index)
    {
        return $this->getNodeAtOffset($index) !== null;
    }

    public function offsetGet($index)
    {
        $node = $this->getNodeAtOffset($index);
        if ($node === null) throw new \OutOfRangeException();
        return $node->value;
    }

    public function offsetSet($index, $newval)
    {
        $node = $this->getNodeAtOffset($index);
        if ($node === null) throw new \OutOfRangeException();
        $node->value = $newval;
    }

    public function offsetUnset($index)
    {
        $node = $this->getNodeAtOffset($index);
        if ($node === null) throw new \OutOfRangeException();
        $this->removeNode($node);
    }

    public function pop()
    {
        if ($this->bottom === null) throw new \RuntimeException('Empty');
        $value = $this->bottom->value;
        $this->removeNode($this->bottom);
        return $value;
    }

    public function prev()
    {
        if (isset($this->current->previous)) $this->current = $this->current->previous;
    }

    public function push($value)
    {
        $node = new SplDoublyLinkedListNode();
        $node->value = $value;
        if ($this->isEmpty()) {
            $this->top = $node;
            $this->bottom = $node;
        } else {
            $this->bottom->next = $node;
            $node->previous = $this->bottom;
            $this->bottom = $node;
        }
    }

    private function removeNode($node)
    {
        // Reset stuff
        if ($this->top === $node) $this->top = $node->next;
        if ($this->bottom === $node) $this->bottom = $node->previous;
        if ($this->current === $node) $this->current = null;
        // Remove the node
        if ($node->previous !== null) $node->previous->next = $node->next;
        if ($node->next !== null) $node->next->previous = $node->previous;
    }

    public function rewind()
    {
        $this->current = $this->top;
    }

    public function setIteratorMode($mode)
    {
        $this->iteratorMode = $mode;
    }

    public function shift()
    {
        if ($this->bottom === null) throw new \RuntimeException('Empty');
        $value = $this->bottom->value;
        $this->removeNode($this->bottom);
        return $value;
    }

    // TODO: more
}
