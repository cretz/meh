<?php
namespace Meh\Runtime\Extension\Basic\VarType;

use Meh\Runtime\Extension\Extension;
use Meh\Runtime\Extension\ExtensionMetadata;

class ArrayExtension extends Extension
{
    const CASE_LOWER = 1;
    const CASE_UPPER = 2;
    // TODO: more

    public function getMetadata()
    {
        $meta = new ExtensionMetadata();
        $meta->addConstantsFromClass($this);
        $meta->addFunctionsFromNonOverriddenMethods($this);
        return $meta;
    }

    public function array_change_key_case($array, $case = self::CASE_LOWER)
    {
        $ret = [];
        foreach ($array as $key => $value) {
            if (!is_string($key)) $ret[$key] = $value;
            else if ($case === self::CASE_LOWER) $ret[strtolower($key)] = $value;
            else $ret[strtoupper($key)] = $value;
        }
        return $ret;
    }

    public function array_chunk($array, $size, $preserve_keys = false)
    {
        if ($size < 1) {
            trigger_error('Size must be larger than 0', E_WARNING);
            return null;
        }
        $ret = [];
        $currArray = null;
        foreach ($array as $key => $value) {
            if ($currArray === null) {
                $currArray = array();
                $ret[] &= $currArray;
            }
            if ($preserve_keys) $currArray[$key] = $value;
            else $currArray[] = $value;
            if (count($currArray) === $size) $currArray = null;
        }
        return $ret;
    }

    public function array_column($array, $column_key, $index_key = null)
    {
        $ret = [];
        foreach ($array as $row) {
            if ($column_key === null) {
                if ($index_key !== null && array_key_exists($index_key, $row)) {
                    $ret[$row[$index_key]] = $row;
                } else $ret[] = $row;
            } elseif (is_array($row) && array_key_exists($column_key, $row)) {
                if ($index_key !== null && array_key_exists($index_key, $row)) {
                    $ret[$row[$index_key]] = $row[$column_key];
                } else $ret[] = $row[$column_key];
            }
        }
        return $ret;
    }

    public function array_combine($keys, $values)
    {
        if (count($keys) !== count($values)) {
            trigger_error('Key and value array size do not match', E_WARNING);
        }
        $ret = [];
        reset($keys);
        reset($values);
        while (true) {
            $key = each($keys);
            $val = each($values);
            if ($key === false || $val === false) return $ret;
            $ret[$key['value']] = $val['value'];
        }
    }

    protected function array_compare_callback(array $arrays, callable $check)
    {
        $arrays = func_get_args();
        $ret = [];
        foreach ($arrays[0] as $key => $value) {
            for ($i = 1; $i < count($arrays); $i++) {
                if ($check($key, $value, $arrays[$i], $ret)) break;
            }
            $ret[$key] = $value;
        }
        return $ret;
    }

    public function array_count_values($array)
    {
        $ret = [];
        foreach ($array as $index => $value) {
            if (!is_int($value) && !is_string($value)) {
                trigger_error('Value at index ' . $index . ' is not string or integer');
            } elseif (!isset($ret[$value])) $ret[$value] = 0;
            else $ret[$value]++;
        }
        return $ret;
    }

    public function array_diff($array1, $array2)
    {
        return $this->array_compare_callback(func_get_args(), function ($key, $value, $array, &$ret) {
            if (in_array($value, $array)) return false;
            $ret[$key] = $value;
            return true;
        });
    }

    public function array_diff_assoc($array1, $array2)
    {
        return $this->array_compare_callback(func_get_args(), function ($key, $value, $array, &$ret) {
            if (array_key_exists($key, $array) && $array[$key] === $value) return false;
            $ret[$key] = $value;
            return true;
        });
    }

    public function array_diff_key($array1, $array2)
    {
        return $this->array_compare_callback(func_get_args(), function ($key, $value, $array, &$ret) {
            if (array_key_exists($key, $array)) return false;
            $ret[$key] = $value;
            return true;
        });
    }

    public function array_diff_uassoc($array1, $array2, $key_compare_func)
    {
        $arrays = func_get_args();
        $key_compare_func = array_pop($arrays);
        return $this->array_compare_callback($arrays, function ($key, $value, $array, &$ret) use ($key_compare_func) {
            foreach ($array as $arrKey => $arrValue) {
                if ($key_compare_func($key, $arrKey) === 0 && $array[$key] === $arrValue) return false;
            }
            $ret[$key] = $value;
            return true;
        });
    }

