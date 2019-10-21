// All code in this file taken and modified from https://jsfiddle.net/taditdash/hkjpzjuj/
    let textFile = null,
        makeTextFile = function (text) {
            let data = new Blob([text], {
                type: 'text/plain'
            });

            // If we are replacing a previously generated file we need to
            // manually revoke the object URL to avoid memory leaks.
            if (textFile !== null) {
                window.URL.revokeObjectURL(textFile);
            }

            textFile = window.URL.createObjectURL(data);

            return textFile;
        };
function makeFileAjax(){
    const monthnum = Number(month.indexOf(document.getElementById("month").textContent)+1);
    const year = Number(document.getElementById("year").textContent);
    const token = String(document.getElementById("token").value);
    const data = { 'monthnum': monthnum, 'year': year, 'token': token };
    fetch("makefile_ajax.php", {
        method: 'POST',
        body: JSON.stringify(data),
        headers: { 'content-type': 'application/json' }
    })
    .then(res => res.json())
    .then(response => {document.getElementById('downloadlink').href = makeTextFile(fileParser(JSON.stringify(response)))})
    .catch(error => console.error('Error:',error))
}
function fileParser(entr){
    var jsonData = JSON.parse(entr);
    let eventArray = [];
    // If statement found on https://stackoverflow.com/questions/18884249/checking-whether-something-is-iterable/32538867
    if (typeof jsonData[Symbol.iterator] === 'function'){
        for (let item of jsonData){
            if(eventArray[item.day]==undefined){
                eventArray[item.day] = "";
            }
            if(item.time1 > 12){
                if(item.time2 < 10){
                    eventArray[item.day] += 
                    `${item.time1-12}:0${item.time2} PM: ${item.event}
                    
            `;
                }
                else{
                    eventArray[item.day] += 
                    `${item.time1-12}:${item.time2} PM: ${item.event}

            `;
                }
            }
            else{
                if(item.time2 < 10){
                    eventArray[item.day] += 
                    `${item.time1}:0${item.time2} AM: ${item.event}

            `;
                }
                else{
                    eventArray[item.day] += 
                    `${item.time1}:${item.time2} AM: ${item.event}
                    
            `;
                }
            }
        }                
    }
    let resp = "";
    eventArray.forEach(function(element){
        resp += `
        Day: ${eventArray.indexOf(element)}
            ${element}
        
        `;
    }
    );
    return `Events for ${document.getElementById("month").textContent} ${document.getElementById("year").textContent}
    
    ${resp}`;
}

    document.getElementById('create').addEventListener('click', function () {
        makeFileAjax();
        let link = document.getElementById('downloadlink');
        link.style.visibility = 'visible';
        document.getElementById("create").textContent = "Change your events this month? Click to update text file, then download again.";
    }, false);