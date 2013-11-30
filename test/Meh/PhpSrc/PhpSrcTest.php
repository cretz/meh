<?php
namespace Meh\PhpSrc;

use Meh\MehTestCase;
use Meh\Test\Phpt\PhptParser;

class PhpSrcTest extends MehTestCase
{
    public function phptFileProvider()
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(__DIR__ . '/php-src'),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );
        $files = [];
        foreach ($iterator as $fileInfo) {
            $realPath = $fileInfo->getRealPath();
            if (substr_compare($realPath, '.phpt', -5) === 0) $files[] = [$realPath];
        }
        return $files;
    }

    /**
     * @dataProvider phptFileProvider
     * @param string $file
     */
    public function testPhptFile($file)
    {
        // Load and parse phpt file
        $test = (new PhptParser())->parse(file_get_contents($file));
        // Compile it

        // Run it
        // Compare output
        $this->assertEquals(1, 1);
    }
}
