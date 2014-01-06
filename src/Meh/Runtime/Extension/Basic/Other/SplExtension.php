<?php
namespace Meh\Runtime\Extension\Basic\Other;

use Meh\Runtime\Extension\Extension;
use Meh\Runtime\Extension\ExtensionMetadata;

class SplExtension extends Extension
{
    public function getMetadata()
    {
        $meta = new ExtensionMetadata();
        $meta->addConstantsFromClass($this);
        $meta->addFunctionsFromNonOverriddenMethods($this);
        return $meta;
    }
}
