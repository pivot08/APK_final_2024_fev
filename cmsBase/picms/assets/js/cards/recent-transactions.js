// -----------------------------------------------------------------------------
// Title: Recent Transactions
// ID: #recent-transaction-table
// Location: index.html
// Dependency File(s):
// /lfcms/assets/vendor/datatables.net/js/jquery.dataTables.js
// /lfcms/assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.js
// -----------------------------------------------------------------------------
(function(window, document, $, undefined) {
	  "use strict";
	$(function() {
		$('#recent-transaction-table').DataTable({
			"columnDefs": [{
				"targets": 'no-sort',
				"orderable": false,
			}],
			"columns": [
				null,
				null,
				null,
				null,
				{
					"width": "10%"
				}]
		});
	});

})(window, document, window.jQuery);
