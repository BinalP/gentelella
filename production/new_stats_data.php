<?php
//Include GP config file && User class
include_once 'gpConfig.php';
include_once 'User.php';

if(isset($_GET['code'])){
  $gClient->authenticate($_GET['code']);
  $_SESSION['token'] = $gClient->getAccessToken();
  header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
  $gClient->setAccessToken($_SESSION['token']);
}


mysql_connect("localhost","root","") or die(mysql_error());
mysql_select_db("something2") or die(mysql_error());
$sid=$_GET['sid'];
if ($gClient->getAccessToken()) {
  //Get user profile data from google
  $gpUserProfile = $google_oauthV2->userinfo->get();
  
  //Initialize User class
  $user = new User();
  
  //Insert or update user data to the database
  $gpUserData = array(
    'oauth_provider'=> 'google',
    'oauth_uid'     => $gpUserProfile['id'],
    'first_name'    => $gpUserProfile['given_name'],
    'last_name'     => $gpUserProfile['family_name'],
    'email'         => $gpUserProfile['email'],
    //'gender'        => $gpUserProfile['gender'],
    'locale'        => $gpUserProfile['locale'],
    'picture'       => $gpUserProfile['picture'],
    //'link'          => $gpUserProfile['link']
  );
  $userData = $user->checkUser($gpUserData);
  
  //Storing user data into session
  $_SESSION['userData'] = $userData;
  
  //Render facebook profile data
  if(!empty($userData)){
    $output = '<h1>Google+ Profile Details </h1>';
    $output .= '<img src="'.$userData['picture'].'" width="300" height="220">';
    $output .= '<br/>Google ID : ' . $userData['oauth_uid'];
    $output .= '<br/>Name : ' . $userData['first_name'].' '.$userData['last_name'];
    $output .= '<br/>Email : ' . $userData['email'];
    //$output .= '<br/>Gender : ' . $userData['gender'];
    $output .= '<br/>Locale : ' . $userData['locale'];
    $output .= '<br/>Logged in with : Google';
    //$output .= '<br/><a href="'.$userData['link'].'" target="_blank">Click hiiiiiiiii Visit Google+ Page</a>';
    $output .= '<br/>Logout from <a href="logout.php">Google</a>';
    $output .= '<br/>Logout from <a href="index1.php">home</a>';
  }else{
    $output = '<h3 style="color:red">Some problem occurred, please try again.</h3>';
  }

$query=mysql_query("SELECT * FROM stats WHERE s_id='".$sid."'");
$row=mysql_fetch_array($query);
$t1=$row['x_type'];
$t2=$row['y_type'];
?>

<!DOCTYPE html>
<html lang="en">
  <head>
  <script> var t1="<?php echo $t1; ?>";
  var t2="<?php echo $t2; ?>";</script>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?php $userd = $_SESSION['userData'];
        echo $userd['first_name'];?></title>

  <!-- Bootstrap -->
  <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- iCheck -->
  <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
 
  <!-- bootstrap-progressbar -->
  <link href="../vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
  <!-- JQVMap -->
  <link href="../vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
  <!-- bootstrap-daterangepicker -->
  <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<!-- Datatables -->
    <link href="../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

  <!-- Custom Theme Style -->
  <link href="../build/css/custom.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
  <script type="text/javascript"> var temp=0;</script>
  <script type="text/javascript">
    $(document).ready(function(){
        $(".add-row").click(function(){
            var name = $("#X-value").val();
            var email = $("#Y-value").val();

            if(t1=='int' && isNaN(name)){}
            
              else{
            if(t2=='int' && isNaN(email)){}
              else{
            var markup = "<tr><td><input type='checkbox' name='record'></td><td><input type='text'  +  value=" + name + " name=x"+temp+ "></td><td><input type='text' + value=" + email + " name=y"+ temp+"></td></tr>";
            temp++;
            var ele=document.getElementById("try");
            var try2=temp.toString();
            ele.setAttribute("value",try2);
           

           
            $("table tbody").append(markup);
          }
        }
        });
        
        // Find and remove selected table rows
        $(".delete-row").click(function(){
            $("table tbody").find('input[name="record"]').each(function(){
              if($(this).is(":checked")){
                    $(this).parents("tr").remove();
                }
            });
        });
    
    });    
</script>
  </head>

  <body class="nav-md">
  <div class="container body">
    <div class="main_container">
    <div class="col-md-3 left_col menu_fixed">
      <div class="left_col scroll-view">
      <div class="navbar nav_title" style="border: 0;">
        <a href="index.html" class="site_title"><i class="fa fa-line-chart"></i> <span>MathTool</span></a>
      </div>

      <div class="clearfix"></div>

      <!-- menu profile quick info -->
      <div class="profile clearfix">
        <div class="profile_pic">
        <img src="<?php echo $userd['picture'];?>" alt="..." class="img-circle profile_img">
        </div>
        <div class="profile_info">
        <span>Welcome,</span>
        <h2><?php echo $userd['first_name']." ".$userd['last_name'];?></h2>
        </div>
      </div>
      <!-- /menu profile quick info -->

      <br />

      <!-- sidebar menu -->
      <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
        <div class="menu_section">
        <h3>General</h3>
        <ul class="nav side-menu">
          <li><a href="home.php"><i class="fa fa-home"></i> Home</a>
          </li>
          <li><a href="plot.php"><i class="fa fa-area-chart"></i> Plot an Equation</a>
          </li>
          <li><a href="project.php"><i class="fa fa-list"></i> My Projects</a>
          </li>
          <li><a href="notification.php"><i class="fa fa-envelope"></i> Notifications</a>
          </li>
          <li><a href="profile.php"><i class="fa fa-user"></i> My Profile</a>
          </li>
          <li><a href="contact_us.php"><i class="fa fa-paper-plane"></i> Contact Us</a>
          </li>
        </ul>
        </div>

      </div>
      <!-- /sidebar menu -->

      <!-- /menu footer buttons -->
      <div class="sidebar-footer hidden-small">
        <a data-toggle="tooltip" data-placement="top" title="Logout" href="logout.php">
        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
        </a>
      </div>
      <!-- /menu footer buttons -->
      </div>
    </div>


        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3><?php $row['s_name']?></h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Add data</h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />

                 <form>
        <input type="text" id="X-value" placeholder="X-value">
        <input type="text" id="Y-value" placeholder="Y-value">
      <input type="button" class="add-row" value="Add Row">
    </form>
                    <form id="demo-form"  class="form-horizontal form-label-left" method="post" action="add_data.php?sid=<?php echo $sid; ?>">

                        <div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Data:</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
      
              
                   
    <div style="margin-top: 30px;">
    <table id="datatable" class="table table-striped table-bordered" >

        <thead>
            <tr>
            <input type="" name="try" style="display: none;" id="try" value="-1">
                <th>Select</th>
                <th>X-value</th>
                <th>Y-value</th>
            </tr>
        </thead>
        <tbody>
         
        </tbody>
    </table>
    </div>
    <button type="button" class="delete-row">Delete Row</button>
                
 
      </div>
    </div>
  </div>
</div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                          
              
                          <button type="submit" class="btn btn-success" name="submit">Submit</button>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
            </div>


            


            
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        
        <!-- /footer content -->
      </div>
    </div>


    <!-- jQuery -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../vendors/nprogress/nprogress.js"></script>
      <!-- jQuery Smart Wizard -->
    <script src="../vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
     <!-- Chart.js -->
  <script src="../vendors/Chart.js/dist/Chart.min.js"></script>
  <!-- gauge.js -->
  <script src="../vendors/gauge.js/dist/gauge.min.js"></script>
  <!-- bootstrap-progressbar -->
  <script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
  <!-- iCheck -->
  <script src="../vendors/iCheck/icheck.min.js"></script>
  <!-- Skycons -->
  <script src="../vendors/skycons/skycons.js"></script>
  <!-- Flot -->
  <script src="../vendors/Flot/jquery.flot.js"></script>
  <script src="../vendors/Flot/jquery.flot.pie.js"></script>
  <script src="../vendors/Flot/jquery.flot.time.js"></script>
  <script src="../vendors/Flot/jquery.flot.stack.js"></script>
  <script src="../vendors/Flot/jquery.flot.resize.js"></script>
  <!-- Flot plugins -->
  <script src="../vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
  <script src="../vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
  <script src="../vendors/flot.curvedlines/curvedLines.js"></script>
  <!-- DateJS -->
  <script src="../vendors/DateJS/build/date.js"></script>
  <!-- JQVMap -->
  <script src="../vendors/jqvmap/dist/jquery.vmap.js"></script>
  <script src="../vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
  <script src="../vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
  <!-- bootstrap-daterangepicker -->
  <script src="../vendors/moment/min/moment.min.js"></script>
  <script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- iCheck -->
    <script src="../vendors/iCheck/icheck.min.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="../vendors/moment/min/moment.min.js"></script>
    <script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="../vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
    <script src="../vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
    <script src="../vendors/google-code-prettify/src/prettify.js"></script>
    <!-- jQuery Tags Input -->
    <script src="../vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
    <!-- Switchery -->
    <script src="../vendors/switchery/dist/switchery.min.js"></script>
    <!-- Select2 -->
    <script src="../vendors/select2/dist/js/select2.full.min.js"></script>
    <!-- Parsley -->
    <script src="../vendors/parsleyjs/dist/parsley.min.js"></script>
    <!-- Autosize -->
    <script src="../vendors/autosize/dist/autosize.min.js"></script>
    <!-- jQuery autocomplete -->
    <script src="../vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
    <!-- starrr -->
    <script src="../vendors/starrr/dist/starrr.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
  
  </body>
</html>

<?php
} else {
  $authUrl = $gClient->createAuthUrl();
  $output = '<a href="'.filter_var($authUrl, FILTER_SANITIZE_URL).'"><img src="images/glogin.png" alt=""/></a>';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>MathTool</title>

  <!-- Bootstrap -->
  <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- iCheck -->
  <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
 <!-- Datatables -->
    <script src="../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="../vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="../vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="../vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="../vendors/jszip/dist/jszip.min.js"></script>
    <script src="../vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="../vendors/pdfmake/build/vfs_fonts.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
  <!-- bootstrap-progressbar -->
  <link href="../vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
  <!-- JQVMap -->
  <link href="../vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
  <!-- bootstrap-daterangepicker -->
  <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

  <!-- Custom Theme Style -->
  <link href="../build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
  <div class="container body">
    <div class="main_container">
    <div class="col-md-3 left_col">
      <div class="left_col scroll-view">
      <div class="navbar nav_title" style="border: 0;">
        <a href="index.html" class="site_title"><i class="fa fa-line-chart"></i> <span>MathTool</span></a>
      </div>

      <div class="clearfix"></div>

      <br>

      <!-- sidebar menu -->
      <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
        <div class="menu_section">
        <h3>General</h3>
        <ul class="nav side-menu">
          <li><a href="home.php"><i class="fa fa-home"></i> Home</a>
          </li>
          <li><a href="plot.php"><i class="fa fa-area-chart"></i> Plot an Equation</a>
          <li><a href="index.php"><i class="fa fa-key"></i> Login/Register</a>
          </li>
          <li><a href="contact_us.php"><i class="fa fa-paper-plane"></i> Contact Us</a>
          </li>
        </ul>
        </div>

      </div>
      <!-- /sidebar menu -->
      </div>
    </div>

    <!-- top navigation -->
    <div class="top_nav">
      <div class="nav_menu">
      <nav>
        <div class="nav toggle">
        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
        </div>
              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search" style="padding-top: 20px;">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                    </span>
                  </div>
                </div>
              </div>
        </nav>
      </div>
    </div>
    <!-- /top navigation -->

    <!-- page content -->
    <div class="right_col" role="main">
      <!-- top tiles -->
      <!-- /top tiles -->

      <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        
      </div>

      </div>
      <br />

      <div class="row">


    

      <div class="col-md-4 col-sm-4 col-xs-12">
        
          <h2>Recent Projects</h2>
          
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <div class="dashboard-widget-content">

          <ul class="list-unstyled timeline widget">
          <?php
          $search = mysql_query("SELECT * FROM project NATURAL JOIN users") or die(mysql_error());
          while($row=mysql_fetch_array($search))
          {
          ?>
            <li>
            <div class="block">
              <div class="block_content">
              <h2 class="title">
                        <a><?php echo $row['p_name'];?></a>
                      </h2>
              <div class="byline">
                <span><?php echo $row['p_modified'];?></span> by <a><?php echo $row['first_name']." ".$row['last_name'];?></a>
              </div>
              <p class="excerpt"><?php echo $row['p_desc'];?>
              </p>
              </div>
            </div>
            </li>
            <?php
          }
            ?>
          </ul>
          
        </div>
      </div>
    </div>
    <!-- /page content -->
    </div>
  </div>

  <!-- jQuery -->
  <script src="../vendors/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- FastClick -->
  <script src="../vendors/fastclick/lib/fastclick.js"></script>
  <!-- NProgress -->
  <script src="../vendors/nprogress/nprogress.js"></script>
  <!-- Chart.js -->
  <script src="../vendors/Chart.js/dist/Chart.min.js"></script>
  <!-- gauge.js -->
  <script src="../vendors/gauge.js/dist/gauge.min.js"></script>
  <!-- bootstrap-progressbar -->
  <script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
  <!-- iCheck -->
  <script src="../vendors/iCheck/icheck.min.js"></script>
  <!-- Skycons -->
  <script src="../vendors/skycons/skycons.js"></script>
  <!-- Flot -->
  <script src="../vendors/Flot/jquery.flot.js"></script>
  <script src="../vendors/Flot/jquery.flot.pie.js"></script>
  <script src="../vendors/Flot/jquery.flot.time.js"></script>
  <script src="../vendors/Flot/jquery.flot.stack.js"></script>
  <script src="../vendors/Flot/jquery.flot.resize.js"></script>
  <!-- Flot plugins -->
  <script src="../vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
  <script src="../vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
  <script src="../vendors/flot.curvedlines/curvedLines.js"></script>
  <!-- DateJS -->
  <script src="../vendors/DateJS/build/date.js"></script>
  <!-- JQVMap -->
  <script src="../vendors/jqvmap/dist/jquery.vmap.js"></script>
  <script src="../vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
  <script src="../vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
  <!-- bootstrap-daterangepicker -->
  <script src="../vendors/moment/min/moment.min.js"></script>
  <script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

  <!-- Custom Theme Scripts -->
  <script src="../build/js/custom.min.js"></script>
 
  </body>
</html>
<?php
}
?>