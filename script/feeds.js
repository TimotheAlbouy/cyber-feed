/*
 * Refresh the current feeds.
 */
function refreshFeeds() {
  const message = document.getElementById("feedsContentMessage");
  const token = getToken();
  const headers = {
    "Authorization": token,
  };
  apiRequest("GET", "get_feeds_content.php", null, headers, res => {
    const jsonRes = JSON.parse(res.responseText);
    const feedsList = document.getElementById("feedsContent");
    const template = document.getElementById("feedsContentItem");
    for (const feed of jsonRes.feeds) {
      const feedItem = document.importNode(template.content, true);
      
      const image = feedItem.querySelector("img");
      image.src = feed.imageUrl;
      image.type = feed.imageType;

      const title = feedItem.querySelector("a");
      title.innerText = feed.title;
      title.href = feed.link;

      const description = feedItem.querySelector("p");
      description.innerText = feed.description;

      feedsList.appendChild(feedItem);
      message.innerHTML = "";
      message.className = "";
    }
  }, err => {
    if (err.status === 401) switchToNotConnected();
    else handleRequestError("Erreur lors de la récupération des flux.", message);
  });
}

/*
 * Display the current list of feeds.
 */
function displayFeedsList() {
  const message = document.getElementById("feedsUrlMessage");
  const headers = {"Authorization": getToken()};
  const feedsList = document.getElementById("feedsUrl");
  const template = document.getElementById("feedsUrlItem");
  apiRequest("GET", "get_feeds_url.php", null, headers, res => {
    const jsonRes = JSON.parse(res.responseText);
    for (const feed of jsonRes.feeds)
      addFeedToList(feed.id, feed.url, feedsList,template);
    message.innerHTML = "";
    message.className = "";
  }, err => {
    if (err.status === 401) switchToNotConnected();
    else handleRequestError("Erreur lors de la récupération des flux.", message);
  });
}

function addFeed() {
  const message = document.getElementById("feedsUrlMessage");
  const headers = {"Authorization": getToken()};
  const params = {url: document.getElementById("newFeedUrl").value};
  const feedsList = document.getElementById("feedsUrl");
  const template = document.getElementById("feedsUrlItem");
  apiRequest("POST", "add_feed.php", params, headers, res => {
    const feed = JSON.parse(res.responseText);
    addFeedToList(feed.id, feed.url, feedsList, template);
    message.innerHTML = "Flux créé.";
    message.className = "alert alert-success";
  }, err => {
    if (err.status === 401) switchToNotConnected();
    else handleRequestError("Erreur lors de la création du flux.", message);
  });
}
//https://www.ouest-france.fr/rss-en-continu.xml


function addFeedToList(feedId, feedUrl, feedsList, template) {
  const message = document.getElementById("feedsUrlMessage");
  const headers = {"Authorization": getToken()};
  const feedItem = document.importNode(template.content, true).querySelector("li");
  const url = feedItem.querySelector("a");
  url.innerText = feedUrl;
  url.href = feedUrl;

  const deleteFeed = feedItem.querySelector("button");
  deleteFeed.onclick = function() {
    apiRequest("DELETE", "delete_feed.php?id="+feedId, null, headers, res => {
      const item = document.getElementById("feedItem"+feedId);
      item.remove();
      message.innerHTML = "Flux supprimé.";
      message.className = "alert alert-success";
    }, err => {
      if (err.status === 401) switchToNotConnected();
      else handleRequestError("Erreur lors de la suppression du flux.", message);
    });
  };
  feedItem.id = "feedItem"+feedId;
  feedsList.appendChild(feedItem);
}