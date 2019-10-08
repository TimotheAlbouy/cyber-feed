function httpRequest(method, url, params, headers, success, error) {
  let req = XMLHttpRequest();
  
  req.onreadstatechange = function(event) {
    if (this.readyState === XMLHttpRequest.DONE) {
      if (Math.floor(this.status/100) === 2)
        success(this);
      else error(this);
    }
  };

  for (let header of headers)
    req.setRequestHeader(header, headers[header]);

  req.open(method, url, true);
  req.send(params);
}