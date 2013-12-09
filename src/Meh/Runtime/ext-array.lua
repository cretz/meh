local io = require("io")
local util = require("util")
local arrays = require("arrays")

local ext = {
  apply = function(php)
    php.defineFuncs({}, {
      -- Expects { key = ?, val = ? } w/ possible nil key
      array = function(...)
        -- Create empty array and puplate one piece at a time
        local arr = arrays.new()
        for _, v in pairs({...}) do
          arrays.set(arr, v.key, v.val)
        end
        return { type = "array", val = arr }
      end,
      current = function(arr)
        local v = arrays.current(arr.val)
        if v == nil then return php.boolVal(false) end
        return v
      end,
      key = function(arr)
        local v = arrays.key(arr.val)
        if v == nil then return php.nullVal() end
        if type(v) == "number" then return php.intVal(v) end
        return php.stringVal(v)
      end,
      reset = function(arr)
        arrays.reset(arr.val)
        local v = arrays.current(arr.val)
        if v == nil then return php.boolVal(false) end
        return v
      end,
      next = function(arr)
        local v = arrays.next(arr.val)
        if v == nil then return php.boolVal(false) end
        return v
      end
    })
  end
}

return ext