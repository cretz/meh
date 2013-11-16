<?php
namespace Meh\Lua\Ast;

class TableConstructor implements Expression, ArgumentList
{
    /** @var FieldList|null */
    public $fields;

    /** @param FieldList $fields */
    public function __construct(FieldList $fields = null)
    {
        $this->fields = $fields;
    }
}
