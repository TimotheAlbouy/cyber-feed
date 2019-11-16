document.addEventListener("DOMContentLoaded", () => {
  if (getToken())
    switchToConnected();
  else switchToNotConnected();
});

/**
 * Switch the page mode to connected.
 */
function switchToConnected() {
  // show and hide the proper navbar sections
  document.getElementById("connectedNav").style.display = "inherit";
  document.getElementById("notConnectedNav").style.display = "none";
  // display the welcome message
  document.getElementById("welcomeUser").innerHTML = "Bienvenue <b>" + getUsername() + "</b>";
  // start refreshing the feeds
  refreshFeedsContent();
  // set the initial settings
  updateSettings();
  // display the list of feed URLs in the proper modal
  displayFeedsUrlList();
}

/**
 * Switch the page mode to not connected.
 */
function switchToNotConnected() {
  // show and hide the proper navbar sections
  document.getElementById("connectedNav").style.display = "none";
  document.getElementById("notConnectedNav").style.display = "inherit";
  // remove the welcome message
  document.getElementById("welcomeUser").innerHTML = "";
  // stop the fetching of the feeds
  stopRefreshing();
  // delete the data stored in the local storage
  setToken("");
  setUsername("");
  setIsAdmin(false);
  // clear the list of feeds content
  document.getElementById("feedsContent").innerHTML = "";
  // clear the list of feeds URL
  document.getElementById("feedsUrl").innerHTML = "";
}