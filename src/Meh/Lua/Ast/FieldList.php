<?php
namespace Meh\Lua\Ast;

class FieldList
{
    /** @var Field[] */
    public $fields;

    /** @param Field[] $fields */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }
}
