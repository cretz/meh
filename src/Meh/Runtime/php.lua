local bit = require("bit")
local io = require("io")
local util = require("util")

local php = {}

-- Namespace support
php.namespaces = { children = {} }
php.globalNs = php.namespaces
php.ns = function(...)
  local currNs = php.namespaces
  for _, v in ipairs({...}) do
    if currNs.children[v] == nil then
      currNs.children[v] = { children = {} }
    end
    currNs = currNs.children[v]
  end
  return currNs
end

-- Expects array of { key = ?, val = ? } w/ possible nil key
php.arrayVal = function(...) return php.call({ }, php.stringVal('array'), ...) end
php.boolVal = function(val) return { type = "bool", val = val } end
php.floatVal = function(val) return { type = "float", val = val } end
php.intVal = function(val) return { type = "int", val = val } end
php.nullVal = function() return { type = "null" } end
php.objectVal = function(val) return { type = "object", val = val } end
php.stringVal = function(val) return { type = "string", val = val } end

php.add = function(left, right)
  if left.type == "int" and right.type == "int" then return php.intVal(left.val + right.val) end
  error("Bad type, left: " .. left.type .. ", right: " .. right.type)
end
php.assign = function(val, newVal)
  -- TODO: copy arrays
  val.type = newVal.type
  val.val = newVal.val
  return val
end
php.bitAnd = function(left, right)
  if left.type == "int" and right.type == "int" then return php.intVal(bit.band(left.val, right.val)) end
  error("Bad type, left: " .. left.type .. ", right: " .. right.type)
end
php.bitOr = function(left, right)
  if left.type == "int" and right.type == "int" then return php.intVal(bit.bor(left.val, right.val)) end
  error("Bad type, left: " .. left.type .. ", right: " .. right.type)
end
php.call = function(ns, name, args)
  local n = php.ns(unpack(ns))
  if n.functions == nil or n.functions[name.val] == nil then error("Unknown func: " .. name.val) end
  return n.functions[name.val](unpack(args))
end
php.callMethod = function(inCls, var, name, args)
  if var.val == nil then error('Nil var') end
  -- Find the method up the stack after the inCls is found
  -- TODO: support ns inCls
  local v = var.val
  local inClsFound = inCls == nil
  while v ~= nil do
    if not inClsFound and v.meta.name == inCls then inClsFound = true end
    if inClsFound and v.methods ~= nil and v.methods[name.val] ~= nil then
      return v.methods[name.val](var, unpack(args))
    end
    v = v.parent
  end
  error("No method: " .. name.val)
end
php.concat = function(...)
  local r = ''
  for _, v in ipairs({...}) do
    if v.val ~= nil then r = r .. v.val end
  end
  return php.stringVal(r)
end
php.defineClass = function(ns, clsTbl)
  local n = php.ns(unpack(ns))
  if n.classes == nil then n.classes = { } end
  n.classes[clsTbl.name] = clsTbl
end
php.defineFuncs = function(ns, fnTbl)
  local n = php.ns(unpack(ns))
  if n.functions == nil then n.functions = fnTbl
  else
    for k, v in pairs(fnTbl) do n.functions[k] = v end
  end
end
php.defineMethod = function(methTbl)
  return methTbl
end
php.defineProperty = function(propTbl)
  return propTbl
end
php.div = function(left, right)
  if left.type == "int" and right.type == "int" then return php.intVal(left.val / right.val) end
  error("Bad type, left: " .. left.type .. ", right: " .. right.type)
end
php.eq = function(left, right)
  if (left.type == "int" or left.type == "float") and
          (right.type == "int" or right.type == "float") then return php.boolVal(left.val == right.val)
  elseif left.type == "string" and right.type == "string" then return php.boolVal(left.val == right.val) end
  error("Bad type, left: " .. left.type .. ", right: " .. right.type)
end
php.fetchProperty = function(inCls, var, name)
  if var.val == nil then error('Nil var') end
  -- Find the property up the stack after inCls found
  local v = var.val
  local inClsFound = inCls == nil
  while v ~= nil do
    if not inClsFound and v.meta.name == inCls then inClsFound = true end
    if inClsFound and v.properties ~= nil and v.properties[name.val] ~= nil then
      return v.properties[name.val]
    end
    v = v.parent
  end
  error("No property: " .. name.val)
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
php.mult = function(left, right)
  if left.type == "int" and right.type == "int" then return php.intVal(left.val * right.val) end
  error("Bad type, left: " .. left.type .. ", right: " .. right.type)
end
php.new = function(ns, clsName, ...)
  local initInst
  initInst = function(ns, clsName)
    local n = php.ns(unpack(ns))
    -- Get class
    if n.classes == nil or n.classes[clsName] == nil then
      error('No class for ' .. clsName)
    end
    -- Instances have properties, methods, and parent
    local i = { meta = n.classes[clsName] }
    -- Parent (TODO: namespaces)
    if i.meta.parent ~= nil then
      i.parent = initInst({ }, i.meta.parent)
    end
    -- Properties
    if i.meta.properties ~= nil then
      i.properties = { }
      for k, v in pairs(i.meta.properties) do
        i.properties[k] = v.default
      end
    end
    -- Methods
    if i.meta.methods ~= nil then
      i.methods = { }
      for k, v in pairs(i.meta.methods) do
        i.methods[k] = v.__invoke
      end
    end
    return i
  end
  -- Init inst
  local i = php.objectVal(initInst(ns, clsName.val))
  -- Construct?
  if i.val.methods ~= nil and i.val.methods.__construct ~= nil then
    php.callMethod(i, '__construct', ...)
  end
  return i
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
php.staticNs = function(...)
  local ns = php.ns(unpack(...))
  if ns.statics == nil then ns.statics = {} end
  return ns.statics
end
php.sub = function(left, right)
  if left.type == "int" and right.type == "int" then return php.intVal(left.val - right.val) end
  error("Bad type, left: " .. left.type .. ", right: " .. right.type)
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
php.varCtx = function(parent)
  local self = setmetatable({}, php.VarCtx)
  if parent == nil then self.__parent = -1
  else self.__parent = parent end
  return self
end
php.nsVarCtx = function(...)
  local ns = php.ns(unpack(...))
  if ns.variables == nil then ns.variables = php.varCtx() end
  return ns.variables
end

-- Extensions (hardcoded for now)
require("ext-array").apply(php)
require("ext-errorfunc").apply(php)
require("ext-strings").apply(php)
require("ext-var").apply(php)

return php