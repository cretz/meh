
local ext = {
  apply = function(php)
    local error_reporting_val = 0
    php.defineConst({}, 'E_ALL', 123) -- TODO: fix
    php.defineFuncs({}, {
      error_reporting = function(val)
        local prev = error_reporting_val
        error_reporting_val = val
        return prev
      end
    })
  end
}

return ext