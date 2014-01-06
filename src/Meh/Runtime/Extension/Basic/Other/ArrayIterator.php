<?php
namespace Meh\Runtime\Extension\Basic\Other;

class ArrayIterator implements \ArrayAccess, \SeekableIterator, \Countable, \Serializable
{
    use StandardArrayObjectTrait;

    private $input;
    private $flags;
    private $keys;

    public function __construct(&$input = [], $flags = 0)
    {
        $this->input =& $input;
        $this->flags = $flags;
        $this->keys = array_keys(array_keys($this->input));
    }

    protected final function _isArrayAsProps()
    {
        return $this->flags === 1;
    }

    private function assertArrayNotModified()
    {
        $key = current($this->keys);
        if ($key !== false && !array_key_exists($key, $this->input)) {
            trigger_error('Array modified outside of object', E_NOTICE);
            return false;
        }
        return $key;
    }

    public function current()
    {
        $key = $this->key();
        if ($key === false) return false;
        return $this->input[$key];
    }

    public function key()
    {
        return $this->assertArrayNotModified();
    }

    public function next()
    {
        $this->assertArrayNotModified();
        next($this->keys);
    }

    public function rewind()
    {
        $this->assertArrayNotModified();
        reset($this->keys);
    }

    public function seek($position)
    {
        $this->assertArrayNotModified();
        reset($this->keys);
        for ($i = 1; $i < count($this->keys); $i++) {
            next($this->keys);
        }
    }

    public function serialize()
    {
        // TODO
    }

    public function unserialize($serialized)
    {
        // TODO
    }

    public function valid()
    {
        return $this->assertArrayNotModified() !== false;
    }
}
