<?php
namespace Meh\Extension;

use Meh\Runtime\Extension\Basic\Php\InfoExtension;

class IniSetting
{
    /** @var int */
    public $accessMode;

    /** @var string|null */
    public $initialValue;

    /** @var string|null Required */
    public $name;

    /** @var callable|null */
    public $updateCallback;

    public function __construct(
        $name,
        $initialValue = null,
        $accessMode = InfoExtension::INI_ALL,
        callable $updateCallback = null
    ) {
        $this->name = $name;
        $this->initialValue = $initialValue;
        $this->accessMode = $accessMode;
        $this->updateCallback = $updateCallback;
    }
}