<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta http-equiv="Content-Security-Policy" content="default-src 'none'; script-src 'self'; connect-src 'self'; img-src 'self'; style-src 'self'; font-src 'self';">

    <title><?php echo $pageTitle ?></title>

    <link href="<?php echo public_path('/css/bootstrap.min.css') ?>" rel="stylesheet" id="bootstrap-css">
    <link href="<?php echo public_path('/css/font-awesome.min.css') ?>" rel="stylesheet">
    <link href="<?php echo public_path('/css/index.css') ?>" rel="stylesheet">

</head>

<body>

<?php echo csrf_token(); ?>