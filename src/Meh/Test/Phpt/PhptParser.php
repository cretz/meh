<?php
namespace Meh\Test\Phpt;

class PhptParser
{
    /** @var string[] */
    public static $KNOWN_DIRECTIVES = [
        'TEST', 'FILE', 'EXPECT', 'EXPECTF'
    ];

    /**
     * @param string $contents
     * @return PhptTest
     * @throws PhptException
     */
    public function parse($contents)
    {
        // Get all lines
        $lines = array_map('rtrim', explode("\n", $contents));
        // Pieces to build up
        $name = null;
        $file = null;
        $expectation = null;
        // Go through each handling expected pieces
        $cursor = 0;
        do {
            switch ($lines[$cursor]) {
                case '--TEST--':
                    $name = $this->parseName($lines, $cursor);
                    break;
                case '--FILE--':
                    $file = $this->parseFile($lines, $cursor);
                    break;
                case '--EXPECT--':
                case '--EXPECTF--':
                    $expectation = $this->parseExpectation($lines, $cursor);
                    break;
                default:
                    // No-op
                    $cursor++;
            }
        } while ($cursor < count($lines));
        // Make sure everything is there
        if ($name === null) throw new PhptException('Phpt name required');
        if ($file === null) throw new PhptException('Phpt file required');
        if ($expectation === null) throw new PhptException('Phpt expectation required');
        return new PhptTest($name, $file, $expectation);
    }

    /**
     * @param array $lines
     * @param $cursor
     * @return string
     */
    public function parseName(array $lines, &$cursor)
    {
        // Skip first and read
        return $this->readUntilNextDirective($lines, ++$cursor);
    }

    /**
     * @param array $lines
     * @param int $cursor
     * @return PhptFile
     */
    public function parseFile(array $lines, &$cursor)
    {
        // Skip first and read
        return new PhptFile($this->readUntilNextDirective($lines, ++$cursor));
    }

    /**
     * @param array $lines
     * @param int $cursor
     * @return PhptExpectation
     */
    public function parseExpectation(array $lines, &$cursor)
    {
        // Formatted?
        $formatted = $lines[$cursor] === '--EXPECTF--';
        // Skip and read
        return new PhptExpectation($this->readUntilNextDirective($lines, ++$cursor), $formatted);
    }

    /**
     * @param array $lines
     * @param int $cursor
     * @return string
     */
    public function readUntilNextDirective(array $lines, &$cursor)
    {
        $contents = '';
        do {
            // Check end or append
            if (!isset($lines[$cursor])) break;
            if (strpos($lines[$cursor], '--') === 0
                && substr_compare($lines[$cursor], '--', -2) === 0
                && in_array(substr($lines[$cursor], 2, -2), self::$KNOWN_DIRECTIVES)
            ) break;
            if (!empty($contents)) $contents .= "\n";
            $contents .= $lines[$cursor];
            $cursor++;
        } while (true);
        return trim($contents);
    }
}
