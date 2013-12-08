local io = require("io")

local ext = {
  apply = function(php)
    php.defineFuncs({}, {
      echo = function(...)
        for _, v in ipairs({...}) do
          if v.val ~= nil then io.write(v.val) end
        end
      end,
      print = function(arg)
        io.write(arg.val)
        return 1
      end
    })
  end
}

return ext