// ajax.js

function registerAjax(event) {
    const createusername = String(document.getElementById("createusername").value); // Get the username from the form
    const createpassword = String(document.getElementById("createpassword").value); // Get the password from the form

    // Make a URL-encoded string for passing POST data:
    const data = { 'createusername': createusername, 'createpassword': createpassword };

    fetch("register_ajax.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {console.log(data.success ? "You've been registered!" : `You were not registered. ${data.message}`); alert(data.message)});
}

document.getElementById("signup_btn").addEventListener("click", registerAjax, false); // Bind the AJAX call to button click