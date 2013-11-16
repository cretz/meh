<?php
namespace Meh\Lua\Ast;

class Chunk implements Block
{
    /** @var Statement[] */
    public $statements;

    /** @var LastStatement|null */
    public $lastStatement;

    /**
     * @param Statement[] $statements
     * @param LastStatement|null $lastStatement
     */
    public function __construct(array $statements, LastStatement $lastStatement = null)
    {
        $this->statements = $statements;
        $this->lastStatement = $lastStatement;
    }
}
