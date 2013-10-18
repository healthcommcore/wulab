<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix" <?php print $attributes; ?>>
  <div class="testimonial-image">
    <?php print render($content['field_testimonial_image']); ?>
  </div>
  <div class="testimonial-entry">
    <?php print render($content['body']); ?>
  </div>
  <div class="testimonial-author">- <?php print $title; ?></div>
</div>
