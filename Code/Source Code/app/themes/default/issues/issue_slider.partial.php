<?php if (count($items)) { ?>
    <header class="masthead">
        <div class="container h-100">
            <div class="row h-100 align-items-center justify-content-center text-center">
		  
		   <div class="col-lg-4 align-self-end">
		   <img src="<?php echo to('themes/laurelv1/img/LaurelImg1.jpeg') ?>" alt="" height="200" width="200">
		   </div>
		  	       <?php
                foreach ($items as $item) { ?>
				
                    <div class="col-lg-10 align-self-end">
					
                        <h1 class="text-uppercase text-white font-weight-bold"><?php
                            echo link_to(
                                action('\Project\Controllers\Issues\Index', array($item->slug)),
                                $item->title
                            ) ?></h1>
                        <hr class="divider my-4">
                    </div>
                    <div class="col-lg-8 align-self-baseline">
                        <p class="text-white-75 font-weight-light mb-5"><?php echo $item->content ?></p>
                        <?php echo link_to(
                            action('\Project\Controllers\Issues\Index', array($item->slug)),
                            _('Find Out More'),
                            array('class' => 'btn btn-primary btn-xl js-scroll-trigger')
                        ) ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </header>

<?php } ?>

