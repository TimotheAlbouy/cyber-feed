function updateSettings() {
  // handle the newsPerPage input
  const newsPerPage = document.getElementById("newsPerPage");
  const nb = parseInt(newsPerPage.value);
  setNewsPerPage(nb);
  // handle the refreshInterval input
  startRefreshing();
  // refresh the list of feeds
  refreshFeedsContent();
}

/**
 * Update the refresh interval according to the value of the input.
 */
function startRefreshing() {
  stopRefreshing();
  const refreshInterval = document.getElementById("refreshInterval");
  const duration = parseInt(refreshInterval.value);
  if (duration > 0) {
    const intervalId = setInterval(refreshFeedsContent, duration * 1000);
    setIntervalId(intervalId);
  }
}

/**
 * Clear the current interval if there is one.
 */
function stopRefreshing() {
  const intervalId = getIntervalId();
  if (intervalId) {
    clearInterval(intervalId);
    setIntervalId(0);
  }
}