function deleteEventAjax(event) {
    const eventcontent = String(document.getElementById("eventcontent2").value);
    const date = String(document.getElementById("date2").value);
    const time = String(document.getElementById("time2").value);
    const token = String(document.getElementById("token").value);
    // Make a URL-encoded string for passing POST data:
    const data = { 'eventcontent': eventcontent, 'date': date, 'time': time, 'token': token };

    fetch("deleteevent_ajax.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {console.log(data.success ? "Your event has been deleted." : `Your event has not been deleted. ${data.message}`); alert(data.message)});
}