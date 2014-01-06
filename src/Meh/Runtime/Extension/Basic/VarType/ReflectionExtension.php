<?php
namespace Meh\Runtime\Extension\Basic\VarType;

use Meh\Runtime\Extension\Extension;
use Meh\Runtime\Extension\ExtensionMetadata;

class ReflectionExtension extends Extension
{
    public function getMetadata()
    {
        $meta = new ExtensionMetadata();
        $meta->classes[] = [
            'ReflectionClass' => __NAMESPACE__ . '\\ReflectionClass',
            'Reflector' => __NAMESPACE__ . '\\Reflector'
        ];
        return $meta;
    }
}
