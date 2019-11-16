/**
 * Log-in using the provided `username` and `password`.
 */
function login(admin=false) {
  const message = document.getElementById("loginMessage");
  const username = document.getElementById("loginUsername").value;
  const password = document.getElementById("loginPassword").value;
  const params = {
    username: username,
    password: password
  };
  
  apiRequest("POST", "login.php", params, null, res => {
    const jsonRes = JSON.parse(res.responseText);
    if (!admin || jsonRes.isAdmin) {
      setToken(jsonRes.token);
      setUsername(jsonRes.username);
      setIsAdmin(jsonRes.isAdmin);
      $('#loginModal').modal('hide');
      message.innerHTML = "";
      message.className = "";
      switchToConnected();
    } else {
      message.innerHTML = "Le compte ne possède pas les droits administrateur.";
      message.className = "alert alert-danger";
    }
  }, err => {
    message.innerHTML = "Echec de la connexion.";
    message.className = "alert alert-danger";
  });
}

/**
 * Register a new account using the provided `username` and `password`.
 */
function register() {
  const message = document.getElementById("registerMessage");
  const username = document.getElementById("registerUsername").value;
  const password = document.getElementById("registerPassword").value;
  const repeatPassword = document.getElementById("registerRepeatPassword").value;

  if (password === repeatPassword) {
    const params = {
      username: username,
      password: password
    };

    apiRequest("POST", "register.php", params, null, res => {
      const jsonRes = JSON.parse(res.responseText);
      setToken(jsonRes.token);
      setUsername(jsonRes.username);
      $('#registerModal').modal('hide');
      message.innerHTML = "";
      message.className = "";
      switchToConnected();
    }, err => {
     message.innerHTML = "Erreur lors de la création du compte.";
     message.className = "alert alert-danger";
    });
  } else {
    message.innerHTML = "Les mots de passe entrés ne correspondent pas.";
    message.className = "alert alert-danger";
  }
}