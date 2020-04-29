<?php
// create links from the optional sections and create the needed textarea for them

if (count($optional_sections)) { ?>

    <?php
        foreach ($optional_sections as $key => $section) { ?>
            <div class="1/1">
                <?php
                echo \Story\Form::label(
                    'optional-section-' . $section,
                    _(mb_convert_case($section, MB_CASE_TITLE))
                ) ?>

                <?php
                echo \Story\Form::textarea(
                    'optional-section-' . $section,
                    $page_content->findBy('name', $section, $default_content)->attributes['content'],
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
