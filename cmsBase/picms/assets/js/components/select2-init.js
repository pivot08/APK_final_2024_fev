(function(window, document, $, undefined) {
	  "use strict";
	$(function() {

		// multi select
		$('#AppraiserList').select2({
			placeholder: "Selecione um avaliador"
		});
		
		$('#JuryList').select2({
			placeholder: "Selecione um jurado"
		});

	});

})(window, document, window.jQuery);
