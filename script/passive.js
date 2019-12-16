document.addEventListener("DOMContentLoaded", () => {
  if (getToken())
    switchToConnected();
  else switchToNotConnected();
});

/**
 * Switch the page mode to connected.
 */
function switchToConnected() {
  // start refreshing the feeds
  refreshFeedsContent();
  // set the initial settings
  updateSettings();
  // remove a message
  const display = document.getElementById("feedsContentMessage");
  display.innerHTML = "";
  display.className = "";
}

/**
 * Switch the page mode to not connected.
 */
function switchToNotConnected() {
  // stop the fetching of the feeds
  stopRefreshing();
  // delete the data stored in the local storage
  setToken("");
  setUsername("");
  setIsAdmin(false);
  // clear the list of feeds content
  document.getElementById("feedsContent").innerHTML = "";
  // display a message
  const display = document.getElementById("feedsContentMessage");
  display.innerHTML = "Veuillez vous connecter.";
  display.className = "alert alert-primary";
}

/**
 * Add the feeds list HTML for the passive page.
 * @param {object} jsonRes - the JSON object response
 */
function addFeeds(jsonRes) {
  const message = document.getElementById("feedsContentMessage");
  const feedsList = document.getElementById("feedsContent");
  const template = document.getElementById("feedsContentItem");
  feedsList.innerHTML = "";

  for (const feed of jsonRes.feeds) {
    const feedItem = document.importNode(template.content, true);
    
    const image = feedItem.querySelector("img");
    if (feed.hasOwnProperty("img"))
      image.src = feed.img;
    else image.remove();

    const title = feedItem.querySelector("h4");
    title.innerText = feed.title;

    feedsList.appendChild(feedItem);
  }

  feedsList.querySelector(":first-child").classList.add("active");
  message.innerHTML = "";
  message.className = "";
}