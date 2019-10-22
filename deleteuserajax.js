function deleteUser(event) {
    const token = String(document.getElementById("token").value);

    const data = { 'token': token };
    fetch("login_ajax.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {console.log(data.success ? "Your account has been deleted" : `Your account was not deleted. ${data.message}`); if(!data.success){alert(data.message)}else{logOut();}});
    
}