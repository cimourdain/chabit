/*

COMMON FUNCTIONS 


*/


/*

DOM NAVIGATION

*/
function findAncestorByTagName (el, tag)
{
  var utag = tag.toUpperCase();
  while ((el = el.parentElement) && el.tagName != utag);
  return el;
}


/*

DATES MANAGEMENT

*/

function strDateToDateTime(dateString)
{
	console.log('Convert date '+dateString);
	var regex = /(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/;
	var dateArray = regex.exec(dateString); 


	var conv_date = new Date(Date.UTC(
	    (+dateArray[1]),
	    (+dateArray[2])-1, // Monthes starts at 0
	    (+dateArray[3]),
	    (+dateArray[4]),
	    (+dateArray[5]),
	    (+dateArray[6])
		)
	);
  //console.log ('Converted date is : '+conv_date);
  return conv_date;
}


function timeTillNow (str, convert = 60000) {
  var today = new Date();
  //time stamp is taken for testing
  var course_time = strDateToDateTime(str);

  //console.log('Get difference between current date '+today+' and provided date +'+course_time);
  //difference in mili seconds
  var diff = today.getTime() - course_time.getTime();
  //round off mili-sec to days
  diff = Math.round(diff / (1000 * 60 ));
  return diff;
};


function sort_by (field, reverse, primer){
  console.log('Sort');
   var key = primer ? 
       function(x) {return primer(x[field])} : 
       function(x) {return x[field]};

   reverse = !reverse ? 1 : -1;

   return function (a, b) {
       return a = key(a), b = key(b), reverse * ((a > b) - (b > a));
     } 
}


/*



*/
function getFileResponse(method, addr, source_obj, successMethod, failMethod, returnOnSuccess = true, returnOnFail = true)
{
  var req = new XMLHttpRequest();
  console.log('Attempt connexion to '+addr+ ' (method '+ method+ ')');
  req.open(method, addr, true);
  
  //callback method
  req.onreadystatechange = function(event) {
      // XMLHttpRequest.DONE === 4
      if (this.readyState === XMLHttpRequest.DONE) {
          if (this.status === 200) {
            	//console.log("Answer: %s", this.responseText);
              if(successMethod != null)
              {
                if(returnOnSuccess)
                {
                  console.log('Return result with arg');
                  successMethod.call(source_obj, this.responseText);
                }else{
                  console.log('Return result without arg');
                  successMethod.call(source_obj);
                }
              }else{
                console.log('No success method defined');
              }
          }else {
              if(failMethod != null)
              {
                console.log('Return on fail');
                //console.log("Answer: %d (%s)", this.status, this.statusText);
                failMethod.call(source_obj, this.responseText);
              }else{
                console.log('No fail method defined');
              }
          }
      }
  };
  req.send(null);
}
