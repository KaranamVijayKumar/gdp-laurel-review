<?php if ($chapbook->toc && count($chapbook->toc)) { ?>

    <h3 class="text--secondary"><?php echo _('Table of Contents') ?></h3>

    <?php
        foreach ($chapbook->toc as $toc) { ?>

        <?php
                if ($toc->is_header) { ?>
                    <h4 class="text--positive text--user"><?php echo $toc->content ?></h4>
        <?php
                } else { ?>

                    <div class="layout text--user">
                        <div class="layout__item u-1-of-4">
                            <strong><?php echo $toc->content ?></strong>
                        </div><!--
                     --><div class="layout__item u-3-of-4 u-1-of-1-palm">
                            <?php
                                if (isset($toc->titles) && $toc->titles) { ?>

                                    <ol class="list-bare">
                                        <?php
                                            foreach ($toc->titles as $toc_title) { ?>
                                                <li class="u-pb-">
                                                    <?php echo $toc_title->link ?: $toc_title->content ?>
                                                </li>
                                        <?php
                                            } ?>
                                    </ol>

                            <?php
                                } ?>
                        </div>
                    </div>
        <?php
                } ?>
    <?php
        } ?>
    <?php
}
