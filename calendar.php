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
    <h1><span id="month">Month</span> <span id="year">Year</span></h1>
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
        <button id="logout">Log out</button><br><br>
        <button id="showadd">Add an event</button> 
        <button id="showedit">Edit an event</button> 
        <button id="showdelete">Delete an event</button><br>
        <div id="buttondisplay">
             
        </div>
        <!-- date and time input types found on https://www.w3schools.com/html/html_form_input_types.asp-->
        <script src="addeventajax.js"></script>
        <script src="deleteeventajax.js"></script>
        <script src="editeventajax.js"></script>
        <script>
        document.getElementById("welcome").style.display = "none";
        document.getElementById("logout").addEventListener("click", function(event){document.getElementById("buttondisplay").textContent = 
        ' ';
        },false);
        document.getElementById("showadd").addEventListener("click", function(event){ document.getElementById("buttondisplay").innerHTML = 
        '<h3>Add an event:</h3><label>Event: <input type="text" id="eventcontent1" placeholder="Title" /></label><br><br><label>Date: <input type="date" id="date1"/></label><label>Time: <input type="time" id="time1" /></label><br><br><button id="addevent">Add</button>';
        document.getElementById("addevent").addEventListener("click", addEventAjax, false);
        document.getElementById("addevent").addEventListener("click", mainMonth, false);},false);
        document.getElementById("showdelete").addEventListener("click", function(event){document.getElementById("buttondisplay").innerHTML = 
        '<h3>Delete an event:</h3><label>Event: <input type="text" id="eventcontent2" placeholder="Title" /></label><br><br><label>Date: <input type="date" id="date2"/></label><label>Time: <input type="time" id="time2" /></label><br><br><button id="deleteevent">Delete</button>';
        document.getElementById("deleteevent").addEventListener("click", deleteEventAjax, false);
        document.getElementById("deleteevent").addEventListener("click", mainMonth, false);},false);
        document.getElementById("showedit").addEventListener("click", function(event){document.getElementById("buttondisplay").innerHTML = 
        '<h3>Edit an event:</h3><label><strong>Choose the event you would like to modify: </strong><input type="text" id="eventcontent3" placeholder="Event Title" /></label><label>Date: <input type="date" id="date3"/></label><label>Time: <input type="time" id="time3" /></label><br><br><label><strong>Modified event input: </strong><input type="text" id="eventcontent4" placeholder="Event Title" /></label><label>Date: <input type="date" id="date4"/></label><label>Time: <input type="time" id="time4" /></label></label><br><br><button id="editevent">Modify</button>';
        document.getElementById("editevent").addEventListener("click", editEventAjax, false);
        document.getElementById("editevent").addEventListener("click", mainMonth, false);},false);
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
        <script src="loginajax.js"></script>
        <br><br><br>
    </div>
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
    document.getElementById("login_btn").addEventListener("click", loginAjax, false);
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
            document.getElementById("nonuser").style.visibility = "visible";
            document.getElementById("welcome").style.display = "none";
        }
    }
    </script>
    <script>
        document.getElementById("login_btn").addEventListener("click", mainMonth, false);
        let date = new Date();
        document.getElementById("month").textContent = nameMonth(date.getMonth());
        // getFullYear() function found on https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Date/getFullYear
        document.getElementById("year").textContent = date.getFullYear();
        let first = new Date(2019, date.getMonth(), 1);
        let zeroDate = new Date(2019, date.getMonth()+1, 0);
        let day = 1;
        function displayEvents(givendate, daynum){
            const displaymonth = givendate.getMonth()+1;
            // alert(displaymonth);
            const displayyear = givendate.getFullYear();
            // alert(displayyear);
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
                        if(item.time2 < 10){
                            respo += `<li>${item.time1}:0${item.time2} AM: ${item.event}</li>`;
                        }
                        else{
                            respo += `<li>${item.time1}:${item.time2} AM: ${item.event}</li>`;
                        }
                    }
                }                
            }
            document.getElementById(coords[firstdt.getDay()+dy]).lastChild.innerHTML= respo;
        }
        for (j=1; j<=6; j++){
                if(day <= Number(zeroDate.getDate())){
                    for (i=1; i<=7; i++){
                        if (day <= Number(zeroDate.getDate())){
                            if (j==1){
                                // getDay() function found on https://www.w3schools.com/jsref/jsref_getday.asp
                                if(i>first.getDay()){
                                    document.getElementById(`(${j},${i})`).firstChild.textContent = day;
                                    displayEvents(date, day);
                                    day++;
                                }
                            }
                            else{
                                document.getElementById(`(${j},${i})`).firstChild.textContent = day;
                                displayEvents(date, day);
                                day++;
                            }
                        }
                    }
                }
            }
        function mainMonth(){
            let date = new Date();
            document.getElementById("month").textContent = nameMonth(date.getMonth());
            // getFullYear() function found on https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Date/getFullYear
            document.getElementById("year").textContent = date.getFullYear();
            let first = new Date(2019, date.getMonth(), 1);
            let zeroDate = new Date(2019, date.getMonth()+1, 0);
            let day = 1;
            for (j=1; j<=6; j++){
                if(day <= Number(zeroDate.getDate())){
                    for (i=1; i<=7; i++){
                        if (day <= Number(zeroDate.getDate())){
                            if (j==1){
                                // getDay() function found on https://www.w3schools.com/jsref/jsref_getday.asp
                                if(i>first.getDay()){
                                    document.getElementById(`(${j},${i})`).firstChild.textContent = day;
                                    displayEvents(first, day);
                                    day++;
                                }
                            }
                            else{
                                document.getElementById(`(${j},${i})`).firstChild.textContent = day;
                                displayEvents(first, day);
                                day++;
                            }
                        }
                    }
                }
            }
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
                                    displayEvents(first1, day);
                                    day++;
                                }
                                else{
                                    document.getElementById(`(${j},${i})`).firstChild.textContent = "";
                                    document.getElementById(`(${j},${i})`).lastChild.textContent = "";
                                }
                            }
                            else{
                                document.getElementById(`(${j},${i})`).firstChild.textContent = day;
                                displayEvents(first1, day);
                                day++;
                            }
                        }
                        else{
                            document.getElementById(`(${j},${i})`).firstChild.textContent = "";
                            document.getElementById(`(${j},${i})`).lastChild.textContent = "";
                        }
                    }
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
                                    displayEvents(first2, day);
                                    day++;
                                }
                                else{
                                    document.getElementById(`(${j},${i})`).firstChild.textContent = "";
                                    document.getElementById(`(${j},${i})`).lastChild.textContent = "";
                                }
                            }
                            else{
                                document.getElementById(`(${j},${i})`).firstChild.textContent = day;
                                displayEvents(first2, day);
                                day++;
                            }
                        }
                        else{
                            document.getElementById(`(${j},${i})`).firstChild.textContent = "";
                            document.getElementById(`(${j},${i})`).lastChild.textContent = "";
                        }
                    }
            }
            date = first2;
        }
        document.getElementById("prevpg").addEventListener("click", prevPage, false);
        document.getElementById("nextpg").addEventListener("click", nextPage, false);
        document.getElementById("logout").addEventListener("click", logOut, false);
        function logOut(){
            fetch('unlog.php')
            .then(res => res.json())
            .then(response => console.log('Success:', JSON.stringify(response)))
            .catch(error => console.error('Error:', error))
            sessionStorage.removeItem("loggedin");
            sessionStorage.setItem("loggedin", 0);
            mainMonth();
            whatToDisplay();
        }
    </script>
    <script>
        document.getElementById("login_btn").addEventListener("click", loginChecker, false);
        
        function loginChecker(event) {
            const username = document.getElementById("username").value; // Get the username from the form
            const password = document.getElementById("password").value; // Get the password from the form
            // Make a URL-encoded string for passing POST data:
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