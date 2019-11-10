document.addEventListener("DOMContentLoaded", () => {
  if (getToken())
    switchToConnected();
  else switchToNotConnected();
});

/**
 * Switch the page mode to connected.
 * @display FeedsList
 */
function switchToConnected() {
  // show and hide the proper navbar buttons
  document.getElementById("connectedNavButtons").style.display = "inherit";
  document.getElementById("notConnectedNavButtons").style.display = "none";
  // start refreshing the feeds
  refreshFeeds();
  // refresh the page every 10 minutes
  setInterval(refreshFeeds, 600000);
  // display the list of feed URLs in the proper modal
  displayFeedsList();
}

/**
 * Switch the page mode to not connected.
 */
function switchToNotConnected() {
  // show and hide the proper navbar buttons
  document.getElementById("connectedNavButtons").style.display = "none";
  document.getElementById("notConnectedNavButtons").style.display = "inherit";
  // delete the access token
  setToken("");
  // clear the list of feeds content
  document.getElementById("feedsContent").innerHTML = "";
  // clear the list of feeds URL
  document.getElementById("feedsUrl").innerHTML = "";
}

function handleRequestError(errorText, messageDisplay) {
  if (err.status === 401) switchToNotConnected();
  else {
    messageDisplay.innerHTML = errorText;
    messageDisplay.className = "alert alert-danger";
  }
}