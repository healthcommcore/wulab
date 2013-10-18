<?php
  // Remove "Log in or register to post comments" text
  if (isset($content['links']['comment']['#links'])) {
    foreach ($content['links']['comment']['#links'] as $key => $link) {
      if ($key == 'comment_forbidden') {
        unset($content['links']['comment']['#links'][$key]);
      }
    }
  }
  
	// Remove the "Add new comment" link on the teaser page or if the comment
	// form is being displayed on the same page.
	if ($teaser || !empty($content['comments']['comment_form'])) {
		unset($content['links']['comment']['#links']['comment-add']);
	}
?>
<article id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>"<?php print $attributes; ?>>
	<?php print render($title_prefix); ?>
	<?php if (!$page): ?>
		<h2<?php print $title_attributes; ?>>
			<a href="<?php print $node_url; ?>"><?php print $title; ?></a>
		</h2>
	<?php endif; ?>
	<?php print render($title_suffix); ?>

	<?php if (isset($content['field_article_image']) && !empty($content['field_article_image'])) : ?>
		<div class="article-image">
			<?php print render($content['field_article_image']); ?>
		</div>
	<?php endif; ?>

	<div class="row">
		<?php if ($display_submitted): ?>
			<div class="meta submitted span2 clearfix">
				<?php print $user_picture; ?>
				<div class="meta-text">
					<div class="author"><?php print $author; ?></div>
					<div class="date"><?php print $submitted; ?></div>
          <?php if (isset($comment_count)): ?>
					  <div class="comment-count"><?php print $comment_count; ?></div>
          <?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="span6">
			<div class="node-content"<?php print $content_attributes; ?>>
				<?php
					// Hide the links and comments
					hide($content['links']);
					hide($content['comments']);
					
					print render($content);
				?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="span8">
			<?php print render($content['comments']); ?>
		</div>
	</div>
</article>
