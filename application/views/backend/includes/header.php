<?php
// Get current page filename without extension
$currentPage = basename($_SERVER['PHP_SELF'], ".php");

$currentPage = ($currentPage=="index") ? "home" : $currentPage ;
$currentPage = strtoupper($currentPage);
?>

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?php echo $currentPage.' | SNAT BURIAL SCHEME' ?></title>
  <meta name="description" content="">
  <meta name="keywords" content="">

<meta name="description" content="SNAT Burial Scheme provides financial and emotional support to teachers and their families in Eswatini since 2003. Dignified funeral cover, compassionate care, and trusted service.">

<meta name="keywords" content="SNAT Burial Scheme, Eswatini Teachers Burial, Funeral Cover Eswatini, SNAT, Teacher Support, Burial Scheme Swaziland">
<meta name="author" content="Sicelo Thabani Hlanze">
<meta name="robots" content="index, follow">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Open Graph / Facebook -->
<meta property="og:title" content="SNAT Burial Scheme – Supporting Teachers & Families">
<meta property="og:description" content="Providing dignity, financial relief, and compassionate support to Eswatini teachers since 2003.">
<meta property="og:image" content="https://snatburialscheme.com/assets/img/favicon.ico">
<meta property="og:type" content="website">
<meta property="og:url" content="https://snatburialscheme.com/">

<!-- Twitter -->
<meta name="twitter:title" content="SNAT Burial Scheme">
<meta name="twitter:description" content="Trusted funeral support for Eswatini teachers since 2003.">
<meta name="twitter:image" content="https://snatburialscheme.com/assets/img/favicon.ico">

  <!-- Favicons -->
  <link href="assets/img/favicon.ico" rel="icon">
  <link href="assets/img/favicon.ico" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
<style>
  body {
    background: linear-gradient(135deg, #f1f5ff, #f8f9fa);
  }

  .login-card {
    background: rgba(255, 255, 255, 0.96);
    border-radius: 1.25rem;
    backdrop-filter: blur(8px);
  }

  .login-image {
    width: 90px;
    height: 90px;
    object-fit: cover;
    border-radius: 1rem;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
  }

  .step-badge {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #0d6efd;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.85rem;
  }

  .divider {
    height: 1px;
    background: linear-gradient(to right, transparent, #dee2e6, transparent);
    margin: 1.5rem 0;
  }

  .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.25);
  }

  #otp-message {
    animation: fadeIn 0.3s ease-in-out;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-4px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>

  <!-- =======================================================
  * Author: Hlanze Sicelo Thabani
  ======================================================== -->
</head>