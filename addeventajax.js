function addEventAjax(event) {
    const eventcontent = document.getElementById("eventcontent1").value;
    const date = String(document.getElementById("date1").value);
    const time = String(document.getElementById("time1").value);
    const token = document.getElementById("token").value;
    // Make a URL-encoded string for passing POST data:
    const data = { 'eventcontent': eventcontent, 'date': date, 'time': time, 'token': token };

    fetch("addevent_ajax.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {console.log(data.success ? "Your event has been added." : `Your event has not been added. ${data.message}`); alert(data.message)})
}