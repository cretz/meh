<?php
namespace Meh\Runtime\Extension\Basic\Other;

class ArrayObject implements \IteratorAggregate, \ArrayAccess, \Serializable, \Countable
{
    use StandardArrayObjectTrait;

    const STD_PROP_LIST = 1;
    const ARRAY_AS_PROPS = 2;

    private $input;
    private $flags;
    private $iterator_class;

    public function __construct(&$input = [], $flags = 0, $iterator_class = 'ArrayIterator')
    {
        $this->input =& $input;
        $this->flags = $flags;
        $this->iterator_class = $iterator_class;
    }

    protected final function _isArrayAsProps()
    {
        return $this->flags & self::ARRAY_AS_PROPS !== 0;
    }

    public function exchangeArray(&$input)
    {
        $old = $this->input;
        $this->input =& $input;
        return $old;
    }

    public function getIterator()
    {
        return new $this->iterator_class($this->input);
    }

    public function getIteratorClass()
    {
        return $this->iterator_class;
    }

    public function serialize()
    {
        // TODO
    }

    public function setIteratorClass($iterator_class)
    {
        $this->iterator_class = $iterator_class;
    }

    public function unserialize($serialized)
    {
        // TODO
    }
}
