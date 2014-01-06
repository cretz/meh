<?php
namespace Meh\Runtime\Extension\Calendar;

use Meh\Runtime\Extension\Extension;
use Meh\Runtime\Extension\ExtensionMetadata;

class DateTimeExtension extends Extension
{
    public function getMetadata()
    {
        $meta = new ExtensionMetadata();
        $meta->addConstantsFromClass($this);
        $meta->addFunctionsFromNonOverriddenMethods($this);
        $meta->classes[] = [
            'DateInterval' => __NAMESPACE__ . '\\DateInterval',
            'DatePeriod' => __NAMESPACE__ . '\\DateInterval',
            'DateTime' => __NAMESPACE__ . '\\DateTime',
            'DateTimeImmutable' => __NAMESPACE__ . '\\DateTimeImmutable',
            'DateTimeInterface' => __NAMESPACE__ . '\\DateTimeInterface',
            'DateTimeZone' => __NAMESPACE__ . '\\DateTimeZone',
            'DateTimeImmutable' => __NAMESPACE__ . '\\DateTimeImmutable',
            'DateTimeImmutable' => __NAMESPACE__ . '\\DateTimeImmutable',
            'DateTimeImmutable' => __NAMESPACE__ . '\\DateTimeImmutable',
            'DateTimeImmutable' => __NAMESPACE__ . '\\DateTimeImmutable',
            'DateTimeImmutable' => __NAMESPACE__ . '\\DateTimeImmutable',
            'DateTimeImmutable' => __NAMESPACE__ . '\\DateTimeImmutable',
            'DateTimeImmutable' => __NAMESPACE__ . '\\DateTimeImmutable',
            'DateTimeImmutable' => __NAMESPACE__ . '\\DateTimeImmutable',
            'DateTimeImmutable' => __NAMESPACE__ . '\\DateTimeImmutable',
            'DateTimeImmutable' => __NAMESPACE__ . '\\DateTimeImmutable'
        ];
        return $meta;
    }

    // TODO
}
