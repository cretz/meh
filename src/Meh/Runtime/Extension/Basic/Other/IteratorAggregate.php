<?php
namespace Meh\Runtime\Extension\Basic\Other;

interface IteratorAggregate extends \Traversable
{
    /** @return \Traversable */
    public function getIterator();
}