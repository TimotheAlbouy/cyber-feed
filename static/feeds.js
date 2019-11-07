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
    message.innerHTML = "Erreur lors de la récupération des flux.";
    message.className = "alert alert-danger";
  });
}
/*
 * Display the current list of feeds.
 */
function displayFeedsList() {
  const message = document.getElementById("feedsUrlMessage");
  const token = getToken();
  const headers = {
    "Authorization": token,
  };
  apiRequest("GET", "get_feeds_url.php", null, headers, res => {
    const jsonRes = JSON.parse(res.responseText);
    const feedsList = document.getElementById("feedsUrl");
    const template = document.getElementById("feedsUrlItem");
    for (const feed of jsonRes.feeds) {
      const feedItem = document.importNode(template.content, true);

      const url = feedItem.querySelector("a");
      url.innerText = feed.url;
      url.href = feed.url;

      const deleteFeed = feedItem.querySelector("button");
      //deleteFeed.onclick = deleteFeed(i);

      feedsList.appendChild(feedItem);
      message.innerHTML = "";
      message.className = "";
    }
  }, err => {
    message.innerHTML = "Erreur lors de la récupération des flux.";
    message.className = "alert alert-danger";
  });
}