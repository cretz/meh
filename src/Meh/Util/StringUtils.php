<?php
namespace Meh\Util;

class StringUtils extends UtilBase
{
    /**
     * @param string $contents
     * @param string $marginChar
     * @return string
     */
    public function stripMargin($contents, $marginChar = '|')
    {
        return implode("\n",
            array_map(
                function ($line) use ($marginChar) { return ltrim($line, " \t" . $marginChar); },
                explode("\n", $contents)
            )
        );
    }
}
