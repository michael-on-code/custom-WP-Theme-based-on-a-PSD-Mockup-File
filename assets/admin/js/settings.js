(function( $ ) {
	'use strict';
	$(function() {
		$("."+coalitionJSData.mediaGalleryLoaderSelector).on("click", function(e){
			e.preventDefault();
			var $button = $(this)
			var galleryWindow = wp.media({
				title: $button.attr("data-title"),
				library:{type:"image"},
				multiple:false,
				button:{text:coalitionJSData.mediaGalleryButtonText}
			});
			galleryWindow.on("select", function(){
				var imageSelected = galleryWindow.state().get("selection").first().toJSON();
				console.log(imageSelected)
				$button.siblings("input").val(imageSelected.id)
				$button.siblings("img").removeClass("is-hidden")
				$button.siblings("img").attr("src", imageSelected.url)
			})
			galleryWindow.open()
		})
	 });

})( jQuery );
