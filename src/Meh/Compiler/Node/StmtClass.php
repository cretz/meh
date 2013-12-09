<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;
use Meh\Lua\Ast\TableConstructor;
use Meh\MehException;

trait StmtClass
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileStmtClass(\PHPParser_Node_Stmt_Class $node, Context $ctx)
    {
        // Fields
        $fields = [
            'name' => $ctx->bld->string($node->name),
            'modifiers' => $ctx->bld->number($node->type)
        ];
        // Parent? TODO: namespaces
        if ($node->extends !== null) {
            $fields['parent'] = $ctx->bld->string($node->extends->parts[0]);
        }
        // Push class
        $ctx->pushClass($node);
        // Now methods and properties
        $methods = [];
        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof \PHPParser_Node_Stmt_ClassMethod) {
                // Associative array for methods to be table-ified at the end
                $methods[$stmt->name] = $this->transpile($stmt, $ctx);
            } elseif ($stmt instanceof \PHPParser_Node_Stmt_Property) {
                // Just use the table given back from the property stmt
                /** @var TableConstructor */
                $properties = $this->transpile($stmt, $ctx);
                if (!isset($fields['properties'])) $fields['properties'] = $properties;
                else {
                    $fields['properties']->fields->fields = array_merge(
                        $fields['properties']->fields->fields,
                        $properties->fields->fields
                    );
                }
            } else throw new MehException('Unknown type: ' . get_class($node));
        }
        if (!empty($methods)) $fields['methods'] = $ctx->bld->table($ctx->bld->fieldList($methods));
        // Pop class
        $ctx->popClass();
        return $ctx->phpDefineClass([], $fields);
    }
}
