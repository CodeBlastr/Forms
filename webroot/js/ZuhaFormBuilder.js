(function ($) {
	
	$.fn.zuhaFormBuilder = function() {
		
		return this.each(function() {
			
			var form = $(this).find('form');
			//var fieldsets = form.find('fieldset');
			var inputs = $(form).find('input');
			
			$(form).find('div.input').sortable({containment : 'fieldset'});
			$(form).find('fieldset').draggable().droppable().css('border', '1px #999 solid').css('min-height', '100px');
			
			if(fieldsets.length > 0) {
				
			}
			
		});	
		
	}
	
})(jQuery);
