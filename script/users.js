/*
 * Display the list of user items.
 */
function displayUsersList() {
  const message = document.getElementById("usersMessage");
  const headers = {"Authorization": getToken()};
  apiRequest("GET", "get_users.php", null, headers, res => {
    const jsonRes = JSON.parse(res.responseText);
    for (const user of jsonRes.users)
      addUserToList(user.username, user.is_admin);
  }, err => handleRequestError(err.status, "Erreur lors de la récupération des utilisateurs.", message)
  );
}

/**
 * Add the user item to the HTML list.
 * @param {string} username - the username of the user
 * @param {boolean} isAdmin - true iff the user is admin
 */
function addUserToList(username, isAdmin) {
  const usersList = document.getElementById("users");
  const template = document.getElementById("userItem");
  const message = document.getElementById("usersMessage");
  const headers = {"Authorization": getToken()};
  const userItem = document.importNode(template.content, true).querySelector("li");
  const usernameItem = userItem.querySelector("span");
  const toggleIsAdmin = userItem.querySelector("input");
  const deleteUser = userItem.querySelector("button");
  usernameItem.innerText = username;

  //TODO: toggleIsAdmin
  
  deleteUser.onclick = () => {
    apiRequest("DELETE", "delete_user.php?id="+username, null, headers, res => {
      const item = document.getElementById("userItem-"+username);
      item.remove();
      message.innerHTML = "Utilisateur supprimé.";
      message.className = "alert alert-success";
    }, err => handleRequestError(err.status, "Erreur lors de la suppression de l'utilisateur.", message)
    );
  };
  userItem.id = "userItem-"+username;
  usersList.appendChild(userItem);
}