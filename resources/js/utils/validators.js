import { isEmpty, isEmptyArray, isNullOrUndefined } from './helpers'

export const requiredValidator = value => {
  if (isNullOrUndefined(value) || isEmptyArray(value) || value === false)
    return 'This field is required'

  return !!String(value).trim().length || 'This field is required'
}

export const emailValidator = value => {
  if (isEmpty(value))
    return true
  const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
  if (Array.isArray(value))
    return value.every(val => re.test(String(val))) || 'The Email field must be a valid email'

  return re.test(String(value)) || 'The Email field must be a valid email'
}

export const passwordValidator = password => {
  // const regExp = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%&*()]).{8,}/
  const regExp = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/
  const validPassword = regExp.test(password)

  return validPassword || 'Field must contain at least one uppercase, lowercase and digit with min 8 chars'
}

export const confirmedValidator = (value, target) => value === target || 'The Confirm Password field confirmation does not match'

