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
        <!-- date and input types found on https://www.w3schools.com/html/html_form_input_types.asp-->
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
        document.getElementById("addevent").addEventListener("click", addEventAjax, false);},false);
        document.getElementById("showdelete").addEventListener("click", function(event){document.getElementById("buttondisplay").innerHTML = 
        '<h3>Delete an event:</h3><label>Event: <input type="text" id="eventcontent2" placeholder="Title" /></label><br><br><label>Date: <input type="date" id="date2"/></label><label>Time: <input type="time" id="time2" /></label><br><br><button id="deleteevent">Delete</button>';
        document.getElementById("deleteevent").addEventListener("click", deleteEventAjax, false);},false);
        document.getElementById("showedit").addEventListener("click", function(event){document.getElementById("buttondisplay").innerHTML = 
        '<h3>Edit an event:</h3><label><strong>Choose the event you would like to modify: </strong><input type="text" id="eventcontent3" placeholder="Event Title" /></label><label>Date: <input type="date" id="date3"/></label><label>Time: <input type="time" id="time3" /></label><br><br><label><strong>Modified event input: </strong><input type="text" id="eventcontent4" placeholder="Event Title" /></label><label>Date: <input type="date" id="date4"/></label><label>Time: <input type="time" id="time4" /></label></label><br><br><button id="editevent">Modify</button>';
        document.getElementById("editevent").addEventListener("click", editEventAjax, false);},false);
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
                    echo "<td id='($row,$col)'><div class='date'></div><div class='event'></div> </td>";
                }
                echo "</tr>";              
            }
        ?>
    </table>
    <script>
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
                                day++;
                            }
                        }
                        else{
                            document.getElementById(`(${j},${i})`).firstChild.textContent = day;
                            day++;
                        }
                    }
                }
            }
        }
        function nextPage(){
            let currdate = date;
            let currMonth = new Month(currdate.getFullYear(), currdate.getMonth());
            let nowMonth = currMonth.nextMonth();
            document.getElementById("month").textContent = nameMonth(nowMonth.month);
            // getFullYear() function found on https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Date/getFullYear
            document.getElementById("year").textContent = nowMonth.year;
            let first = new Date(nowMonth.year, nowMonth.month, 1);
            let zeroDate = new Date(nowMonth.year, nowMonth.month+1, 0);
            let day = 1;
            for (j=1; j<=6; j++){
                    for (i=1; i<=7; i++){
                        if (day <= Number(zeroDate.getDate())){
                            if (j==1){
                                // getDay() function found on https://www.w3schools.com/jsref/jsref_getday.asp
                                if(i>first.getDay()){
                                    document.getElementById(`(${j},${i})`).firstChild.textContent = day;
                                    day++;
                                }
                                else{
                                    document.getElementById(`(${j},${i})`).firstChild.textContent = "";
                                }
                            }
                            else{
                                document.getElementById(`(${j},${i})`).firstChild.textContent = day;
                                day++;
                            }
                        }
                        else{
                            document.getElementById(`(${j},${i})`).firstChild.textContent = "";
                        }
                    }
            }
            date = first;
        }
        function prevPage(){
            let currdate = date;
            let currMonth = new Month(currdate.getFullYear(), currdate.getMonth());
            let nowMonth = currMonth.prevMonth();
            document.getElementById("month").textContent = nameMonth(nowMonth.month);
            // getFullYear() function found on https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Date/getFullYear
            document.getElementById("year").textContent = nowMonth.year;
            let first = new Date(nowMonth.year, nowMonth.month, 1);
            let zeroDate = new Date(nowMonth.year, nowMonth.month+1, 0);
            let day = 1;
            for (j=1; j<=6; j++){
                    for (i=1; i<=7; i++){
                        if (day <= Number(zeroDate.getDate())){
                            if (j==1){
                                // getDay() function found on https://www.w3schools.com/jsref/jsref_getday.asp
                                if(i>first.getDay()){
                                    document.getElementById(`(${j},${i})`).firstChild.textContent = day;
                                    day++;
                                }
                                else{
                                    document.getElementById(`(${j},${i})`).firstChild.textContent = "";
                                }
                            }
                            else{
                                document.getElementById(`(${j},${i})`).firstChild.textContent = day;
                                day++;
                            }
                        }
                        else{
                            document.getElementById(`(${j},${i})`).firstChild.textContent = "";
                        }
                    }
            }
            date = first;
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
            }
        }
    </script>
</body>
</html>