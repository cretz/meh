<?php
namespace Meh\Runtime\Extension;

use Meh\Extension\IniSetting;

class ExtensionMetadata
{
    /** @var string[] Keyed by class alias, value is actual impl */
    public $classes = [];

    /** @var string|int|float Keyed by constant name */
    public $constants = [];

    /** @var callable[] Keyed by name */
    public $functions = [];

    /** @var IniSetting[] */
    public $iniSettings = [];

    /** @var string|null */
    public $name;
}