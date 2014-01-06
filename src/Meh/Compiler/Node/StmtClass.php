<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;
use Meh\Lua\Ast\TableConstructor;
use Meh\MehException;

trait StmtClass
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    private function mergeTableConstructors(TableConstructor $new, TableConstructor $existing = null)
    {
        if ($existing === null) return $new;
        $existing->fields->fields = array_merge($existing->fields->fields, $new->fields->fields);
        return $existing;
    }

    public function getClassMembers(\PHPParser_Node_Stmt $node, Context $ctx)
    {
        $fields = [];
        // Members
        $methods = [];
        $traits = [];
        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof \PHPParser_Node_Stmt_ClassMethod) {
                // Associative array for methods to be table-ified at the end
                $methods[$stmt->name] = $this->transpile($stmt, $ctx);
            } elseif ($stmt instanceof \PHPParser_Node_Stmt_Property) {
                // Just use the table given back from the property stmt
                $fields['properties'] = $this->mergeTableConstructors(
                    $this->transpile($stmt, $ctx),
                    isset($fields['properties']) ? $fields['properties'] : null
                );
            } elseif ($stmt instanceof \PHPParser_Node_Stmt_TraitUse) {
                // Just use the table given back
                $fields['traits'] = $this->mergeTableConstructors(
                    $this->transpile($stmt, $ctx),
                    isset($fields['traits']) ? $fields['traits'] : null
                );
            } else throw new MehException('Unknown type: ' . get_class($node));
        }
        if (!empty($methods)) $fields['methods'] = $ctx->bld->table($ctx->bld->fieldList($methods));
        return $fields;
    }

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
        $fields += $this->getClassMembers($node, $ctx);
        // Pop class
        $ctx->popClass();
        return $ctx->phpDefineClass([], $fields);
    }
}
