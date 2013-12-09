local bit = require("bit")
local io = require("io")
local util = require("util")

local arrays = {}

arrays.current = function(arr)
  if arr.cursor > #arr.idxd then return nil end
  return arr.hash[arr.idxd[arr.cursor]].val
end
arrays.getLastNum = function(arr)
  -- Not nil, just return
  if arr.lastNum ~= nil then return arr.lastNum end
  -- Otherwise, just loop through idxd
  arr.lastNum = -1
  for _, k in ipairs(arr.idxd) do
    if type(k) == "number" and k > arr.lastNum then arr.lastNum = k end
  end
  return arr.lastNum
end
arrays.key = function(arr)
  if arr.cursor > #arr.idxd then return nil end
  return arr.idxd[arr.cursor]
end
arrays.new = function()
  return {
    -- Normal 1-based lua array, value is the key of the hash
    idxd = { },
    -- Normal hash (or 0-based if PHP indexed array)
    -- The value is a table of { idx = ?, val = ? } where idx corresponds to index of above array
    hash = { },
    -- The current index of idxd array
    cursor = 1,
    -- The last integer added, nil means it has to be figured out
    lastNum = -1
  }
end
arrays.next = function(arr)
  if arr.cursor > #arr.idxd then return nil end
  arr.cursor = arr.cursor + 1
  return arrays.current(arr)
end
arrays.reset = function(arr) arr.cursor = 1 end
arrays.set = function(arr, key, val)
  -- No key means increment int, add to end
  if key == nil then
    arr.lastNum = arrays.getLastNum(arr) + 1
    key = arr.lastNum
  else
    -- If there's an existing value in the hash, remove it
    arrays.unset(arr, key)
    -- TODO: support floats, numeric strings, etc
    -- If its an int > last num, update last num
    if key.type == "int" and key.val > arrays.getLastNum(arr) then arr.lastNum = key.val end
    key = key.val
  end
  -- Add to idxd w/ the newest key
  arr.idxd[#arr.idxd + 1] = key
  -- Now hash value
  -- TODO copies and stuff
  arr.hash[key] = { idx = #arr.idxd, val = val }
end
arrays.unset = function(arr, key)
  -- Must be in hash TODO: key coercion
  local v = arr.hash[key.val]
  if v == nil then return end
  -- Remove the hash/idxd val and nilify the lastNum
  arr.hash[key.val] = nil
  table.remove(arr.idxd, v.idx)
  arr.lastNum = nil
  -- No need to reset cursor, if its now past the end, so what
end

return arrays