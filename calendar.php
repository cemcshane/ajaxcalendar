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
    <h1><div id="month"></div> <div id="year"></div></h1>
    <button id="prevpg">Previous Month</button><button id="nextpg">Next Month</button>
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
    </script>
</body>
</html>