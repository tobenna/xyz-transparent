<?php

/**
 * @file
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>
<?php if (!empty($title)): ?>
  <h3><?php print $title; ?></h3>
<?php endif; ?>
<div class="grid-block small-up-1 medium-up-4">
    <?php foreach ($rows as $id => $row): ?>
        <?php print $row; ?>
    <?php endforeach; ?>
</div>