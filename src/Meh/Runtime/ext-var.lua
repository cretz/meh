local io = require("io")

local ext = {
  apply = function(php)

    local var_dump = function(...)
      for _, v in pairs({...}) do
        if v.type == "string" then
          io.write("string(" .. v.val:len() .. ') "' .. v.val .. '"\n')
        elseif v.type == "int" then
          io.write("int(" .. v.val .. ")\n")
        elseif v.type == "bool" then
          if v.val then io.write("bool(true)\n") else io.write("bool(false)\n") end
        elseif v.type == "null" then io.write("NULL\n")
        else error("Unknown type: " .. v.type) end
      end
    end

    php.defineFuncs({}, {
      isset = function(...)
        if ... == nil then return php.boolVal(false) end
        for _, v in ipairs({...}) do
          if v == nil or v.type == "null" then return php.boolVal(false) end
        end
        return php.boolVal(true)
      end,
      var_dump = var_dump
    })
  end
}

return ext