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
<?php 
$pid=$_GET['pid'];
$name=$_POST['sname'];
$xlabel=$_POST['xlabel'];
$ylabel=$_POST['ylabel'];
$xtype=$_POST['xtype'];
$ytype=$_POST['ytype'];
$stype=$_POST['stype'];
$des=$_POST['description'];
mysql_connect("localhost","root","") or die(mysql_error());
mysql_select_db("something2") or die(mysql_error());
//$query=mysql_query("INSERT INTO circled(USERNAME,CIRCLED_USER) VALUES ('$circling','$user')") or die(mysql_error());
    //header("location:blogger.php?user=$user&hash=$hash");
if(!isset($des) || empty($des)){
  $des=null;
}
if(!isset($xlabel) || empty($xlabel)){
  $xlabel='X label';
}
if(!isset($ylabel) || empty($ylabel)){
  $ylabel='Y label';
}
if(!isset($xtype) || empty($xtype)){
  $xtype='text';
}
if(!isset($ytype) || empty($ytype)){
  $ytype='text';
}
if(!isset($stype) || empty($stype)){
  $stype='line';
}

$query=mysql_query("INSERT INTO stats(p_id,s_desc,x_type,y_type,x_label,y_label,s_name,s_type) VALUES('$pid','$des','$xtype','$ytype','$xlabel','$ylabel','$name','$stype')") or die(mysql_error());

$sid=mysql_insert_id();
header("location:new_stats_data.php?pid=$pid&sid=$sid");

?>
<?php
} else {
  $authUrl = $gClient->createAuthUrl();
  $output = '<a href="'.filter_var($authUrl, FILTER_SANITIZE_URL).'"><img src="images/glogin.png" alt=""/></a>';
?>
<?php
}
?>

