<?php
namespace Meh\Runtime\Extension\Basic\Text;

use Meh\Runtime\Extension\Extension;
use Meh\Runtime\Extension\ExtensionMetadata;

class StringsExtension extends Extension
{
    public function getMetadata()
    {
        $meta = new ExtensionMetadata();
        $meta->addConstantsFromClass($this);
        $meta->addFunctionsFromNonOverriddenMethods($this);
        return $meta;
    }

    public function chr($ascii)
    {
        $ascii = intval($ascii);
        MEH << 'return php.stringVal(string.char(ctx.ascii))';
    }

    public function count_chars($string, $mode = 0)
    {
        $chars = [];
        for ($i = 0; $i < strlen($string); $i++) {
            $char = ord($string[$i]);
            if (!isset($chars[$char])) $chars[$char] = 1;
            else $chars[$char]++;
        }
        switch ($mode) {
            case 0:
                for ($i = 0; $i <= 255; $i++) if (!isset($chars[$i])) $chars[$i] = 0;
                return $chars;
            case 1:
                return $chars;
            case 2:
                for ($i = 0; $i <= 255; $i++){
                    if (isset($chars[$i])) unset($chars[$i]);
                    else $chars[$i] = 0;
                }
                return $chars;
            case 3:
                $ret = '';
                foreach ($chars as $byte => $count) if ($count === 1) $ret .= chr($byte);
                return $ret;
            case 4:
                $ret = '';
                for ($i = 0; $i <= 255; $i++) if (!isset($chars[$i])) $ret .= chr($i);
                return $ret;
            default:
                trigger_error('Unknown mode', E_WARNING);
                return false;
        }
    }

    public function explode($delimiter, $string, $limit = null)
    {
        if (empty($delimiter)) return false;
        $results = [];
        $last = '';
        for ($i = 0; $i < strlen($string) && ($limit === null || count($results) < $limit); $i++) {
            $last .= $string[$i];
            if (substr_compare($last, $delimiter, -strlen($delimiter)) === 0) {
                $results[] = substr($last, 0, -strlen($delimiter));
                $last = '';
            }
        }
        return $results;
    }

    public function implode($glue, $pieces = null)
    {
        if ($pieces === null) {
            $pieces = $glue;
            if (!is_array($pieces)) {
                trigger_error('Argument must be an array', E_WARNING);
                return null;
            }
            $glue = null;
        } elseif (is_array($glue)) {
            $tmp = $glue;
            $glue = $pieces;
            $pieces = $tmp;
        } elseif (!is_array($pieces)) {
            trigger_error('Invalid arguments passed', E_WARNING);
            return null;
        }
        $ret = '';
        $first = true;
        foreach ($pieces as $piece) {
            if (!$first) $ret .= $glue;
            else $first = false;
            $ret .= $piece;
        }
        return $ret;
    }

    public function join($glue, $pieces = null)
    {
        return implode($glue, $pieces);
    }

    public function lcfirst($str)
    {
        return strtolower($str[0]) . substr($str, 1);
    }

    public function ltrim($str, $charlist = " \t\n\r\0\x0B")
    {
        for ($i = 0; $i < strlen($str) && strpos($charlist, $str[$i]) !== false; $i++);
        return substr($str, $i);
    }

    public function nl2br($string, $is_xhtml = true)
    {
        return str_replace(["\r\n", "\n\r", "\r", "\n"], $is_xhtml ? '<br />' : '<br>', $string);
    }

    public function rtrim($str, $charlist = " \t\n\r\0\x0B")
    {
        for ($i = strlen($str) - 1; $i >= 0 && strpos($charlist, $str[$i]) !== false; $i--);
        return substr($str, 0, $i);
    }

    public function strlen($string)
    {
        if (is_array($string)) {
            trigger_error('Array not allowed', E_WARNING);
            return null;
        }
        $string = strval($string);
        MEH << 'return php.intVal(string.len(ctx.string.val))';
    }

    public function strpos($haystack, $needle, $offset = 0)
    {
        $haystack = strval($haystack);
        if (!is_string($needle)) $needle = chr(intval($needle));
        $offset = intval($offset);
        MEH << '
        local __TMP__ = string.find(ctx.haystack.val, ctx.needle.val, ctx.offset.val, true)
        if __TMP__ == nil then return php.boolVal(false) else return php.intVal(__TMP__ - 1)';
    }

    public function trim($str, $charlist = " \t\n\r\0\x0B")
    {
        return ltrim(rtrim($str, $charlist), $charlist);
    }

    public function vsprintf($format, $args)
    {
        throw new \Exception('Not implemented');
    }
}
