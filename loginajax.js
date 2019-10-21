// ajax.js

function loginAjax(event) {
    const username = String(document.getElementById("username").value); // Get the username from the form
    const password = String(document.getElementById("password").value); // Get the password from the form

    // Make a URL-encoded string for passing POST data:
    const data = { 'username': username, 'password': password };
    fetch("login_ajax.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {console.log(data.success ? "You've been logged in!" : `You were not logged in. ${data.message}`); if(!data.success){alert(data.message)}else{document.getElementById('token').setAttribute("value", data.token)}});
    
}
document.getElementById("login_btn").addEventListener("click", loginAjax, false);