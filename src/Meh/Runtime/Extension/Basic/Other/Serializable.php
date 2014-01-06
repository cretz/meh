<?php
namespace Meh\Runtime\Extension\Basic\Other;

interface Serializable
{
    public function serialize();
    public function unserialize($serialized);
}
