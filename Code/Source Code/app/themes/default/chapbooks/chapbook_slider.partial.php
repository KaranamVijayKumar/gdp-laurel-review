<?php if (count($items)) { ?>
    <!-- .hero -->
    <div class="hero">
        <ul class="nav nav--stacked slider hero__slider" data-wsslider-change="<?php echo $change; ?>">
        <?php
            foreach ($items as $item) { ?>
                <li>
                    <!-- .hero-slide -->
                    <div class="layout m0 hero-slide">
                        <div class="layout__item <?php echo isset($item->highlights) && count($item->highlights) ? '1/2' : '1/1' ?> hero-slide__left">

                            <h2 class="hero-slide__title">
                                <?php
                                echo link_to(
                                    action('\Project\Controllers\Chapbooks\Index', array($item->slug)),
                                    $item->title
                                ) ?>
                            </h2>
                            <div class="hero-slide__body hero-slide__body--description">
                                <?php echo $item->content ?>
                            </div>
                            <div class="mt- hero-slide__footer">
                                <?php
                                echo link_to(
                                    action('\Project\Controllers\Chapbooks\Index', array($item->slug)),
                                    _('More'),
                                    array('class' => 'btn btn--wide btn--positive mr')
                                ) ?>
                        <?php
                                if (has_access('chapbooks_order')) { ?>
                                    <?php
                                    echo link_to(
                                        action('\Project\Controllers\Chapbooks\Order', array($item->slug)),
                                        _('Order'),
                                        array('class' => 'btn btn--wide btn--alert')
                                    ) ?>
                                    <?php
                                } ?>
                            </div>
                        </div><!--
                <?php
                        if (isset($item->highlights) && count($item->highlights)) { ?>
                         --><div class="layout__item 1/2 hero-slide__right">
                                <ul class="nav nav--stacked slider highlights"
                                    data-wsslider-change="<?php echo $content_change; ?>"
                                    data-wsslider-enable-navigation="0">
                        <?php
                                    foreach ($item->highlights as $highlight) { ?>

                                        <li>
                                            <h4 class="hero-slide__subtitle">
                                                <span class="title"><?php echo $highlight->title; ?></span>
                                                <span class="author">by <?php echo $highlight->author; ?></span>
                                            </h4>
                                            <div class="hero-slide__subbody hero-slide__subbody--description">
                                                <?php echo $highlight->content ?>

                                            </div>
                                            <?php
                                            echo link_to(
                                                action(
                                                    '\Project\Controllers\Chapbooks\TocContent',
                                                    array($item->slug, $highlight->slug)
                                                ),
                                                _('Read more'),
                                                array('class' => 'read-more')
                                            ) ?>
                                        </li>
                        <?php
                                    } ?>
                                </ul>
                            </div><!--
                <?php
                        } ?>
                        -->
                    </div><!-- /.hero-slide -->
                </li>
        <?php
            } ?>
        </ul>
    </div>
    <!-- /.hero -->
<?php } ?>
