<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="calendar.css" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calendar</title>
</head>
<body>
    <script>
    // Everything inside this script group was taken from the Calendar library on the Module 5 page
        (function(){
            /* Date.prototype.deltaDays(n)
	        * 
	        * Returns a Date object n days in the future.
	        */
            Date.prototype.deltaDays=function(c){
                return new Date(this.getFullYear(), this.getMonth(), this.getDate()+c);
            };
            /* Date.prototype.getSunday()
	        * 
	        * Returns the Sunday nearest in the past to this date (inclusive)
	        */
            Date.prototype.getSunday=function(){
                return this.deltaDays(-1*this.getDay());
            };
        })();
        /** Week
        * 
        * Represents a week.
        * 
        * Functions (Methods):
        *	.nextWeek() returns a Week object sequentially in the future
        *	.prevWeek() returns a Week object sequentially in the past
        *	.contains(date) returns true if this week's sunday is the same
        *		as date's sunday; false otherwise
        *	.getDates() returns an Array containing 7 Date objects, each representing
        *		one of the seven days in this month
        */
        function Week(c){
            this.sunday=c.getSunday();
            this.nextWeek=function(){
                return new Week(this.sunday.deltaDays(7))
            };
            this.prevWeek=function(){
                return new Week(this.sunday.deltaDays(-7))
            };
            this.contains=function(b){
                return this.sunday.valueOf()===b.getSunday().valueOf()
            };
            this.getDates=function(){
                for(var b=[],a=0;7>a;a++){
                    b.push(this.sunday.deltaDays(a));
                    return b;
                }
            };
        }
        /** Month
        * 
        * Represents a month.
        * 
        * Properties:
        *	.year == the year associated with the month
        *	.month == the month number (January = 0)
        * 
        * Functions (Methods):
        *	.nextMonth() returns a Month object sequentially in the future
        *	.prevMonth() returns a Month object sequentially in the past
        *	.getDateObject(d) returns a Date object representing the date
        *		d in the month
        *	.getWeeks() returns an Array containing all weeks spanned by the
        *		month; the weeks are represented as Week objects
        */
        function Month(c,b){
            this.year=c;
            this.month=b;
            this.nextMonth=function(){
                return new Month(c+Math.floor((b+1)/12),(b+1)%12)
            };
            this.prevMonth=function(){
                return new Month(c+Math.floor((b-1)/12),(b+11)%12)
            };
            this.getDateObject=function(a){
                return new Date(this.year,this.month,a)
            };
            this.getWeeks=function(){
                var firstDay=this.getDateObject(1);
                var lastDay=this.nextMonth().getDateObject(0);
                var weeks=[];
                var currweek=new Week(firstDay);
                weeks.push(currweek);
		        while(!currweek.contains(lastDay)){
			        currweek = currweek.nextWeek();
			        weeks.push(currweek);
		        }
		        return weeks;
            };
        }
        // Month array found on https://www.w3schools.com/jsref/jsref_getmonth.asp
        const month = new Array();
        month[0] = "January";
        month[1] = "February";
        month[2] = "March";
        month[3] = "April";
        month[4] = "May";
        month[5] = "June";
        month[6] = "July";
        month[7] = "August";
        month[8] = "September";
        month[9] = "October";
        month[10] = "November";
        month[11] = "December";
        function nameMonth(num){
            return month[num];
        }
    </script>   
    <div id="welcome">
        <?php 
        ini_set("session.cookie_httponly", 1);
        session_start();
        ?>
        <h2 id="greeting">Hello, <span id="usrnm"></span></h2>
        <input type='hidden' id='token' value='<?php echo $_SESSION["token"];?>' />
        <input type='hidden' id='userid' value='<?php echo $_SESSION["user_id"];?>' />
    </div>
    
    <div id="yesuser">
        <button id="logout">Log out</button> <button id="deleteuser">Delete account</button><br><br>
        <!-- Text file creater button found on https://jsfiddle.net/taditdash/hkjpzjuj/ -->
        <button id="create">Create a text file of this month's events</button> <a download="monthevents.txt" href="" id="downloadlink" style="visibility: hidden">Download</a>
        <br><br>
        <button id="showadd">Add an event</button> 
        <button id="showedit">Edit an event</button> 
        <button id="showdelete">Delete an event</button>
        <button id="showcount">See event countdown</button>
        <br>
        <div id="buttondisplay">
             
        </div>
        <script src="makefileajax.js"></script>
        <script src="addeventajax.js"></script>
        <script src="deleteeventajax.js"></script>
        <script src="editeventajax.js"></script>
        <!-- <script src="countdownajax.js"></script> -->
        <!-- date and time input types found on https://www.w3schools.com/html/html_form_input_types.asp-->
        <script>
        document.getElementById("welcome").style.display = "none";
        document.getElementById("logout").addEventListener("click", function(event){document.getElementById("buttondisplay").textContent = 
        ' '; clearInterval(sessionStorage.getItem("x"));
        },false);
        document.getElementById("showadd").addEventListener("click", function(event){ document.getElementById("buttondisplay").innerHTML = 
        '<h3>Add an event:</h3><label>Event: <input type="text" id="eventcontent1" placeholder="Title" /></label><br><br><label>Date: <input type="date" id="date1"/></label><label>Time: <input type="time" id="time1" /></label><br><br><button id="addevent">Add</button>';
        document.getElementById("addevent").addEventListener("click", addEventAjax, false);
        clearInterval(sessionStorage.getItem("x"));
        document.getElementById("addevent").addEventListener("click", mainMonth, false);},false);
        document.getElementById("showdelete").addEventListener("click", function(event){document.getElementById("buttondisplay").innerHTML = 
        '<h3>Delete an event:</h3><label>Event: <input type="text" id="eventcontent2" placeholder="Title" /></label><br><br><label>Date: <input type="date" id="date2"/></label><label>Time: <input type="time" id="time2" /></label><br><br><button id="deleteevent">Delete</button>';
        document.getElementById("deleteevent").addEventListener("click", deleteEventAjax, false);
        clearInterval(sessionStorage.getItem("x"));
        document.getElementById("deleteevent").addEventListener("click", mainMonth, false);},false);
        document.getElementById("showedit").addEventListener("click", function(event){document.getElementById("buttondisplay").innerHTML = 
        '<h3>Edit an event:</h3><label><strong>Choose the event you would like to modify: </strong><input type="text" id="eventcontent3" placeholder="Event Title" /></label><label>Date: <input type="date" id="date3"/></label><label>Time: <input type="time" id="time3" /></label><br><br><label><strong>Modified event input: </strong><input type="text" id="eventcontent4" placeholder="Event Title" /></label><label>Date: <input type="date" id="date4"/></label><label>Time: <input type="time" id="time4" /></label></label><br><br><button id="editevent">Modify</button>';
        document.getElementById("editevent").addEventListener("click", editEventAjax, false);
        clearInterval(sessionStorage.getItem("x"));
        document.getElementById("editevent").addEventListener("click", mainMonth, false);},false);
        document.getElementById("showcount").addEventListener("click", function(event){document.getElementById("buttondisplay").innerHTML = 
        '<h3>Show countdown:</h3><label><strong>Choose the event you would like to see a countdown to: </strong><input type="text" id="eventcontent5" placeholder="Event Title" /></label><label>Date: <input type="date" id="date5"/></label><label>Time: <input type="time" id="time5" /></label> <button id="countdown">See countdown</button><br><br><div id="timer"> </div>';
        document.getElementById("countdown").addEventListener("click", countdownAjax, false);},false);       
        function countdownAjax(event) {
            const eventcontent = String(document.getElementById("eventcontent5").value);
            const dateorg = document.getElementById("date5").value;
            const date = String(document.getElementById("date5").value);
            const time = String(document.getElementById("time5").value);
            const token = String(document.getElementById("token").value);

            const data = { 'eventcontent': eventcontent, 'date': date, 'time': time, 'token': token };

            fetch("countdown_ajax.php", {
                    method: 'POST',
                    body: JSON.stringify(data),
                    headers: { 'content-type': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {console.log(data.success ? "Countdown showing." : `Countdown could not be shown. ${data.message}`); if(!data.success){alert(data.message)}else{counter(data.year, data.month, data.day, data.hours, data.minutes)}});
        }
        function counter(year, month, day, hour, min){
            // Code for this method found on https://www.w3schools.com/howto/howto_js_countdown.asp
            if(sessionStorage.getItem("x")!=null){
                clearInterval(sessionStorage.getItem("x"));
            }  
            let countDownDate = new Date(year, month, day, hour, min);

            sessionStorage.setItem("x", setInterval(function() {
                let now = new Date().getTime();
                let distance = countDownDate - now;
                let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((distance % (1000 * 60)) / 1000);
                document.getElementById("timer").innerHTML = days + "d " + hours + "h "
                + minutes + "m " + seconds + "s ";
                if (distance < 0) {
                    clearInterval(sessionStorage.getItem("x"));
                    document.getElementById("timer").innerHTML = "EXPIRED: This date has already passed.";
                }
            }, 1000))
        }
        </script>
    </div>
    <div id="nonuser">
        <!-- Both forms below taken and modified from "Logging in a User" example on AJAX class wiki -->
        <label id="reg">Create an account: <input type="text" id="createusername" placeholder="Username" /></label>
        <input type="password" id="createpassword" placeholder="Password" />
        <button id="signup_btn">Register</button>
        <script src="registerajax.js"></script>
        <br><br>
        <label id="signin">Sign in: <input type="text" id="username" placeholder="Username" /></label>
        <input type="password" id="password" placeholder="Password" />
        <button id="login_btn">Log In</button>
        <!-- <script src="loginajax.js"></script> -->
        <br><br><br>
    </div>
    <h1><span id="month">Month</span> <span id="year">Year</span></h1>
    <button id="prevpg">Previous Month</button><button id="nextpg">Next Month</button>
    <br>
    <br>
    <table>
        <tr>
            <th>Sunday</th>
            <th>Monday</th>
            <th>Tuesday</th>
            <th>Wednesday</th>
            <th>Thursday</th>
            <th>Friday</th>
            <th>Saturday</th>
        </tr>
        <?php
            for($row=1; $row<=6; $row++){
                echo "<tr>";
                for ($col=1; $col<=7; $col++){
                    echo "<td id='($row,$col)'><div class='date'></div><ul class='events'> </ul></td>";
                }
                echo "</tr>";              
            }
        ?>
    </table>
    <script>
        function loginAjax(event, callback) {
            const username = String(document.getElementById("username").value);
            const password = String(document.getElementById("password").value);

            const data = { 'username': username, 'password': password };
            fetch("login_ajax.php", {
                    method: 'POST',
                    body: JSON.stringify(data),
                    headers: { 'content-type': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {console.log(data.success ? "You've been logged in!" : `You were not logged in. ${data.message}`); if(!data.success){alert(data.message)}else{document.getElementById('token').setAttribute("value", data.token); callback();}});
                
            
        }
    document.getElementById("login_btn").addEventListener("click", function(event){loginAjax(event, loginChecker);}, false);
    document.addEventListener("DOMContentLoaded", whatToDisplay, false);
    function whatToDisplay(){
        // sessionStorage info found on https://developer.mozilla.org/en-US/docs/Web/API/Window/sessionStorage
        if (sessionStorage.getItem("loggedin")==null){
            sessionStorage.setItem("loggedin", 0);
        }
        let test = sessionStorage.getItem("loggedin");
        if (test==1){
            document.getElementById("nonuser").style.visibility = "hidden";
            document.getElementById("yesuser").style.visibility = "visible";
            document.getElementById("userid").setAttribute("value", sessionStorage.getItem("userid"));
            document.getElementById("usrnm").textContent = sessionStorage.getItem("usrnm");
            document.getElementById("welcome").style.display = "block";
        }
        else{
            document.getElementById("yesuser").style.visibility = "hidden";
            document.getElementById("downloadlink").style.visibility = "hidden";
            document.getElementById("nonuser").style.visibility = "visible";
            document.getElementById("welcome").style.display = "none";
        }
    }
        document.getElementById("login_btn").addEventListener("click", mainMonth, false);
        let date = new Date();
        document.getElementById("month").textContent = nameMonth(date.getMonth());
        // getFullYear() function found on https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Date/getFullYear
        document.getElementById("year").textContent = date.getFullYear();
        let first = new Date(2019, date.getMonth(), 1);
        let zeroDate = new Date(2019, date.getMonth()+1, 0);
        let day = 1;
        // Code taken/modified from "Logging in a User" section of AJAX class wiki
        function displayEvents(givendate, daynum){
            const displaymonth = givendate.getMonth()+1;
            const displayyear = givendate.getFullYear();
            const displayday = daynum;
            const token = document.getElementById("token").value;
            const data = { 'displaymonth': displaymonth, 'displayyear': displayyear, 'displayday': displayday, 'token': token };
            fetch("displayevents_ajax.php", {
                    method: 'POST',
                    body: JSON.stringify(data),
                    headers: { 'content-type': 'application/json' }
                })
                .then(res => res.json())
                .then(response => eventParser(JSON.stringify(response), givendate, daynum))
                .catch(error => console.error('Error:',error))
        }
        const coords = new Array();
        coords[1] = "(1,1)";
        coords[2] = "(1,2)";
        coords[3] = "(1,3)";
        coords[4] = "(1,4)";
        coords[5] = "(1,5)";
        coords[6] = "(1,6)";
        coords[7] = "(1,7)";
        coords[8] = "(2,1)";
        coords[9] = "(2,2)";
        coords[10] = "(2,3)";
        coords[11] = "(2,4)";
        coords[12] = "(2,5)";
        coords[13] = "(2,6)";
        coords[14] = "(2,7)";
        coords[15] = "(3,1)";
        coords[16] = "(3,2)";
        coords[17] = "(3,3)";
        coords[18] = "(3,4)";
        coords[19] = "(3,5)";
        coords[20] = "(3,6)";
        coords[21] = "(3,7)";
        coords[22] = "(4,1)";
        coords[23] = "(4,2)";
        coords[24] = "(4,3)";
        coords[25] = "(4,4)";
        coords[26] = "(4,5)";
        coords[27] = "(4,6)";
        coords[28] = "(4,7)";
        coords[29] = "(5,1)";
        coords[30] = "(5,2)";
        coords[31] = "(5,3)";
        coords[32] = "(5,4)";
        coords[33] = "(5,5)";
        coords[34] = "(5,6)";
        coords[35] = "(5,7)";
        coords[36] = "(6,1)";
        coords[37] = "(6,2)";
        coords[38] = "(6,3)";
        coords[39] = "(6,4)";
        coords[40] = "(6,5)";
        coords[41] = "(6,6)";
        coords[42] = "(6,7)";        
        document.getElementById("nextpg").addEventListener("click", function(event){document.getElementById("create").textContent = "Create a text file of this month's events"; document.getElementById("downloadlink").style.visibility = "hidden";}, false);
        document.getElementById("prevpg").addEventListener("click", function(event){document.getElementById("create").textContent = "Create a text file of this month's events"; document.getElementById("downloadlink").style.visibility = "hidden";}, false);
        document.getElementById("login_btn").addEventListener("click", function(event){document.getElementById("create").textContent = "Create a text file of this month's events"; document.getElementById("downloadlink").style.visibility = "hidden";}, false);
        document.getElementById("logout").addEventListener("click", function(event){document.getElementById("create").textContent = "Create a text file of this month's events"; document.getElementById("downloadlink").style.visibility = "hidden";}, false);
        function eventParser(entr, dt, dy){
            var jsonData = JSON.parse(entr);
            let respo = "";
            let firstdt = new Date(dt.getFullYear(), dt.getMonth(), 1);
            // If statement found on https://stackoverflow.com/questions/18884249/checking-whether-something-is-iterable/32538867
            if (typeof jsonData[Symbol.iterator] === 'function'){
                for (let item of jsonData){
                    if(item.time1 > 12){
                        if(item.time2 < 10){
                            respo += `<li>${item.time1-12}:0${item.time2} PM: ${item.event}</li>`;
                        }
                        else{
                            respo += `<li>${item.time1-12}:${item.time2} PM: ${item.event}</li>`;
                        }
                    }
                    else{
                        if(item.time1==0){
                            if(item.time2 < 10){
                                respo += `<li>12:0${item.time2} AM: ${item.event}</li>`;
                            }
                            else{
                                respo += `<li>12:${item.time2} AM: ${item.event}</li>`;
                            }
                        }
                        if(item.time1==12){
                            if(item.time2 < 10){
                                respo += `<li>12:0${item.time2} PM: ${item.event}</li>`;
                            }
                            else{
                                respo += `<li>12:${item.time2} PM: ${item.event}</li>`;
                            }
                        }
                        if(item.time1!=12&&item.time1!=0){
                            if(item.time2 < 10){
                                respo += `<li>${item.time1}:0${item.time2} AM: ${item.event}</li>`;
                            }
                            else{
                                respo += `<li>${item.time1}:${item.time2} AM: ${item.event}</li>`;
                            }                            
                        }
                    }
                }                
            }
            document.getElementById(coords[firstdt.getDay()+dy]).lastChild.innerHTML= respo;
        }
        for (j=1; j<=6; j++){
                    for (i=1; i<=7; i++){
                        if (day <= Number(zeroDate.getDate())){
                            if (j==1){
                                // getDay() function found on https://www.w3schools.com/jsref/jsref_getday.asp
                                if(i>first.getDay()){
                                    document.getElementById(`(${j},${i})`).firstChild.textContent = day;
                                    if(sessionStorage.getItem("loggedin")==1){
                                        displayEvents(date, day);
                                    }
                                    else{
                                        document.getElementById(`(${j},${i})`).lastChild.textContent = "";
                                    }
                                    day++;
                                }
                            }
                            else{
                                document.getElementById(`(${j},${i})`).firstChild.textContent = day;
                                if(sessionStorage.getItem("loggedin")==1){
                                        displayEvents(date, day);
                                }
                                else{
                                    document.getElementById(`(${j},${i})`).lastChild.textContent = "";
                                }
                                day++;
                            }
                        }
                    }
            }
        function mainMonth(){
            let date0 = new Date();
            document.getElementById("month").textContent = nameMonth(date0.getMonth());
            // getFullYear() function found on https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Date/getFullYear
            document.getElementById("year").textContent = date0.getFullYear();
            let first = new Date(2019, date0.getMonth(), 1);
            let zeroDate = new Date(2019, date0.getMonth()+1, 0);
            let day = 1;
            for (j=1; j<=6; j++){
                    for (i=1; i<=7; i++){
                        if (day <= Number(zeroDate.getDate())){
                            if (j==1){
                                // getDay() function found on https://www.w3schools.com/jsref/jsref_getday.asp
                                if(i>first.getDay()){
                                    document.getElementById(`(${j},${i})`).firstChild.textContent = day;
                                    if(sessionStorage.getItem("loggedin")==1){
                                        displayEvents(first, day);
                                    }
                                    else{
                                        document.getElementById(`(${j},${i})`).lastChild.textContent = "";
                                    }
                                    day++;
                                }
                            }
                            else{
                                document.getElementById(`(${j},${i})`).firstChild.textContent = day;
                                if(sessionStorage.getItem("loggedin")==1){
                                    displayEvents(first, day);
                                }
                                else{
                                    document.getElementById(`(${j},${i})`).lastChild.textContent = "";
                                }
                                day++;
                            }
                        }
                        else{
                            document.getElementById(`(${j},${i})`).firstChild.textContent = "";
                            document.getElementById(`(${j},${i})`).lastChild.textContent = "";
                        }
                    }
            }
            document.getElementById('downloadlink').style.visibility = "hidden";
            if(sessionStorage.getItem("loggedin")==1){
                document.getElementById("create").style.visibility = "visible";
            }
            date = date0
        }
        function nextPage(){
            let currdate = date;
            // getFullYear() function found on https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Date/getFullYear
            let currMonth = new Month(currdate.getFullYear(), currdate.getMonth());
            let nowMonth = currMonth.nextMonth();
            document.getElementById("month").textContent = nameMonth(nowMonth.month);
            document.getElementById("year").textContent = nowMonth.year;
            let first1 = new Date(nowMonth.year, nowMonth.month, 1);
            let zeroDate = new Date(nowMonth.year, nowMonth.month+1, 0);
            let day = 1;
            for (j=1; j<=6; j++){
                    for (i=1; i<=7; i++){
                        if (day <= Number(zeroDate.getDate())){
                            if (j==1){
                                // getDay() function found on https://www.w3schools.com/jsref/jsref_getday.asp
                                if(i>first1.getDay()){
                                    document.getElementById(`(${j},${i})`).firstChild.textContent = day;
                                    if(sessionStorage.getItem("loggedin")==1){
                                        displayEvents(first1, day);
                                    }
                                    else{
                                        document.getElementById(`(${j},${i})`).lastChild.textContent = "";
                                    }
                                    day++;
                                }
                                else{
                                    document.getElementById(`(${j},${i})`).firstChild.textContent = "";
                                    document.getElementById(`(${j},${i})`).lastChild.textContent = "";
                                }
                            }
                            else{
                                document.getElementById(`(${j},${i})`).firstChild.textContent = day;
                                if(sessionStorage.getItem("loggedin")==1){
                                        displayEvents(first1, day);
                                }
                                else{
                                    document.getElementById(`(${j},${i})`).lastChild.textContent = "";
                                }
                                day++;
                            }
                        }
                        else{
                            document.getElementById(`(${j},${i})`).firstChild.textContent = "";
                            document.getElementById(`(${j},${i})`).lastChild.textContent = "";
                        }
                    }
            }
            document.getElementById('downloadlink').style.visibility = "hidden";
            if(sessionStorage.getItem("loggedin")==1){
                document.getElementById("create").style.visibility = "visible";
            }
            date = first1;
        }
        function prevPage(){
            let currdate = date;
            // getFullYear() function found on https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Date/getFullYear
            let currMonth = new Month(currdate.getFullYear(), currdate.getMonth());
            let nowMonth = currMonth.prevMonth();
            document.getElementById("month").textContent = nameMonth(nowMonth.month);
            document.getElementById("year").textContent = nowMonth.year;
            let first2 = new Date(nowMonth.year, nowMonth.month, 1);
            let zeroDate = new Date(nowMonth.year, nowMonth.month+1, 0);
            let day = 1;
            for (j=1; j<=6; j++){
                    for (i=1; i<=7; i++){
                        if (day <= Number(zeroDate.getDate())){
                            if (j==1){
                                // getDay() function found on https://www.w3schools.com/jsref/jsref_getday.asp
                                if(i>first2.getDay()){
                                    document.getElementById(`(${j},${i})`).firstChild.textContent = day;
                                    if(sessionStorage.getItem("loggedin")==1){
                                        displayEvents(first2, day);
                                    }
                                    else{
                                        document.getElementById(`(${j},${i})`).lastChild.textContent = "";
                                    }
                                    day++;
                                }
                                else{
                                    document.getElementById(`(${j},${i})`).firstChild.textContent = "";
                                    document.getElementById(`(${j},${i})`).lastChild.textContent = "";
                                }
                            }
                            else{
                                document.getElementById(`(${j},${i})`).firstChild.textContent = day;
                                if(sessionStorage.getItem("loggedin")==1){
                                        displayEvents(first2, day);
                                }
                                else{
                                    document.getElementById(`(${j},${i})`).lastChild.textContent = "";
                                }
                                day++;
                            }
                        }
                        else{
                            document.getElementById(`(${j},${i})`).firstChild.textContent = "";
                            document.getElementById(`(${j},${i})`).lastChild.textContent = "";
                        }
                    }
            }
            document.getElementById('downloadlink').style.visibility = "hidden";
            if(sessionStorage.getItem("loggedin")==1){
                document.getElementById("create").style.visibility = "visible";
            }
            date = first2;
        }
        document.getElementById("prevpg").addEventListener("click", prevPage, false);
        document.getElementById("nextpg").addEventListener("click", nextPage, false);
        document.getElementById("logout").addEventListener("click", logOut, false);
        document.getElementById("deleteuser").addEventListener("click", function(event){deleteUser(); clearInterval(sessionStorage.getItem("x"));}, false);
        // Code taken/modified from "Logging in a User" section of AJAX class wiki
        function logOut(){
            fetch('unlog.php')
            .then(res => res.json())
            .then(response => console.log('Success:', JSON.stringify(response)))
            .catch(error => console.error('Error:', error))
            sessionStorage.removeItem("loggedin");
            sessionStorage.setItem("loggedin", 0);
            mainMonth();
            whatToDisplay();
            document.getElementById('downloadlink').style.visibility = "hidden";
            document.getElementById("create").style.visibility = "hidden";
        }
        // Code taken/modified from "Logging in a User" section of AJAX class wiki        
        function deleteUser(event) {
            const token = String(document.getElementById("token").value);

            const data = { 'token': token };
            fetch("deleteuser_ajax.php", {
                    method: 'POST',
                    body: JSON.stringify(data),
                    headers: { 'content-type': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {console.log(data.success ? "Your account has been deleted" : `Your account was not deleted. ${data.message}`); if(!data.success){alert(data.message)}else{alert("Your account has been deleted."); logOut();}});
            
        }        
        // Code taken/modified from "Logging in a User" section of AJAX class wiki
        function loginChecker(event) {
            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;

            const data = { 'username': username, 'password': password };
            fetch("logger2.php", {
                    method: 'POST',
                    body: JSON.stringify(data),
                    headers: { 'content-type': 'application/json' }
                })
                .then(res => res.json())
                .then(response => {console.log('Success'); loginDisplay(JSON.stringify(response))})
                .catch(error => console.error('Error:',error))
        }

        function loginDisplay(resp){
            var jsonData = JSON.parse(resp);
            if (jsonData.success){
                // .style.visibility found on https://www.w3schools.com/jsref/prop_style_visibility.asp
                document.getElementById("nonuser").style.visibility = "hidden";
                document.getElementById("yesuser").style.visibility = "visible";
                sessionStorage.setItem("usrnm", jsonData.usrnm);
                sessionStorage.setItem("userid", jsonData.userid);
                document.getElementById("userid").setAttribute("value", sessionStorage.getItem("userid"));
                document.getElementById("usrnm").textContent = sessionStorage.getItem("usrnm");
                document.getElementById("welcome").style.display = "block";
                sessionStorage.removeItem("loggedin");
                sessionStorage.setItem("loggedin", 1);
                mainMonth();
            }
        }
    </script>
</body>
</html>