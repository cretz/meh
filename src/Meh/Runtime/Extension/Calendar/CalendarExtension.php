<?php
namespace Meh\Runtime\Extension\Calendar;

use Meh\Runtime\Extension\Extension;
use Meh\Runtime\Extension\ExtensionMetadata;

class CalendarExtension extends Extension
{
    public function getMetadata()
    {
        $meta = new ExtensionMetadata();
        $meta->addConstantsFromClass($this);
        $meta->addFunctionsFromNonOverriddenMethods($this);
        return $meta;
    }

    // TODO
}
