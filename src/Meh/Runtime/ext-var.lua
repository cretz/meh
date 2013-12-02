
local ext = {
  apply = function(php)
    php.defineFuncs({}, {
      isset = function(...)
        if ... == nil then return php.boolVal(false) end
        for _, v in ipairs({...}) do
          if v == nil or v.type == "null" then return php.boolVal(false) end
        end
        return php.boolVal(true)
      end
    })
  end
}

return ext