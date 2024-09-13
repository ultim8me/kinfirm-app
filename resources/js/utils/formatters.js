import { isNullOrUndefined } from './helpers'

export const ucFirst = string => {
  if (isNullOrUndefined(string))
    return ''
  return string.charAt(0).toUpperCase() + string.slice(1)
}

export const limit = (string, len = 75, ending = '...') => {
  if (isNullOrUndefined(string))
    return ''

  if (string?.length <= len)
    return string

  return string.substring(0, len) + ending
}
