
local ext = {
  apply = function(php)
    local error_reporting
    php.error_reporting = function(val)
      local prev = error_reporting
      error_reporting = val
      return prev
    end
  end
}

return ext