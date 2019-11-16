/*
 * Refresh the feed content items.
 */
function refreshFeedsContent() {
  const message = document.getElementById("feedsContentMessage");
  const token = getToken();
  const headers = {
    "Authorization": token,
  };
  apiRequest("GET", "get_feeds_content.php", null, headers, res => {
    const jsonRes = JSON.parse(res.responseText);
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
      message.innerHTML = "";
      message.className = "";
    }
  }, err => handleRequestError(err.status, "Erreur lors de la récupération des flux.", message)
  );
}

/*
 * Display the current list of feed URL items.
 */
function displayFeedsUrlList() {
  const message = document.getElementById("feedsUrlMessage");
  const headers = {"Authorization": getToken()};
  apiRequest("GET", "get_feeds_url.php", null, headers, res => {
    const jsonRes = JSON.parse(res.responseText);
    for (const feed of jsonRes.feeds)
      addFeedUrlToList(feed.id, feed.url);
    message.innerHTML = "";
    message.className = "";
  }, err => handleRequestError(err.status, "Erreur lors de la récupération des flux.", message)
  );
}

/**
 * Add the feed URL item to the database.
 */
function addFeedUrl() {
  const message = document.getElementById("feedsUrlMessage");
  const headers = {"Authorization": getToken()};
  const params = {url: document.getElementById("newFeedUrl").value};
  apiRequest("POST", "add_feed.php", params, headers, res => {
    const feed = JSON.parse(res.responseText);
    addFeedUrlToList(feed.id, feed.url);
    refreshFeedsContent();
    message.innerHTML = "Flux créé.";
    message.className = "alert alert-success";
  }, err => handleRequestError(err.status, "Erreur lors de la création du flux.", message)
  );
}

/**
 * Add the feed URL item to the HTML list.
 * @param {string} id - the ID of the feed
 * @param {string} url - the URL of the feed
 */
function addFeedUrlToList(id, url) {
  const feedsList = document.getElementById("feedsUrl");
  const template = document.getElementById("feedsUrlItem");
  const message = document.getElementById("feedsUrlMessage");
  const headers = {"Authorization": getToken()};
  const feedItem = document.importNode(template.content, true).querySelector("li");
  const urlItem = feedItem.querySelector("a");
  const deleteFeed = feedItem.querySelector("button");
  urlItem.innerText = url;
  urlItem.href = url;

  deleteFeed.onclick = () => {
    apiRequest("DELETE", "delete_feed.php?id="+id, null, headers, res => {
      const item = document.getElementById("feedItem-"+id);
      item.remove();
      refreshFeedsContent();
      message.innerHTML = "Flux supprimé.";
      message.className = "alert alert-success";
    }, err => handleRequestError(err.status, "Erreur lors de la suppression du flux.", message)
    );
  };
  feedItem.id = "feedItem-"+id;
  feedsList.appendChild(feedItem);
}