<?php
namespace Meh\Util;

class StringUtils extends UtilBase
{
    /**
     * @param string $contents
     * @return string
     */
    public function withLineNumbers($contents)
    {
        $ret = '';
        foreach (explode("\n", $contents) as $i => $line) {
            if ($i > 0) $ret .= "\n";
            $ret .= str_pad($i + 1, 3, '0', STR_PAD_LEFT) . ': ' . $line;
        }
        return $ret;
    }

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
