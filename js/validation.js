var flag,flag1,flag2,flag3,flag4,flag5;
function compare_strings(first,second)
{
if(first == second)
return true;
else
return false;
}

function search(username)
{
var xmlhttp, answer;
if (window.XMLHttpRequest)
  {
  // code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else if (window.ActiveXObject)
  {
  // code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
else
  {
  alert("Sorry, your browser seems to not support XMLHTTP functionality.");
  }


xmlhttp.onreadystatechange=function()
{
if(xmlhttp.readyState==4)
  {
	answer=xmlhttp.responseText;
	if(compare_strings(answer,1))
	{
		document.getElementById("nameInfo").className="error";
		document.getElementById("username").className="error";
		document.getElementById("nameInfo").innerHTML="Please enter your desired username.";
	}
	else if(compare_strings(answer,2))
	{
		document.getElementById("nameInfo").className="error";
		document.getElementById("username").className="error";
		document.getElementById("nameInfo").innerHTML="The username <strong>"+username+"</strong> is not allowed.";
	
	}
	else if(compare_strings(answer,3))
	{
		document.getElementById("nameInfo").className="error";
		document.getElementById("username").className="error";
		document.getElementById("nameInfo").innerHTML="The username <strong>"+username+"</strong> has already been taken.";
	
	}
	else 
	{
		document.getElementById("nameInfo").className="success";
		document.getElementById("username").className="";
		document.getElementById("nameInfo").innerHTML="Username <strong>"+username+"</strong> is allowed.";
		flag=1;	
	}
  }
}
var url="ajax_search.php";
url=url+"?q="+username;
url=url+"&sid="+Math.random();
xmlhttp.open("GET",url,true);
xmlhttp.send(null);
xmlhttp.send(null);
}

function search1(password)
{
var xmlhttp;
var answer1;
if (window.XMLHttpRequest)
  {
  // code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else if (window.ActiveXObject)
  {
  // code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
else
  {
  alert("Sorry, your browser seems to not support XMLHTTP functionality.");
  }


xmlhttp.onreadystatechange=function()
{
if(xmlhttp.readyState==4)
  {
	answer1=xmlhttp.responseText;
	if(compare_strings(answer1,1))
	{
		document.getElementById("result").className="error";
		document.getElementById("password").className="error";
		document.getElementById("result").innerHTML="Weak";
	
	}
	else if(compare_strings(answer1,2))
	{
		document.getElementById("result").className="error";
		document.getElementById("password").className="error";
		document.getElementById("result").innerHTML="Medium";
		
	}
	else if(compare_strings(answer1,3))
	{
		document.getElementById("result").className="success";
		document.getElementById("password").className="success";
		document.getElementById("result").innerHTML="Strong";
		flag1=1;
	}
	else 
	{
		document.getElementById("result").className="success";
		document.getElementById("password").className="";
		document.getElementById("result").innerHTML="Very Strong";
		flag1=1;
	}
  }
}
var url="chek_pwd.php";
url=url+"?q="+password;
url=url+"&sid="+Math.random();
xmlhttp.open("GET",url,true);
xmlhttp.send(null);
/*It has been assumed here that ajax_search.php is in the same directory.*/
xmlhttp.send(null);
}

function chek(image_name)
{
var xmlhttp;
var answer2;
if (window.XMLHttpRequest)
  {
  // code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else if (window.ActiveXObject)
  {
  // code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
else
  {
  alert("Sorry, your browser seems to not support XMLHTTP functionality.");
  }


xmlhttp.onreadystatechange=function()
{
if(xmlhttp.readyState==4)
  {
	answer2=xmlhttp.responseText;
	if(compare_strings(answer2,1)&&(document.getElementById("vercode").className=="success"))
	{
		document.getElementById("txtvercode").className="error";
		document.getElementById("vercode").className="error";
		document.getElementById("txtvercode").innerHTML="Incorrect";
		
	}
	else if(compare_strings(answer2,2))
	{
		document.getElementById("txtvercode").className="success";
		document.getElementById("vercode").className="success";
		document.getElementById("txtvercode").innerHTML="Correct";
		flag2=1;
	}	
  }

}
var url="verify_image.php";
url=url+"?q="+image_name;
url=url+"&sid="+Math.random();
xmlhttp.open("GET",url,true);
xmlhttp.send(null);
/*It has been assumed here that ajax_search.php is in the same directory.*/

}
function chek_failure(image_name)
{
var xmlhttp;
var answer2;
if (window.XMLHttpRequest)
  {
  // code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else if (window.ActiveXObject)
  {
  // code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
else
  {
  alert("Sorry, your browser seems to not support XMLHTTP functionality.");
  }


xmlhttp.onreadystatechange=function()
{
if(xmlhttp.readyState==4)
  {
	answer2=xmlhttp.responseText;
	if(compare_strings(answer2,1))
	{
		document.getElementById("txtvercode").className="error";
		document.getElementById("vercode").className="error";
		document.getElementById("txtvercode").innerHTML="Incorrect";
		
	}
	else if(compare_strings(answer2,2))
	{
		document.getElementById("txtvercode").className="success";
		document.getElementById("vercode").className="success";
		flag3=1;
		
	}
  }
}
var url="verify_image.php";
url=url+"?q="+image_name;
url=url+"&sid="+Math.random();
xmlhttp.open("GET",url,true);
xmlhttp.send(null);
/*It has been assumed here that ajax_search.php is in the same directory.*/

}

function check_password_failure()
{
if(document.getElementById("password").value != document.getElementById("conf_pwd").value)
{
 document.getElementById("match").className="error";
		document.getElementById("conf_pwd").className="error";
		document.getElementById("match").innerHTML="Passwords do not match";
		
}
}
function check_password_success()
{
if(document.getElementById("password").value == document.getElementById("conf_pwd").value)
{
 document.getElementById("match").className="success";
		document.getElementById("conf_pwd").className="success";
		document.getElementById("match").innerHTML="password matched";
		flag4=1;
		
}
 if((document.getElementById("conf_pwd").className == "success")&&(document.getElementById("password").value != document.getElementById("conf_pwd").value))
{
 document.getElementById("match").className="error";
		document.getElementById("conf_pwd").className="error";
		document.getElementById("match").innerHTML="Passwords do not match";
		
}
}

function validate_email()
{
  apos=document.getElementById("email").value.indexOf("@");
  dotpos=document.getElementById("email").value.lastIndexOf(".");
  if (apos<1||dotpos-apos<2)
    {
	document.getElementById("emailInfo").className="error";
		document.getElementById("email").className="error";
		document.getElementById("emailInfo").innerHTML="invalid email";
	}
  else 
  {
  document.getElementById("emailInfo").className="success";
		document.getElementById("email").className="success";
		document.getElementById("emailInfo").innerHTML="valid email";
		flag5=1;
  }
  }
  
  function validate_all()
  {
  if (flag==1 && flag1==1 && (flag2==1 || flag3==1) && flag4==1 && flag5==1)
  {
  //alert("Processing Request");	
  //alert("Registration Successful");
  return true;
  //document.forms["form2"].submit();
  //location.replace('chek1.php');       
  }
  else
  {
  alert("Errors in some entries.");
  if(flag!=1)
  {
  username.focus();
  return false;
  }
  if(flag1!=1)
  {
  password.focus();
  return false;
  }
  
  return false;
   //location.replace('register.php');
  }
  }
