// Code in this file taken/modified from "Logging in a User" section of AJAX class wiki
function registerAjax(event) {
    const createusername = String(document.getElementById("createusername").value);
    const createpassword = String(document.getElementById("createpassword").value);

    const data = { 'createusername': createusername, 'createpassword': createpassword };

    fetch("register_ajax.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {console.log(data.success ? "You've been registered!" : `You were not registered. ${data.message}`); alert(data.message)});
}

document.getElementById("signup_btn").addEventListener("click", registerAjax, false);