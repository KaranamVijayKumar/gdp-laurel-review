<?php

// we unset the headline and content from the required sections and display a texarea for the
// remaining sections
foreach (array('content', 'headline') as $section) {
    if (($key = array_search($section, $required_sections)) !== false) {
        unset($required_sections[$key]);
    }
}
?>
<?php foreach ($required_sections as $section) { ?>
    <div class="layout__item 1/1 pt">
        <?php echo \Story\Form::label('required-section-' . $section, _(mb_convert_case($section, MB_CASE_TITLE))) ?>

        <?php
        echo \Story\Form::textarea(
            'required-section-' . $section,
            '',
            array(
                'rows'                     => '20',
                'class'                    => 'text-input 1/1 text-input--redactor  text-input--redactor-frontend',
                'id'                       => 'required-section-' . $section,
                'placeholder'              => _('Insert ' . h($section) . ' content here ...')
            )
        ) ?>
    </div>
<?php } ?>
