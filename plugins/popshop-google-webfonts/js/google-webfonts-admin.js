jQuery(document).ready(function($){
	
	$('#heading_font_goog option').each(function(){
		var font = $(this).attr('value');
		font = font.substr(0, 5).replace(' ', '');
		if (font == '') {
			font = "_blank";
		}
		var url = $('#popshop_webfonts_dir').val();
		$(this).attr('data-image', url+'images/'+font+'.png');
	});
	
	$('#heading_font_goog').closest('.option').append('<img id="heading_font_goog_preview">');
	
	
	function PopshopGoogleWebfontsUpdate() {
	    var selectVal = $('#heading_font_goog').find('option:selected').attr('data-image');
		
		if (selectVal.length > 0) {
			$('#heading_font_goog_preview').attr('src', selectVal);
		}
	}
	
	PopshopGoogleWebfontsUpdate();
	
	$('#heading_font_goog').change(function(){
		PopshopGoogleWebfontsUpdate();
	});
	
	
});

