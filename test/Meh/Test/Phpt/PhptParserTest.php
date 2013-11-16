<?php
namespace Meh\Test\Phpt;

use Meh\MehTestCase;

class PhptParserTest extends MehTestCase
{
    public function testParseSimple()
    {
        $test = (new PhptParser())->parse(file_get_contents(__DIR__ . '/001.phpt'));
        $this->assertEquals('func_num_args() tests', $test->name);
        $this->assertStringStartsWith('<?php', $test->file->contents);
        $this->assertStringEndsWith('?>', $test->file->contents);
        $this->assertStringStartsWith('int(0)', $test->expectation->contents);
        $this->assertStringEndsWith('Done', $test->expectation->contents);
    }
}