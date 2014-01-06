<?php
namespace Meh\Runtime\Extension\Basic\Other;

interface SeekableIterator extends \Iterator
{
    public function seek();
}
