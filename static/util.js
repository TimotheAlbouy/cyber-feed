function httpRequest(method, url, params, headers, success, error) {
  let req = new XMLHttpRequest();
  
  req.onreadstatechange = function(event) {
    if (this.readyState === XMLHttpRequest.DONE) {
      if (Math.floor(this.status/100) === 2)
        success(this);
      else error(this);
    }
  };

  if (headers !== null) {
    for (let header of headers)
      req.setRequestHeader(header, headers[header]);
  }

  paramsStr = encodeParams(params);
  console.log(paramsStr);

  req.open(method, url, true);
  req.send(paramsStr);
}

function encodeParams(params) {
  let ret = "";
  if (params !== null) {
    for (let key of params)
      ret += key + "=" + params[key] + "&";
  }
  return ret;
}