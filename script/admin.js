document.addEventListener("DOMContentLoaded", () => {
  if (getToken() && getIsAdmin())
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
  // display the list of users
  displayUsersList();
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
  // delete the data stored in the local storage
  setToken("");
  setUsername("");
  setIsAdmin(false);
  // clear the list of users
  document.getElementById("users").innerHTML = "";
}