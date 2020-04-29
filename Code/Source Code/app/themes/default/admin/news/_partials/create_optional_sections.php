<?php
// create links from the optional sections and create the needed textarea for them

if (count($optional_sections)) {
    $optional_sections_links = $optional_sections;
    array_walk(
        $optional_sections_links,
        function (&$name) {

            $name = '<a class="js-section-selector" href="#os-' . $name . '">' . $name . '</a>';
        }
    );
    ?>
    <h4 class="layout__item 1/1 content-hero">
        <?php echo sprintf(_("Optional news article sections: %s"), implode(', ', $optional_sections_links)) ?>
    </h4>
    <?php
        foreach ($optional_sections as $key => $section) { ?>
            <div class="layout__item 1/1 pt <?php
            echo array_key_exists(
                'optional-section-' . $section,
                $errors
            ) ? '' : 'hidden' ?>" id="os-<?php echo $section ?>">
                <?php
                echo \Story\Form::label(
                    'optional-section-' . $section,
                    _(mb_convert_case($section, MB_CASE_TITLE)) . ' (' . _('optional') . ')'
                ) ?>

                <?php
                echo \Story\Form::textarea(
                    'optional-section-' . $section,
                    '',
                    array(
                        'rows'                     => '20',
                        'class'                    => 'text-input 1/1 text-input--redactor  text-input--redactor-frontend',
                        'id'                       => 'optional-section-' . $section,
                        'placeholder'              => _('Insert ' . h($section) . ' content here ...')
                    )
                ) ?>
            </div>
    <?php
        } ?>
    <?php
}
