<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title> SRMS | CBE Portal </title>
<link  rel="stylesheet" href="css/bootstrap.min.css"/>
 <link  rel="stylesheet" href="css/bootstrap-theme.min.css"/>
 <link rel="stylesheet" href="css/main.css">
 <link  rel="icon" href="image/logo.png">
 <link  rel="stylesheet" href="css/font.css">
 <script src="js/jquery.js" type="text/javascript"></script>
  <script src="js/bootstrap.min.js"  type="text/javascript"></script>
 	<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
<?php if(@$_GET['w'])
{echo'<script>alert("'.@$_GET['w'].'");</script>';}
include_once 'dbConnection.php';
?>
<script>
function validateForm() {
  var y = document.forms["form"]["fname"].value;	
  var letters = /^[A-Za-z]+$/;
  if (y == null || y == "") {alert("First Name must be filled out.");return false;}
  var z =document.forms["form"]["lname"].value;
  if (z == null || z == "") {alert("Last Name must be filled out.");return false;}
  var s =document.forms["form"]["sid"].value;
  if (s == null || z == "") {alert("Student ID must be filled out.");return false;}
  var x = document.forms["form"]["email"].value;
  var atpos = x.indexOf("@");
  var dotpos = x.lastIndexOf(".");
  if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {alert("Not a valid e-mail address.");return false;}
  var a = document.forms["form"]["password"].value;
  if(a == null || a == ""){alert("Password must be filled out");return false;}
  if(a.length<7 || a.length>25){alert("Passwords must be 7 to 25 characters long.");return false;}
  var b = document.forms["form"]["cpassword"].value;
  if (a!=b){alert("Passwords must match.");return false;} 
  {alert("Registeration Successful!!!");}
}
</script>
</head>

<body>
<div class="header">
    <div class="row">
        <div class="col-lg-12">
            <span title="" class="logo">CBE Portal</span>
        </div>
    </div>
</div>
<div class="col-md-2 col-md-offset-9">

<!-- Sign in modal start -->
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content title1">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title title1"><span style="color:orange;font-family:'typo' ">LOGIN</span> <!--<p class="pull-right title" style="padding-right: 50px;">Don't have an account? <a href="#register" data-dismiss="modal">Sign up!!</a></p> --></h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" action="login.php?q=index.php" method="POST">
          <fieldset>
            <!-- Text input-->
            <div class="form-group">
              <label class="col-md-3 control-label" for="email"></label>
              <div class="col-md-6">
                <input id="email" name="email" placeholder="Enter your email-id" class="form-control input-md" type="email" required="required">
              </div>
            </div>

            <!-- Password input-->
            <div class="form-group">
              <label class="col-md-3 control-label" for="password"></label>
              <div class="col-md-6">
                <input id="password" name="password" placeholder="Enter your Password" class="form-control input-md" type="password" required="required">
                <label style="margin-top: 7px" class="pull-center title"><input type="checkbox"> Remember me</label>
              </div>
            </div>
      </div>
      <div class="modal-footer">
        <a href="forgotpassword.php" class="pull-left title" style="color: #383737;">Forgot Password?</a>
        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
        <button type="submit" class="btn btn-primary">Log in</button>
          </fieldset>
        </form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Sign in modal closed -->

<script>
$(document).ready(function() {
    $('#myModal').modal({
        backdrop: 'static',
        keyboard: false
    });
    $('#myModal').modal('show');
});
</script>
<!-- <script>
function myFunction() {
  alert("Visit The ICT Team with your registered email to rest your Password!!");
}
</script> -->
</div><!--header row closed-->
</div>

<div class="bg1">
<div class="row">
<div class="col-md-7">
  <!-- <div style="padding-left: 80px; padding: 90px;"><iframe width="600" height="400" src="https://www.youtube.com/embed/StLZH0vmbSY" frameborder="1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div> -->
</div>

<div class="col-md-4 panel" style="border-radius: 2em;">
<!-- sign in form begins -->
 <form class="form-horizontal" name="form" action="sign.php?q=account.php" onSubmit="return validateForm()" method="POST" >
<fieldset>
<!-- Text input-->
 <h1 class="modal-title title1"><span style="color:orange;font-family:'typo'">REGISTRATION</span></h1><br>
<div class="form-group">
  <label class="col-md-12 control-label" for="fname"></label>
  <div class="col-md-12">
  <input id="fname" name="fname" placeholder="Enter your First Name" class="form-control input-md" type="text">

  </div>
</div>

<div class="form-group">
  <label class="col-md-12 control-label" for="lname"></label>
  <div class="col-md-12">
  <input id="lname" name="lname" placeholder="Enter your Last Name" class="form-control input-md" type="text">

  </div>
</div>

<div class="form-group">
  <label class="col-md-12 control-label" for="sid"></label>
  <div class="col-md-12">
  <input id="sid" name="sid" placeholder="Enter your Student ID" class="form-control input-md" type="text">

  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="gender"></label>
  <div class="col-md-12">
    <select id="gender" name="gender" class="form-control input-md" >
   <option value="Male">Select Gender</option>
  <option value="M">Male</option>
  <option value="F">Female</option> </select>
  </div>
</div>

