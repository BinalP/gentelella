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



?>

<!DOCTYPE html>
<html lang="en">
  <head>
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

    <!-- top navigation -->
    <div class="top_nav">
      <div class="nav_menu">
      <nav>
        <div class="nav toggle">
        <a id="menu_toggle"><i class="fa fa-navicon"></i></a>
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
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Projects</h3>
              </div>
            </div>
            
            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_content">

                    <!-- start project list -->
                    <table class="table table-striped projects">
                      <thead>
                        <tr>
                          <th style="width: 1%">#</th>
                          <th style="width: 20%">Project Name</th>
                          <th style="width: 20%">#Edit</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                      $proj = mysql_query("SELECT * FROM project JOIN users ON project.u_id=users.id WHERE project.u_id='".$userd['id']."'") or die(mysql_error());
                      while($row=mysql_fetch_array($proj))
                      {
                      ?>
                        <tr>
                          <td>#</td>
                          <td>
                            <a><?php echo $row['p_name'];?></a>
                            <br />
                            <small>Created <?php echo $row['p_date'];?></small><br>
                            <small><?php echo $row['p_desc'];?></small>
                          </td>
                          <td>
                            
                            <a href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>
                          </td>
                        </tr>
                        <?php
                        }
                        ?>
                      </tbody>
                    </table>
                    <!-- end project list -->

                  </div>
                </div>
              </div>
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
    <!-- bootstrap-progressbar -->
    <script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
  </body>
</html>
<?php
} else {
  $authUrl = $gClient->createAuthUrl();
  $output = '<a href="'.filter_var($authUrl, FILTER_SANITIZE_URL).'"><img src="images/glogin.png" alt=""/></a>';
  }