document.addEventListener("DOMContentLoaded", () => {
  if (getToken())
    switchToConnected();
  else switchToNotConnected();
});

function switchToConnected() {
  // show and hide the proper navbar buttons
  document.getElementById("connectedNavButtons").style.display = "inherit";
  document.getElementById("notConnectedNavButtons").style.display = "none";
  // start refreshing the feeds
  refreshFeeds();
  //setInterval() blabla
  // display the list of feed URLs in the proper modal
  displayFeedsList();
}

function switchToNotConnected() {
  // show and hide the proper navbar buttons
  document.getElementById("connectedNavButtons").style.display = "none";
  document.getElementById("notConnectedNavButtons").style.display = "inherit";
  // clear the list of feeds
  document.getElementById("feeds").innerHTML = "";
}