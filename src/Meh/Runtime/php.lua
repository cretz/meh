
local io = require "io"

local php = {}

php.globals = {}

php.falseVal = { type = "bool", val = false }
php.floatVal = function(val) return { type = "float", val = val } end
php.intVal = function(val) return { type = "int", val = val } end
php.nullVal = { type = "null" }
php.stringVal = function(val) return { type = "string", val = val } end
php.trueVal = { type = "bool", val = true }

php.echo = io.write
php.isset = function(...)
  if ... == nil then return false end
  for _, v in ipairs(...) do
    if v == nil or v.val.type == "null" then return false end
  end
  return true
end
php.postDec = function(val)
  ret = val.val
  val.val = val.val - 1
  return ret
end
php.postInc = function(val)
  ret = val.val
  val.val = val.val + 1
  return ret
end

return php