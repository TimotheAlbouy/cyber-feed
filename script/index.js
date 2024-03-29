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
  // remove a message
  const display = document.getElementById("feedsContentMessage");
  display.innerHTML = "";
  display.className = "";
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
  // display a message
  const display = document.getElementById("feedsContentMessage");
  display.innerHTML = "Veuillez vous connecter.";
  display.className = "alert alert-primary";
}

/**
 * Add the feeds list HTML for the index page.
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

    const title = feedItem.querySelector("a");
    title.innerText = feed.title;
    title.href = feed.link;

    const description = feedItem.querySelector("p");
    description.innerText = feed.description;

    feedsList.appendChild(feedItem);
  }

  message.innerHTML = "";
  message.className = "";
}