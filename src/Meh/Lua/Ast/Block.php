<?php
namespace Meh\Lua\Ast;

class Block extends Node implements Chunk
{
    /** @var Statement[] */
    public $statements;

    /** @var ReturnStatement|null */
    public $returnStatement;

    /**
     * @param Statement[] $statements
     * @param ReturnStatement|null $returnStatement
     */
    public function __construct(array $statements, ReturnStatement $returnStatement = null)
    {
        $this->statements = $statements;
        $this->returnStatement = $returnStatement;
    }
}
