<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> row clearfix" <?php print $attributes; ?>>
  <div class="span7">
    <?php print render($content['field_work_image']); ?>
  </div>
  <div class="span5">
    <?php print render($content['field_preface']); ?>
    <?php print render($content['body']); ?>
    <?php print render($content['field_work_link']); ?>
  </div>
</div>
