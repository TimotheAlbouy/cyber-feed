function apiRequest(method, route, params, headers, success, error) {
  const req = new XMLHttpRequest();
  
  req.onreadstatechange = function(event) {
    if (this.readyState === XMLHttpRequest.DONE) {
      if (Math.floor(this.status/100) === 2)
        success(this);
      else error(this);
    }
  };

  const paramsStr = encodeParams(params);
  const url = "localhost/cyber-feed/api/" + route;

  req.open(method, url, true);

  if (headers !== null) {
    for (const header in headers)
      req.setRequestHeader(header, headers[header]);
  }
  req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  req.send(paramsStr);
}

function encodeParams(params) {
  let ret = "";
  if (params !== null) {
    for (let key in params)
      ret += key + "=" + params[key] + "&";
  }
  return ret;
}

function retrieveToken() {
  return sessionStorage.getItem("cyber-feed-api-token");
}