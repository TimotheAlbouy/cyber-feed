/**
 * Add a listener in order to get token.
 */
document.addEventListener("DOMContentLoaded", () => {
  if (getToken())
    switchToConnected();
  else switchToNotConnected();
});
/*
 * Switch the page when an user is connecting
 * @display FeedsList
 */
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
/*
 * Switch the page when an user was connected and is not connecting.
 */
function switchToNotConnected() {
  // show and hide the proper navbar buttons
  document.getElementById("connectedNavButtons").style.display = "none";
  document.getElementById("notConnectedNavButtons").style.display = "inherit";
  // clear the list of feeds content
  document.getElementById("feedsContent").innerHTML = "";
  // clear the list of feeds URL
  document.getElementById("feedsUrl").innerHTML = "";
}