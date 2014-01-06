<?php
namespace Meh\Runtime\Extension\Basic\VarType;

use Meh\Runtime\Extension\Extension;
use Meh\Runtime\Extension\ExtensionMetadata;

class VarExtension extends Extension
{
    public function getMetadata()
    {
        $meta = new ExtensionMetadata();
        $meta->addConstantsFromClass($this);
        $meta->addFunctionsFromNonOverriddenMethods($this);
        return $meta;
    }

    public function boolval($var)
    {
        return $var !== false && $var !== 0 && $var !== '' && $var !== '0'
            && $var !== [] && $var !== null;
    }

    public function doubleval($var)
    {
        return floatval($var);
    }

    public function empty_($var)
    {
        return !isset($var) || $var == false;
    }

    public function floatval($var)
    {
        if (!is_string($var)) {
            $var = intval($var);
            return MEH << 'php.floatVal(ctx.var.val)';
        }
        // Fancy string conversion...just get numeric chars until it doesn't apply
        $signFound = false;
        $decimalFound = false;
        $exponentFound = false;
        $str = '';
        for ($i = 0; $i < strlen($var); $i++) {
            $chr = $var[$i];
            if (!$signFound && $chr === '-') $signFound = true;
            elseif (!$exponentFound && !$decimalFound && !empty($str) && $chr === '.') $decimalFound = true;
            elseif (!empty($str) && !$exponentFound && ($chr === 'e' || $chr === 'E')) $exponentFound = true;
            elseif (!ctype_digit($chr)) break;
            $str .= $chr;
        }
        // Empty or sign only is 0
        if (empty($str) || (strlen($str) === 1 && $signFound)) return 0.0;
        // Drop ending decimal and/or exponent w/ no trailing number
        if ($str[strlen($str) - 1] === '.') $str = substr($str, 0, strlen($str) - 1);
        if ($str[strlen($str) - 1] === 'e' || $str[strlen($str) - 1] === 'E') $str = substr($str, 0, strlen($str) - 1);
        // Convert
        return MEH << 'php.floatVal(tonumber(ctx.str.val))';
    }

    public function gettype($var)
    {
        MEH << 'return php.stringVal(ctx.var.type)';
    }

    public function intval($var)
    {
        switch (gettype($var)) {
            case 'boolean': return $var ? 1 : 0;
            case 'float': return floor($var);
            case 'string': return intval(floatval($var));
            default: return -1;
        }
    }

    public function is_array($var)
    {
        return gettype($var) === 'array';
    }

    public function is_bool($var)
    {
        return gettype($var) === 'boolean';
    }

    public function is_callable($name, $syntax_only = false, &$callable_name = null)
    {
        $class = null;
        $func = null;
        if (is_object($name)) {
            $class = get_class($name);
            $func = '__invoke';
        } elseif (is_array($name) && isset($name[0]) && isset($name[1])) {
            $class = $name[0];
            $func = $name[1];
            if (!is_string($class) || !is_string($func)) return false;
            // Take out parent::
            if (!$syntax_only && strpos($func, 'parent::') === 0) {
                $class = get_parent_class($class);
                if ($class === false) return false;
                $func = substr($func, 8);
            }
        } elseif (is_string($name)) {
            $doubleColon = strpos($name, '::');
            if ($doubleColon === false) $func = $name;
            else {
                $class = substr($name, 0, $doubleColon);
                $func = substr($name, $doubleColon + 2);
            }
        } else return false;
        // Validate
        if (!$syntax_only && $class !== null && !method_exists($class, $func)) return false;
        if (!$syntax_only && $class === null && !function_exists($func)) return false;
        if ($class !== null) $callable_name = $class . '::' . $func;
        else $callable_name = $func;
        return true;
    }

    public function is_double($var)
    {
        return is_float($var);
    }

    public function is_float($var)
    {
        return gettype($var) === 'double';
    }

    public function is_int($var)
    {
        return gettype($var) === 'integer';
    }

    public function is_integer($var)
    {
        return gettype($var) === 'integer';
    }

    public function is_long($var)
    {
        return gettype($var) === 'long';
    }

    public function is_null($var)
    {
        return gettype($var) === 'NULL';
    }

    public function is_numeric($var)
    {
        if (is_int($var) || is_float($var)) return true;
        // TODO:
        throw new MehException('Numeric string check not yet implemented');
    }

    public function is_object($var)
    {
        return gettype($var) === 'object';
    }

    public function is_real($var)
    {
        return is_float($var);
    }

    public function is_resource($var)
    {
        return gettype($var) === 'resource';
    }

    public function is_scalar($var)
    {
        $type = gettype($var);
        return $type === 'integer' || $type === 'float' || $type === 'string' || $type === 'boolean';
    }

    public function is_string($var)
    {
        return gettype($var) === 'string';
    }

    public function isset_($var)
    {
        $unset = MEH << 'php.boolVal(vtx.var == nil)';
        if ($unset) return false;
        $vars = is_array($var) ? $var : array($var);
        foreach ($vars as $var) {
            if ($var === null) return false;
        }
        return true;
    }

    public function settype(&$var, $type)
    {
        switch ($type) {
            case 'boolean':
            case 'bool':
                $var = boolval($var);
                return true;
            case 'integer':
            case 'int':
                $var = intval($var);
                return true;
            case 'float':
            case 'double':
                $var = floatval($var);
                return true;
            case 'string':
                $var = strval($var);
                return true;
            case 'array':
                switch (gettype($var)) {
                    case 'array':
                        return true;
                    case 'object':
                        // TODO
                        throw new MehException('TODO: object vars');
                    case 'NULL':
                        $var = array();
                        return true;
                    default:
                        $var = array($var);
                        return true;
                }
            case 'NULL';
                $var = null;
                return true;
        }
        // TODO: binary strings
        return false;
    }

    public function strval($var)
    {
        switch (gettype($var)) {
            case 'boolean': return $var ? '1' : '';
            case 'integer':
            case 'double': return MEH << 'php.stringVal("" .. ctx.var.val)';
            case 'array': return 'Array';
            case 'object':
                if (method_exists($var, '__toString')) return $var->__toString();
                return 'Object';
            case 'resource': return MEH << 'php.stringVal("Resource id #" .. ctx.var.id)';
            case 'NULL': return '';
            default: return 'unknown';
        }
    }

    public function unset_($var)
    {
        // TODO
    }
}
