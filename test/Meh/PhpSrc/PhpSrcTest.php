<?php
namespace Meh\PhpSrc;

use Meh\Compiler\Context;
use Meh\Compiler\Transpiler;
use Meh\Lua\Printer\Printer;
use Meh\MehTestCase;
use Meh\Test\Phpt\PhptParser;

class PhpSrcTest extends MehTestCase
{
    public function testLuaJitEnvironmentVariable()
    {
        $path = getenv('LUA_CMD_PATH');
        $this->assertNotSame(false, $path, 'LUA_CMD_PATH not set');
        $this->assertFileExists($path, 'LUA_CMD_PATH not set to file');
        return $path;
    }

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
//         return $files;
        return [$files[count($files) - 1]];
    }

    /**
     * @depends testLuaJitEnvironmentVariable
     * @dataProvider phptFileProvider
     * @param string $file
     * @param string $luajitPath
     */
    public function testPhptFile($file, $luajitPath)
    {
        // Load and parse phpt file
        $test = (new PhptParser())->parse(file_get_contents($file));
        $stmts = (new \PHPParser_Parser(new \PHPParser_Lexer()))->parse($test->file->contents);
        // Compile it
        $transpileCtx = new Context();
        $stmtList = (new Transpiler())->transpileFile($stmts, $transpileCtx);
        // To string
        $printCtx = new \Meh\Lua\Printer\Context();
        (new Printer())->printStatementList($stmtList, $printCtx);
        // Find the php runtime
        $runtimePath = realpath(__DIR__ . '/../../../src/Meh/Runtime/');
        $this->assertNotSame(false, $runtimePath, 'Cannot find runtime folder');
        // Build process
        $pipes = [];
        $process = proc_open(
            $luajitPath . ' -',
            [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            $pipes,
            null,
            ['LUA_PATH' => $runtimePath . '/?.lua']
        );
        $this->assertNotSame(false, $process);
        // Write the script
        $script = $printCtx->asString();
        fwrite($pipes[0], $script);
        fclose($pipes[0]);
        // Get the contents
        $contents = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        // And error contents
        $errorContents = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        // Close and check result
        $this->assertEquals(
            0,
            proc_close($process),
            "Process failed, input: \n" . $script . "\nOutput:\n" .
                $contents . "\nErr:\n" . $errorContents
        );
        // Compare output
        // Trim each line of contents
        $contents = implode("\n", array_map('trim', explode("\n", $contents)));
        $this->assertEquals(
            trim($test->expectation->contents),
            trim($contents),
            "Comparison failed, input: \n" . $script . "\nErr:\n" . $errorContents
        );
    }
}
