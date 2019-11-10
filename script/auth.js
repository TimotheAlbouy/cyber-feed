/**
 * Log-in using the provided `username` and `password`.
 */
function login() {
  const message = document.getElementById("loginMessage");
  const username = document.getElementById("loginUsername").value;
  const password = document.getElementById("loginPassword").value;
  const params = {
    username: username,
    password: password
  };
  
  apiRequest("POST", "login.php", params, null, res => {
    const jsonRes = JSON.parse(res.responseText);
    setToken(jsonRes.token);
    $('#loginModal').modal('hide');
    message.innerHTML = "";
    message.className = "";
    switchToConnected();
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