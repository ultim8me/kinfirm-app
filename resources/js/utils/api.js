import { ofetch } from 'ofetch'
import { isNullOrUndefined } from "./helpers";

const client = ofetch.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || '/api/',
  async onRequest({ request, options }) {
    const accessToken = useCookie('accessToken').value
    options.headers = {
      ...options.headers,
      Accept: "application/json",
    }
    if (accessToken) {
      options.headers = {
        ...options.headers,
        Authorization: `Bearer ${accessToken}`,
      }
    }
    console.log("[fetch request]", request, options);
  },
  async onResponseError({ request, response, options }) {
    console.log(
      "[fetch response error]",
      request,
      response.status,
      response.body
    );
  },
})

export const $api = (url, method = 'GET', body, onResponseError) => {

  let options = {
    method: method
  }

  if (!isNullOrUndefined(onResponseError)) {
    options.onResponseError = onResponseError
  }

  if (!isNullOrUndefined(body)) {
    options.body = body
  }

  return client(url, options)
}
