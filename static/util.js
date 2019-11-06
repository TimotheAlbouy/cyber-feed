/**
 * Callback invoked to handle the HTTP response (whether it be a success
 * or an error) of the request sent by the `apiRequest` function.
 * @callback responseHandler
 * @param {XMLHttpRequest} request - the HTTP request object
 */

/**
 * Send a HTTP request to the Cyber-feed API.
 * @param {string} method - the method of the request
 * @param {string} route - the route of the API
 * @param {Object} params - the parameters to put in the body
 * @param {Object} headers - the additional headers
 * @param {responseHandler} success - the success response handler
 * @param {responseHandler} error - the error response handler
 */
function apiRequest(method, route, params, headers, success, error) {
  const req = new XMLHttpRequest();

  req.onreadystatechange = function() {
    if (req.readyState === XMLHttpRequest.DONE) {
      if (Math.floor(this.status/100) === 2) // If status code starts with '2', success
        success(this);
      else error(this); // Else, error
    }
  };

  const url = "/cyber-feed/api/" + route;
  const paramsStr = encodeParams(params);
  req.open(method, url, true);

  if (headers !== null) {
    for (const header in headers)
      req.setRequestHeader(header, headers[header]);
  }
  req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  req.send(paramsStr);
}

/**
 * Encode the parameters following the x-www-form-urlencoded standard.
 * @param {Object} params - the list of parameters 
 * @returns {string} the encoded parameters
 */
function encodeParams(params) {
  let ret = "";
  if (params !== null) {
    for (let key in params)
      ret += key + "=" + params[key] + "&";
  }
  return ret;
}

/**
 * Retrieve the token from the session storage.
 * @returns {string} the token
 */
function getToken() {
  return localStorage.getItem("cyber-feed-api-token");
}

/**
 * Store the token in the session storage.
 * @param {string} token - the token
 */
function setToken(token) {
  localStorage.setItem("cyber-feed-api-token", token);
}