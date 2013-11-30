<?php
namespace Meh\Bin;

use Meh\Compiler\Context;
use Meh\Compiler\Transpiler;
use Meh\Lua\Printer\Printer;
use Meh\MehException;
use Meh\Test\Phpt\PhptParser;

class Dev extends Script
{
    /** @param string[] $options */
    public function run(array $options)
    {
        if (!isset($options[1])) throw new MehException('Command required');
        // Run command
        switch ($options[1]) {
            case 'dump-phpt-file-nodes':
                $this->dumpPhptFileNodes(array_slice($options, 2));
                break;
            case 'dump-phpt-to-lua':
                $this->dumpPhptToLua(array_slice($options, 2));
                break;
            default:
                throw new MehException('Unrecognized command ' . $options[1]);
        }
    }

    /** @param string[] $options */
    public function dumpPhptFileNodes(array $options)
    {
        if (!isset($options[0])) throw new MehException('File required');
        if (!file_exists($options[0])) throw new MehException('Cannot find file: ' . $options[0]);
        // Load test file
        $test = (new PhptParser())->parse(file_get_contents($options[0]));
        // Parse file part
        $stmts = (new \PHPParser_Parser(new \PHPParser_Lexer()))->parse($test->file->contents);
        // Dump
        echo((new \PHPParser_NodeDumper())->dump($stmts) . "\n");
    }

    public function dumpPhptToLua(array $options)
    {
        if (!isset($options[0])) throw new MehException('File required');
        if (!file_exists($options[0])) throw new MehException('Cannot find file: ' . $options[0]);
        // Load test file
        $test = (new PhptParser())->parse(file_get_contents($options[0]));
        // Dump PHP part
        echo("Parsing PHP:\n" . $test->file->contents . "\n");
        // Parse file part
        $stmts = (new \PHPParser_Parser(new \PHPParser_Lexer()))->parse($test->file->contents);
        // Transpile
        $nodes = (new Transpiler())->transpileFile($stmts, new Context());
        // Print
        $printCtx = new \Meh\Lua\Printer\Context();
        (new Printer())->printStatementList($nodes, $printCtx);
        echo("Lua output:\n" . $printCtx->asString() . "\n");
    }
}
