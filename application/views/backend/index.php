<?php
$account_type       =	$this->session->userdata('login_type');
include 'public_includes/header.php';
?>
<body>
<?php include $account_type.'/navigation.php';?>
<main class="main">

<!-- Page Title -->
<div class="page-title" data-aos="fade">
  <div class="container">
    <nav class="breadcrumbs">
      <ol>
        <li><a href="<?php echo base_url(); ?>">Home</a></li>
        <li class="current"><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></li>
      </ol>
    </nav>
    <h1><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></h1>
  </div>
</div><!-- End Page Title -->

<div class="container">
  <?php 
  // Dynamically include the page based on account_type and page_name
  if (isset($page_name) && !empty($page_name)) {
    $page_file = $account_type.'/'.$page_name.'.php';
    if (file_exists($page_file)) {
      include $page_file;
    } else {
      // Fallback: try with full path
      $full_path = APPPATH.'views/backend/'.$account_type.'/'.$page_name.'.php';
      if (file_exists($full_path)) {
        include $full_path;
      } else {
        echo '<div class="alert alert-warning">Page not found: '.$page_file.'</div>';
      }
    }
  } else {
    // Default to dashboard if page_name is not set
    $default_file = $account_type.'/dashboard.php';
    if (file_exists($default_file)) {
      include $default_file;
    } else {
      echo '<div class="alert alert-warning">Page name not specified and default dashboard not found.</div>';
    }
  }
  ?>
</div>

</main>

<?php include $account_type.'/footer.php';?>
</body>