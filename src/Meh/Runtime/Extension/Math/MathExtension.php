<?php
namespace Meh\Runtime\Extension\Math;

use Meh\Runtime\Extension\Extension;
use Meh\Runtime\Extension\ExtensionMetadata;

class MathExtension extends Extension
{
    public function getMetadata()
    {
        $meta = new ExtensionMetadata();
        $meta->addConstantsFromClass($this);
        $meta->addFunctionsFromNonOverriddenMethods($this);
        return $meta;
    }

    // TODO: lots more

    public function rand($min = null, $max = null)
    {
        if ($min === null) $min = 0;
        if ($max === null) $max = getrandmax();
        return MEH << 'php.intVal(math.random(ctx.min.val, ctx.max.val))';
    }
}
