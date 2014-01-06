<?php
namespace Meh\Runtime\Extension\Basic\Php;

use Meh\Extension\IniSetting;
use Meh\Runtime\Extension\Extension;
use Meh\Runtime\Extension\ExtensionMetadata;

class InfoExtension extends Extension
{
    const CREDITS_GROUP = 1;
    const CREDITS_GENERAL = 2;
    // TODO: more

    const INI_USER = 1;
    const INI_PERDIR = 2;
    const INI_SYSTEM = 4;
    const INI_ALL = 7;

    public function getMetadata()
    {
        $meta = new ExtensionMetadata();
        $meta->addConstantsFromClass($this);
        $meta->iniSettings = [
            new IniSetting('assert.active', '1'),
            // TODO: more
            new IniSetting('max_input_time', '-1', self::INI_PERDIR)
        ];
        $meta->addFunctionsFromNonOverriddenMethods($this);
        return $meta;
    }

    public function assert_options($what, $value = null)
    {
        // TODO: impl
    }
}
