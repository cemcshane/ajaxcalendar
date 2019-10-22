// Code in this file taken from "Logging in a User" section of AJAX class wiki
function loginAjax(event) {
    const username = String(document.getElementById("username").value);
    const password = String(document.getElementById("password").value);

    const data = { 'username': username, 'password': password };
    fetch("login_ajax.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {console.log(data.success ? "You've been logged in!" : `You were not logged in. ${data.message}`); if(!data.success){alert(data.message)}else{document.getElementById('token').setAttribute("value", data.token)}});
    
}