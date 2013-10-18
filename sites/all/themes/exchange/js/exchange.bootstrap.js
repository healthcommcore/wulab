jQuery(document).ready(function($) {
	/**
	 * Accordion active class
	 */
  $('.accordion').on('show', function (e) {
    $(e.target).prev('.accordion-heading').find('.accordion-toggle').addClass('active');
  });
  $('.accordion').on('hide', function (e) {
    $(this).find('.accordion-toggle').not($(e.target)).removeClass('active');
  });
	
	/**
	 * Tabs
	 */
	$('.bstabs a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
  
	/**
	 * Tooltips
	 */
	$('a[rel=tooltip]').tooltip();
	
	/**
	 * Carousel
	 */
	$('.carousel').carousel()
});