// Title: Demo code for jQuery Datatables
// Location: tables.data.html
// Dependency File(s):
// /lfcms/assets/vendor/datatables.net/js/jquery.dataTables.js
// /lfcms/assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.js
// /lfcms/assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.css
// -----------------------------------------------------------------------------

(function(window, document, $, undefined) {
  "use strict";
    $(function() {
		$('#bs1-table').DataTable();
      $('#bs2-table').DataTable();
      $('#bs3-table').DataTable();
      $('#bs4-table').DataTable();
      $('#bs6-table').DataTable();

    });

})(window, document, window.jQuery);
