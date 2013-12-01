local io = require("io")

local php = {}

php.globals = {}

php.boolVal = function(val) return { type = "bool", val = val } end
php.floatVal = function(val) return { type = "float", val = val } end
php.intVal = function(val) return { type = "int", val = val } end
php.nullVal = function() return { type = "null" } end
php.stringVal = function(val) return { type = "string", val = val } end

php.add = function(left, right)
  if left.type == "int" and right.type == "int" then return php.intVal(left.val + right.val) end
  error("Bad type, left: " .. left.type .. ", right: " .. right.type)
end
php.assign = function(val, newVal)
  val.type = newVal.type
  val.val = newVal.val
  return val
end
php.concat = function(...)
  local r = ''
  for _, v in ipairs({...}) do
    if v.val ~= nil then r = r .. v.val end
  end
  return php.stringVal(r)
end
php.echo = function(...)
  for _, v in ipairs({...}) do
    if v.val ~= nil then io.write(v.val) end
  end
end
php.eq = function(left, right)
  if (left.type == "int" or left.type == "float") and
          (right.type == "int" or right.type == "float") then return php.boolVal(left.val == right.val)
  elseif left.type == "string" and right.type == "string" then return php.boolVal(left.val == right.val) end
  error("Bad type, left: " .. left.type .. ", right: " .. right.type)
end
php.gt = function(left, right)
  if (left.type == "int" or left.type == "float") and
          (right.type == "int" or right.type == "float") then return php.boolVal(left.val > right.val) end
  error("Bad type, left: " .. left.type .. ", right: " .. right.type)
end
php.gte = function(left, right)
  local v = php.eq(left, right)
  if v.val then return php.boolVal(true) end
  return php.gt(left, right)
end
php.isFalse = function(val)
  if val.type == "bool" then return val.val == false
  elseif val.type == "int" then return val.val == 0
  elseif val.type == "float" then return val.val == 0.0
  elseif val.type == "string" then return val.val == "" or val.val == "0"
  elseif val.type == "null" then return true
  else error("Bad type: " .. val.type) end
end
php.isset = function(...)
  if ... == nil then return php.boolVal(false) end
  for _, v in ipairs({...}) do
    if v == nil or v.type == "null" then return php.boolVal(false) end
  end
  return php.boolVal(true)
end
php.isTrue = function(val)
  return not php.isFalse(val)
end
php.lt = function(left, right)
  if (left.type == "int" or left.type == "float") and
          (right.type == "int" or right.type == "float") then return php.boolVal(left.val < right.val) end
  error("Bad type, left: " .. left.type .. ", right: " .. right.type)
end
php.lte = function(left, right)
  local v = php.eq(left, right)
  if v.val then return php.boolVal(true) end
  return php.lt(left, right)
end
php.postDec = function(val)
  ret = php.intVal(val.val)
  val.val = val.val - 1
  return ret
end
php.postInc = function(val)
  ret = php.intVal(val.val)
  val.val = val.val + 1
  return ret
end

-- Special variable context class
php.VarCtx = {
  __index = function(t, k)
    if t.__parent ~= -1 then return t.__parent[k] end
    local v = php.nullVal()
    t[k] = v
    return v
  end
}
function php.VarCtx.__new(parent)
  local self = setmetatable({}, php.VarCtx)
  if parent == nil then self.__parent = -1
  else self.__parent = parent end
  return self
end

-- Extensions (hardcoded for now)
local ext = require("ext-errorfunc")
ext.apply(php)

return php