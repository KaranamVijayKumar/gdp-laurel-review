<?php
// create links from the optional sections and create the needed textarea for them
if (count(\Project\Models\IssueContent::$optional_toc_sections)) {
    $optional_sections_links = \Project\Models\IssueContent::$optional_toc_sections;
    array_walk($optional_sections_links, function (&$name) {
        $name = '<a class="js-section-selector" href="#os-' . slug($name) .'">' . $name .'</a>';
    } );
    ?>
    <div class="layout__item 1/1">
        <h4 class="content-hero">
            <?php echo sprintf(_("Table of contents (TOC) sections: %s"), implode(', ', $optional_sections_links)) ?>

        </h4>
    </div>

    <?php foreach (\Project\Models\IssueContent::$optional_toc_sections as $key => $section) { $slug_section = slug($section); ?>
        <div class="layout__item 1/1 pt <?php echo array_key_exists('optional-section-'  . $slug_section, $errors) ? '' : 'hidden' ?>" id="os-<?php echo $slug_section ?>">
            <?php echo \Story\Form::label('optional-section-' . $slug_section, _(mb_convert_case($section, MB_CASE_TITLE)) . ' (' . _('optional') . ')') ?>

            <?php echo \Story\Form::textarea(
                'optional-section-'  . $slug_section,
                '',
                array(
                    'data-redactor-min_height' => '220',
                    'rows'                     => '20',
                    'class'                    => 'text-input 1/1 text-input--redactor  text-input--redactor-frontend',
                    'id'                       => 'optional-section-' . $slug_section,
                    'placeholder'              => _('Insert '. h($section) .' content here ...')
                )
            ) ?>
        </div>
    <?php } ?>

<?php } ?>
