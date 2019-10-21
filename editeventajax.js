// Code in this file taken/modified from "Logging in a User" section of AJAX class wiki
function editEventAjax(event) {
    const eventcontentold = String(document.getElementById("eventcontent3").value);
    const dateold = String(document.getElementById("date3").value);
    const timeold = String(document.getElementById("time3").value);
    const eventcontentnew = String(document.getElementById("eventcontent4").value);
    const datenew = String(document.getElementById("date4").value);
    const timenew = String(document.getElementById("time4").value);
    const token = String(document.getElementById("token").value);

    const data = { 'eventcontentold': eventcontentold, 'dateold': dateold, 'timeold': timeold, 'token': token, 'eventcontentnew': eventcontentnew, 'datenew': datenew, 'timenew': timenew };

    fetch("editevent_ajax.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {console.log(data.success ? "Your event has been modified." : `Your event has not been modified. ${data.message}`); alert(data.message)});
}