<!-- Text input-->
 <div class="form-group">
  <label class="col-md-12 control-label" for="classid"></label>
  <div class="col-md-12">
    <select id="classid" name="classid" class="form-control input-md">
      <option value="Class">Select Class</option>
      <option value="JSS1A">JSS1A</option>
      <option value="JSS1B">JSS1B</option>
      <option value="JSS2A">JSS2A</option>
      <option value="JSS2B">JSS2B</option>
      <option value="JSS2C">JSS2C</option>
      <option value="JSS3A">JSS3A</option>
      <option value="JSS3B">JSS3B</option>
      <option value="SS1A">SS1A</option>
      <option value="SS1B">SS1B</option>
      <option value="SS2A">SS2A</option>
      <option value="SS2B">SS2B</option>
      <option value="SS3">SS3A</option>
    </select>
  </div>
</div>


<!-- Text input-->
 <div class="form-group">
  <label class="col-md-12 control-label title1" for="email"></label>
  <div class="col-md-12">
    <input id="email" name="email" placeholder="Enter your email address" class="form-control input-md" type="email">

  </div>
</div> 

<!-- Text input-->
 <div class="form-group">
  <label class="col-md-12 control-label" for="mobile"></label>
  <div class="col-md-12">
  <input id="mobile" name="mobile" placeholder="Enter your mobile number" class="form-control input-md" type="number">

  </div>
</div>


<!-- Text input-->
 <div class="form-group">
  <label class="col-md-12 control-label" for="password"></label>
  <div class="col-md-12">
    <input id="password" name="password" placeholder="Enter your password" class="form-control input-md" type="password">

  </div>
</div>

<div class="form-group">
  <label class="col-md-12control-label" for="cpassword"></label>
  <div class="col-md-12">
    <input id="cpassword" name="cpassword" placeholder="Confirm Password" class="form-control input-md" type="password">
  </div>
</div>


<?php if(@$_GET['q7'])
{ echo'<p style="color:red;font-size:15px;">'.@$_GET['q7'];}?>
<!-- Button -->
 <div class="form-group">
  <label class="col-md-12 control-label" for=""></label>
  <div class="col-md-12">
    <input  type="submit" style="outline: none; border-radius: 8em; padding: 0.5em 5em; font-family: 'typo'" value="Sign up" class="btn btn-primary"/>
  </div>
</div>

 </fieldset>
</form>
</div><!--col-md-6 end-->
</div></div>
</div><!--container end-->

<!--Footer start-->
<div class="row footer">
<div class="col-md-4 box">
<a href="#" data-toggle="modal" data-target="#abtus">About Us</a>
</div>
<div class="col-md-4 box">
<a href="#" data-toggle="modal" data-target="#developers">Developers</a>
</div>
<div class="col-md-4 box">
<a href="feedback.php" target="_blank">Feedback</a></div></div>

<!-- Modal For Developers-->
<div class="modal fade title1" id="developers">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" style="font-family:'typo' "><span style="color:orange">Developers</span></h4>
      </div>

      <div class="modal-body">
        <p>
		<div class="row">
		<div class="col-md-4">
		 <img src="image/developer.jpg" width=100 height=100 alt="Developer" title="Dick Bassey" class="img-rounded">
		 </div>
		 <div class="col-md-5">
		<a href="https://www.facebook.com/emmanuel.itighise.7" target="_blank" style="color:#202020; font-family:'typo' ; font-size:18px" title="Find on Facebook">Emmanuel Itighise</a>
		<h4 style="color:#202020; font-family:'typo' ;font-size:16px" class="title1">+234 8161330031</h4>
		<h4 style="font-family:'typo' ">mcbassboy247@gmail.com</h4>
		<h4 style="font-family:'typo' ">Senior Programmer At Aptech ,Nigeria.</h4></div></div>
		</p>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--Modal For About Us -->
<div class="modal fade title1" id="abtus">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h1 class="modal-title" style="font-family:'typo' "><span style="color:orange"><center><b>About Us</b></center></span></h1>
      </div>

      <div class="modal-body">
        <p>
    <div class="row">
    <div class="col-md-4">
     <img src="image/bg2.jpg" width=100 height=100 alt="About Us" title="About Us Interface" class="img-rounded">
     </div>
     <div class="col-md-5">
    <a style="color:orange; font-family:'typo' ; font-size:20px" title="Find on Facebook"><b>Dominican Portal</b></a>
    <h4 style="color:#202020; font-family:'typo' ;font-size:16px" class="">Dominican Portal Is A Cbt Programme System Developed By AnonymouX. Its Aim Is To Bring The Students And Teachers Into A Circle Of Vast Experience, where Teachers Can Set Quiz And Monitor The Students as well as Study Thier Ranking And Students Can Also Submit Feedback To The Teacher and Developers On Issues Experienced During the E-Excercise.</h4>
    <h4 style="font-family:'typo' ">Copyright &copy;<script>document.write(new Date().getFullYear());</script> - <a href="mailto:mcbassboy247@gmail.com">mcbassboy247@gmail.com</a></h4></div></div>
    </p>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--Modal for admin login-->
	 <div class="modal fade" id="login">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"></div>
      </div>
      <!--<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>-->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--footer end-->


</body>
</html>
