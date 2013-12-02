local io = require("io")

local ext = {
  apply = function(php)
    php.defineFuncs({}, {
      echo = function(...)
        for _, v in ipairs({...}) do
          if v.val ~= nil then io.write(v.val) end
        end
      end
    })
  end
}

return ext