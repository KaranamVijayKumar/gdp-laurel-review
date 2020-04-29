<?php require_once __DIR__ . '/../_global/sections.php' ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo html2text($title) ?></title>

    <!-- Font Awesome Icons -->
    <link href="<?php echo to('themes/laurelv1/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet"
          type="text/css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic'
          rel='stylesheet'
          type='text/css'>

    <!-- Plugin CSS -->
    <link href="<?php echo to('themes/laurelv1/vendor/magnific-popup/magnific-popup.css') ?>" rel="stylesheet">

    <!-- Theme CSS - Includes Bootstrap -->
    <link href="<?php echo to('themes/laurelv1/css/creative.min.css') ?>" rel="stylesheet">
    <link rel="apple-touch-icon" href="<?php echo to('themes/default/global/apple-touch-icon.png') ?>">
    <link rel="shortcut icon" href="<?php echo to('themes/default/global/favicon.ico') ?>"/>

    <!-- meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    if (isset($extra_head)) {
        echo $extra_head;
    }
    ?>
    <?php echo isset($page) && $page ? page_meta($page->meta) : ''; ?>
</head>

<body id="page-top">

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
    <div class="container">
        <h1 class="navbar-brand js-scroll-trigger">
            <a href="<?php echo to('') ?>" title="The Laurel Review">
                <span>The Laurel Review</span>
            </a>
        </h1>

        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                data-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div class="collapse navbar-collapse" id="navbarResponsive">

            <?php
            echo \Project\Support\MenuFactory::get(
                'nav-site',
                isset($selected) ? $selected : array(),
                array('class' => 'navbar-nav ml-auto my-2 my-lg-0')
            ) ?>


            <!-- <ul class="navbar-nav ml-auto my-2 my-lg-0">
                 <li class="nav-item">
                     <a class="nav-link js-scroll-trigger" href="https://thelaurelreview.submittable.com/submit">Submissions</a>
                 </li>
             </ul>-->
        </div>
    </div>
</nav>
<?php echo $engine->getSection('welcome-hero'); ?>


<section class="page-section">
    <div class="container">
        <div class="row justify-content-center">
            <!-- <div class="col-lg-8 text-center">
              <h2 class="mt-0">Let's Get In Touch!</h2>
              <hr class="divider my-4">
              <p class="text-muted mb-5">Ready to start your next project with us? Give us a call or send us an email and we will get back to you as soon as possible!</p>
            </div> -->
            <div class="col-lg-4 text-center">
                <iframe
                    src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fthelaurelreview%2F&tabs=timeline&width=340&height=500&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId"
                    style="border-left:20px solid #000;border-top:35px solid #000;border-right:20px solid #000;border-bottom:35px solid #000;border-radius:25px;overflow:hidden"
                    scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media" height="600"
                    width="400"></iframe>
            </div>
            <div class="col-lg-1 text-center"></div>
            <div class="col-lg-7 text">
                <div class="archive archive__report  archive__report--responsive">

                    <div class="media__body">
                        <?php echo isset($global_content) ? $global_content : '' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>

<footer class="global-footer">
    <hr>
    <div class="row col-lg-12">
        <?php include __DIR__ . '/../_global/nwmissouri.partial.php' ?>
        <div class="col-lg-1 "></div>
        <div class="col-lg-5 text">
            <?php echo $engine->getSection('nav-footer') ?>
            <?php include __DIR__ . '/../_global/footer_signature.partial.php' ?>

        </div>
		
    </div>
<hr>
</footer>

<!-- Bootstrap core JavaScript -->
<script src="<?php echo to('themes/laurelv1/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?php echo to('themes/laurelv1/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

<!-- Plugin JavaScript -->
<script src="<?php echo to('themes/laurelv1/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
<script src="<?php echo to('themes/laurelv1/vendor/magnific-popup/jquery.magnific-popup.min.js'); ?>"></script>


<!-- Custom scripts for this template -->
<script src="<?php echo to('themes/laurelv1/js/creative.min.js') ?>"></script>

</body>

</html>