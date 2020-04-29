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
    <link rel="stylesheet" href="<?php echo to('themes/default/global/style.css') ?>" media="all">
    <!-- print style -->
    <link rel="stylesheet" href="<?php echo to('themes/default/global/print.css') ?>" media="print">
    <!-- meta tags -->
    <?php $findPage = array('1','15');
    if (in_array($this->main_page_content->attributes['page_id'],$findPage)) { ?>
        <link rel="stylesheet" href="<?php echo to('themes/laurelv2/aboutcss.css') ?>">

        <link rel="stylesheet" href="http://qua.laurelreview.org/themes/laurelv2/podcast/fonts/icomoon/style.css">
        <!--<link rel="stylesheet" href="http://localhost/Cigna/themes/laurelv2/podcast/css/bootstrap.min.css">-->
        <link rel="stylesheet" href="http://qua.laurelreview.org/themes/laurelv2/podcast/css/magnific-popup.css">
        <link rel="stylesheet" href="http://qua.laurelreview.org/themes/laurelv2/podcast/css/jquery-ui.css">
        <link rel="stylesheet" href="http://qua.laurelreview.org/themes/laurelv2/podcast/css/owl.carousel.min.css">
        <link rel="stylesheet" href="http://qua.laurelreview.org/themes/laurelv2/podcast/css/owl.theme.default.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mediaelement@4.2.7/build/mediaelementplayer.min.css">
        <!--<link rel="stylesheet" href="http://qua.laurelreview.org/themes/laurelv2/podcast/css/aos.css">-->
        <link rel="stylesheet" href="http://qua.laurelreview.org/themes/laurelv2/podcast/css/style.css">
    <?php } ?>

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
<div class="site-wrap">


    <?php
    $findPage = array('15','878');
    if (in_array($this->main_page_content->attributes['page_id'], $findPage)) { ?>

        <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
            <div class="container">
                <a class="navbar-brand js-scroll-trigger" href="<?php echo to('') ?>">THE LAUREL REVIEW</a>
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

                </div>
            </div>
        </nav>

        <div class="site-blocks-cover overlay"
             style="background-image: url(http://qua.laurelreview.org/themes/laurelv2/podcast/images/hero_bg_1.jpg);"
             data-aos="fade" data-stellar-background-ratio="0.5">
            <div class="container">
                <div class="row align-items-center justify-content-center text-center">

                    <div class="col-md-8" data-aos="fade-up" data-aos-delay="400">
                        <h2 class="text-white font-weight-light mb-2 display-4">Podcast</h2>
                    </div>
                </div>
            </div>
        </div>

    <?php } else { ?>


        <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3 navbar-scrolled" id="mainNav">
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
        <br><br><br><br>
        <div class="page-bar container u-mt-">
            <div class="flag flag--responsive flag--rev">
                <div class="flag__img u-1-of-3 u-1-of-1-palm text--right">
                    <?php echo $engine->getSection('nav--account') ?>
                </div>
                <div class="flag__body">
                    <h2 class="page-bar__title">
                        <?php echo \Story\HTML::link(\Story\URL::current(), ellipsize($title, 50, .5)) ?>
                    </h2>
                </div>
            </div>
        </div>

        <!-- /.page-bar -->
        <!-- .breadcrumb -->
        <ol class="nav  breadcrumb container u-mb- content-hero">
            <?php
            if (isset($breadcrumbs)) {
                foreach ($breadcrumbs as $index => $breadcrumb) {
                    ?>
                    <li <?php echo $index + 1 === count($breadcrumbs) ? 'class="current"' : '' ?>>
                        <?php echo $breadcrumb ?>
                    </li>
                    <?php
                }
            } ?>
        </ol><!-- /.breadcrumb -->
    <?php } ?>


    <!-- .global-content -->
    <div class="global-content">
        <div class="media media--rev media--responsive">
            <?php
            // global content side
            if (isset($global_content_aside)) {
                ?>
                <div class="media__img  u-1-of-4 u-1-of-1-palm <?php
                echo isset($palm_hidden) ? 'palm--hidden' : '' ?>">
                    <?php echo $global_content_aside ?>
                </div>
                <?php
            } ?>

            <div class="media__body">
			<?php 
			if($page->slug=="about") {
                require("aboutus.php");
            }else if($page->slug=="podcast") {
                require("podcast_vw.php");
            } else {
                echo isset($global_content) ? $global_content : '';
            }

           /* echo "<pre>";
			PRINT_R($page->slug);
			PRINT_R($this->name);
			echo "</pre>";*/
			
			?>
                <?php //echo isset($global_content) ? $global_content : '' ?>
            </div>
        </div>
    </div><!-- /.global-content -->
<style>
.university-logo{}
</style>
    <footer class="global-footer">
        <hr>
        <div class="row col-lg-12">
           <div class="col-lg-3 image" style="padding-left: 22px;" style="
    "> 
    <img src="http://laurelreview.org/themes/default/global/N-Horiz-B.png"
         alt="Northwest Missouri State University"style="width: 255px;" height='70px' width='250px' class=""/>
</div>
<div class="col-lg-3 text" style="
    padding-right: 42px;
    padding-left: 28px;
">
    <div class="row">
        <div class="fn n">
            <span class="given-name">Northwest State Missouri University</span>
        </div>
    </div>
    <div class="row">
        <div class="fn n">
            <span class="given-name">800 University Drive</span>
        </div>
    </div>
    <div class="row">
        <div class="fn n">
            <span class="given-name">Maryville, 64468</span>
        </div>
    </div>

</div>
            <div class="col-lg-1 "></div>
            <div class="col-lg-5 text">
                <?php echo $engine->getSection('nav-footer') ?>
                <?php include __DIR__ . '/../_global/footer_signature.partial.php' ?>

            </div>
        </div>

    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="<?php echo to('themes/laurelv1/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?php echo to('themes/laurelv1/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <!-- Plugin JavaScript -->
    <script src="<?php echo to('themes/laurelv1/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
    <script src="<?php echo to('themes/laurelv1/vendor/magnific-popup/jquery.magnific-popup.min.js'); ?>"></script>


    <!-- Custom scripts for this template -->
    <!--<script src="<?php /*echo to('themes/laurelv1/js/creative.min.js') */ ?>"></script>-->

</body>

</html>