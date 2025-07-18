<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["mgrloggedin"]) || $_SESSION["mgrloggedin"] !== true){
    header("location: mgr-login.php");
    exit;
}
// Include config file
require_once "config.php";
 
$sub_day_left = 0 ;

$mgr_id =  $_SESSION["mgr_id"] ;
$sub_day_left =  $_SESSION["sub_day_left"] ;
$tjproUser = $_SESSION["tjproUser"] ;
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <title>TimeJet | Manager Dashboard</title>

    <!--
    <link rel="stylesheet" href="css/style3.css" type="text/css">
    <link rel="stylesheet" href="css/styleBoot.css" type="text/css">


    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="screen">  
    <link rel="stylesheet" type="text/css" href="css/DT_bootstrap.css">

    <script src="js/jquery.js" type="text/javascript"></script>
    <script src="js/bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" charset="utf-8" language="javascript" src="js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf-8" language="javascript" src="js/DT_bootstrap.js"></script>
    
    <style>
    
    #header {
        background-color: #eee;
        border-bottom: 1px solid #e6e6e6;
        padding: 40px 0;
    }
    </style>
-->
    
    <?php include('header.php'); ?>
    <div id="ad">
        <?php if ($tjproUser != true){ include('ad.php');  } ?>
    </div>
</head>
<body>

    <nav class="navbar fixed-top navbar-expand-sm bg-light navbar-light">
        <div class="container justify-content-center">
            <a class="navbar-brand" href="index.php">
            <img src="images/logo2.png" width="50" height="40" class="d-inline-block align-top" alt="">
            </a>
            <a class="navbar-brand mr-auto" href="index.php">TimeJet</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav mr-auto mt-2 mt-lg-0">
                <a class="nav-item nav-link active" href="mgr-dash.php">Dashboard <span class="sr-only">(current)</span></a>
                <a class="nav-item nav-link" href="mgr-activity-list.php">Activites</a>
                <a class="nav-item nav-link" href="mgr-report.php">Reports</a>
                <a class="nav-item nav-link" href="mgr-reset-pwd.php">Reset Password</a>
                <a class="nav-item nav-link" href="mgr-logout.php">Log out</a>
                </div>
            </div>
        </div>
    </nav>

    <br>
	<br>
	<br>
    
    <div class="container justify-content-center">
        
        <div class="card">
            <div class="card-body">

                <!--SUSCRIPTION WARNING-->
                <div id ="subWarning" class="alert alert-warning small" role="alert">
                    Your subscription is about to expire in  <strong id="lblSubDays"><?php echo $sub_day_left ; ?> </strong> Day(s).  <a href="https://www.fiverr.com/dlohia/create-subscription-for-time-jet-pro" style="color:#721c24" target="_blank"><strong>  Renew now. </strong> </a>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>

                <!--SUSCRIPTION EXPIRED-->
                <div id = "subExpired" class="alert alert-danger small" role="alert">
                    Your subscription has <strong> expired.  <a href="https://www.fiverr.com/dlohia/create-subscription-for-time-jet-pro" style="color:#721c24" target="_blank"> Renew now. </a></strong>

                    <input type="image" onclick="refreshSubscription()" src="https://timejet.dlohia.com/images/refresh_2.png"  height="20px" />

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>


                <br>
                <h4 style="text-align:left;">
                    <?php echo htmlspecialchars($_SESSION["mgr_id"]); ?>: employee logins 
                </h4>
                <table class="table table-striped table-bordered" id="loginTable">
                    <thead>
                        <tr>
                        <th>ID</th>
                            <th>Emp ID</th>
                            <th>Emp Login/Logout Time</th>
                            <th>Last Activity</th>
                            <th>Since Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM tj_login WHERE mgr_id = '$mgr_id' 
                                        AND user_type ='emp' ";

                        $records = mysqli_query($conn, $sql);  // Use select query here 

                            while($row = mysqli_fetch_array($records)){
                                $id = $row['id'];
                                $name =$row['emp_id'];
                                ?>
                            <tr class="delete_mem<?php echo $id ?>">
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['emp_id']; ?></td>
                                <td><?php echo $row['emp_loginlogout_dttm']; ?></td>
                                <td><?php echo $row['activity']; ?></td>
                                <td><?php echo $row['since_time']; ?></td>

                                <td width="80">
                                <a class="btn btn-danger" name="<?php echo $name; ?>" id="<?php echo $id; ?>">Delete user</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>      
        </div>     
    </div>   
            
    <footer class="bg-light text-center text-lg-start">
		<div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0);text-align: right;">
			© 2022
			<a class="text-dark" href="https://dlohia.com/">DLA. </a>
			All Rights Reserved
		</div>
	</footer> 

<script type="text/javascript">
    //$(document).ready(function() {

        loadDefaults();

        function loadDefaults(){
            var sub_day_left =  "<?php echo $sub_day_left ?>" ;  
            subAdj(sub_day_left);
        };

        function subAdj(sub_day_left){
            $(':input[id="loginTable"]').prop('disabled', true);

            $("#subExpired").hide();
            $("#subWarning").hide();

            if (sub_day_left <= 0) {
                $("#subExpired").show();
            }
            else if (sub_day_left <=7) {
                $("#subWarning").show();
            }
            else{
                $("#ad").hide();
            }
        }

        function refreshSubscription(){
            if (validSubscription() == true){
                alert("Subscription activated.");
            }
            else{
                alert("no subscription found.");
            }
        }

        function validSubscription() {
            var mgr_id  = "<?php echo $mgr_id ?>" ;   ;
            var isValid = false ;
            
            $.ajax({
                type: "POST",
                url: "subscription-check.php",
                async:false,
                data: ({
                    'mgr_id': mgr_id
                }),
                cache: false,
                success: function(response) {
                    //alert(response);
                    //if(response.search("valid") > 0){
                    response = Number(response);
                    //alert (response);
                    subAdj(response);
                    if (response > 0){
                        isValid = true;
                    }
                }
            });
            return isValid;
        };


        $('.btn-danger').click(function() {
            var del_id = $(this).attr("id");
            var emp_id = $(this).attr("name");
            
            if (confirm("delete login info of user : " + emp_id + " ( id:"  +  del_id + ")" )) {
                $.ajax({
                    type: "POST",
                    url: "mgr-user-del.php",
                    data: ({
                        del_id: del_id
                    }),
                    cache: false,
                    success: function(html) {
                        $(".delete_mem" + del_id).fadeOut('slow');
                    }
                });
            } else {
                return false;
            }
        });
    //});
</script>
    
</body>
</html>




