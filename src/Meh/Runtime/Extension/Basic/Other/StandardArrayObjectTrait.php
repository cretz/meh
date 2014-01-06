<?php
namespace Meh\Runtime\Extension\Basic\Other;

trait StandardArrayObjectTrait
{
    public function __get($name)
    {
        if (array_key_exists($name, $this->input)) return $this->input[$name];
        trigger_error('Undefined property: ' . get_called_class() . '::$' . $name, E_NOTICE);
        return null;
    }

    public function __set($name, $value)
    {
        if ($this->_isArrayAsProps()) $this->$name = $value;
        else $this->input[$name] = $value;
    }

    abstract protected function _isArrayAsProps();

    public function append($value)
    {
        if (is_object($this->input)) trigger_error('Cannot append properties to objects', E_RECOVERABLE_ERROR);
        else $this->input[] = $value;
    }

    public function asort()
    {
        asort($this->input);
    }

    public function count()
    {
        return count($this->input);
    }

    public function getArrayCopy()
    {
        return (array) $this->input;
    }

    public function getFlags()
    {
        return $this->flags;
    }

    public function ksort()
    {
        ksort($this->input);
    }

    public function natcasesort()
    {
        natcasesort($this->input);
    }

    public function natsort()
    {
        natsort($this->input);
    }

    public function offsetExists($index)
    {
        return array_key_exists($index, $this->input);
    }

    public function &offsetGet($index)
    {
        if (array_key_exists($index, $this->input)) return $this->input[$index];
        trigger_error('Key does not exist', E_NOTICE);
        return null;
    }

    public function offsetSet($index, $newval)
    {
        if ($index === null) $this->append($newval);
        else $this->input[$index] = $newval;
    }

    public function offsetUnset($index)
    {
        unset($this->input[$index]);
    }

    public function setFlags($flags)
    {
        $this->flags = $flags;
    }

    public function uasort($cmp_function)
    {
        uasort($this->input, $cmp_function);
    }

    public function uksort($cmp_function)
    {
        uksort($this->input, $cmp_function);
    }
}
