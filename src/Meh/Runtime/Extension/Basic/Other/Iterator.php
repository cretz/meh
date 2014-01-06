<?php
namespace Meh\Runtime\Extension\Basic\Other;

interface Iterator extends \Traversable
{
    public function current();
    public function key();
    public function next();
    public function rewind();
    public function valid();
}