    public function array_diff_ukey($array1, $array2, $key_compare_func)
    {
        $arrays = func_get_args();
        $key_compare_func = array_pop($arrays);
        return $this->array_compare_callback($arrays, function ($key, $value, $array, &$ret) use ($key_compare_func) {
            foreach (array_keys($array) as $arrKey) {
                if ($key_compare_func($key, $arrKey) === 0) return false;
            }
            $ret[$key] = $value;
            return true;
        });
    }

    public function array_fill($start_index, $num, $value)
    {
        $ret = [];
        for ($i = $start_index; $i < $num; $i++) {
            $ret[$i] = $value;
        }
        return $value;
    }

    public function array_fill_keys($keys, $value)
    {
        $ret = [];
        foreach ($keys as $key) {
            $ret[$key] = $value;
        }
        return $ret;
    }

    public function array_filter($array, $callback = null)
    {
        $ret = [];
        foreach ($array as $key => $value) {
            if ($callback === null && $value) $ret[$key] = $value;
            elseif ($callback !== null && $callback($value)) $ret[$key] = $value;
        }
        return $ret;
    }

    public function array_flip($array)
    {
        $ret = [];
        foreach ($array as $key => $value) {
            if (!is_int($value) && !is_string($value)) {
                trigger_error('Value at index ' . $key . ' is not string or integer', E_WARNING);
            } else $ret[$value] = $key;
        }
        return $ret;
    }

    // TODO: intersect stuff

    public function array_key_exists($key, $array)
    {
        MEH << 'php.boolVal(ctx.array.val.hash[ctx.key.val] ~= nil)';
    }

    public function array_keys($array, $search_value = null, $strict = false)
    {
        $ret = [];
        foreach ($array as $key => $value) {
            if ($search_value !== null) {
                if ($strict && $value !== $search_value) continue;
                elseif (!strict && $value != $search_value) continue;
            }
            $ret[] = $key;
        }
        return $ret;
    }

    public function array_map($callback, $array1)
    {
        $arrays = func_get_args();
        $callback = array_shift($arrays);
        if ($callback === null) $callback = 'func_get_args';
        $ret = [];
        foreach ($arrays as $array) reset($array);
        while (true) {
            $args = [];
            $foundArgs = false;
            foreach ($arrays as $array) {
                $val = each($array);
                if ($val !== false) {
                    $foundArgs = true;
                    $args[] = $val['value'];
                } else $args[] = null;
            }
            if (!$foundArgs) return $ret;
            if (count($arrays) === 1) $ret[$val['key']] = call_user_func_array($callback, $args);
            else $ret[] = call_user_func_array($callback, $args);
        }
    }

    public function array_merge($array1)
    {
        $ret = [];
        foreach (func_get_args() as $array) {
            foreach ($array as $key => $value) {
                if (is_numeric($key)) $ret[] = $value;
                else $ret[$key] = $value;
            }
        }
        return $ret;
    }

    public function array_merge_recursive($array1)
    {
        $ret = [];
        foreach (func_get_args() as $array) {
            foreach ($array as $key => $value) {
                if (is_numeric($key)) $ret[] = $value;
                else {
                    if (array_key_exists($key, $ret)) {
                        $arr = null;
                        if (is_array($ret[$key])) $arr = $ret[$key];
                        else $arr = [$ret[$key]];
                        if (is_array($value)) $arr = array_merge_recursive($arr, $value);
                        else $arr[] = $value;
                        $ret[$key] = $arr;
                    } elseif (is_array($value)) $ret[$key] = array_merge_recursive($value);
                    else $ret[$key] = $value;
                }
            }
        }
        return $ret;
    }

    // TODO: array_multisort when varargs references are done

    public function array_pad($array, $size, $value)
    {
        $negative = $size < 0;
        $count = abs($size) - count($array);
        for ($i = 0; $i < $count; $i++) {
            if ($negative) array_unshift($array, $value);
            else $array[] = $value;
        }
        return $array;
    }

    public function array_pop(&$array)
    {
        // TODO: native
    }

    public function array_product($array)
    {
        $ret = 1;
        foreach ($array as $value) {
            $ret *= $value;
        }
        return $ret;
    }

    public function array_push(&$array, $value1)
    {
        foreach (array_slice(func_get_args(), 1) as $value) {
            $array[] = $value;
        }
        return count($array);
    }

    public function array_rand($array, $num = 1)
    {
        // TODO
    }

    public function array_reduce($array, $callback, $initial = null)
    {
        $acc = $initial;
        foreach ($array as $value) $acc = $callback($acc, $value);
        return $acc;
    }

    // TODO: more
}
