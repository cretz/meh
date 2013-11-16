local php = {}

php.falseVal = { type = 'bool', val = false }
php.floatVal = function(val) return { type = 'float', val = val } end
php.intVal = function(val) return { type = 'int', val = val } end
php.nullVal = { type = 'null' }
php.stringVal = function(val) return { type = 'string', val = val } end
php.trueVal = { type = 'bool', val = true }

return php