<?php
/**
 * @file views-view-grid.tpl.php
 * Default simple view template to display a rows in a grid.
 *
 * - $rows contains a nested array of rows. Each row contains an array of
 *   columns.
 *
 * @ingroup views_templates
 */
?>
<div class="row-fluid title-controller">
  <div class="span11">
    <?php if (!empty($title)): ?>
      <h3><?php print $title; ?></h3>
    <?php endif; ?>
  </div>

  <div class="carousel-content-controller span1">
    <a class="left" href="#<?php print $carousel_id; ?>" data-slide="prev">Previous</a>
    <a class="right" href="#<?php print $carousel_id; ?>" data-slide="next">Next</a>
  </div>
</div>
<div class="row-fluid">
  <div class="carousel content-carousel slide items-<?php print $item_count; ?>" id="<?php print $carousel_id; ?>">
    <div class="carousel-inner">
      <?php foreach ($items as $key => $rows): ?>
        <div class="item <?php if ($key == 1): ?>active<?php endif; ?>">
          <?php foreach ($rows as $row): ?>
            <div class="span3 carousel-cell">
              <?php print $row; ?> 
            </div>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>