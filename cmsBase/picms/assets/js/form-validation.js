// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
  'use strict'

  window.addEventListener('load', function () {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation')

    // Loop over them and prevent submission
    Array.prototype.filter.call(forms, function (form) {
      form.addEventListener('submit', function (event) {
        if ($('#ExpectationsID'))
          if ($('#ExpectationsID[type="checkbox"]:checked').length > 0)
            $('#ExpectationsID[type="checkbox"]').each(function () {
              $(this).removeAttr('required');
            });

        if ($('#NatureID'))
          if ($('#NatureID[type="checkbox"]:checked').length > 0)
            $('#NatureID[type="checkbox"]').each(function () {
              $(this).removeAttr('required');
            });

        if ($('#PublicServedID'))
          if ($('#PublicServedID[type="checkbox"]:checked').length > 0)
            $('#PublicServedID[type="checkbox"]').each(function () {
              $(this).removeAttr('required');
            });

        if ($('#AgeGroupID'))
          if ($('#AgeGroupID[type="checkbox"]:checked').length > 0)
            $('#AgeGroupID[type="checkbox"]').each(function () {
              $(this).removeAttr('required');
            });

        if ($('#buttonAddNationalPlace'))
          if ($('#AddressInformationQuant').val() == 0) {
            $('#buttonAddNationalPlace').addClass('addressInvalid');
            $('#buttonAddInternationalPlace').addClass('addressInvalid');
            $('#AddressInformationQuant').attr('required', true);
            $('#invalidAddNationalPlace').show();
            form.setAttribute("isvalid", "false");
          } else {
            $('#ValidateAddressInformationQuant').removeAttr('required');
          }

        if (form.checkValidity() === false) {
          if ($('.form-control:invalid')[0])
            $('.form-control:invalid')[0].focus();

          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
  }, false)
}());

$('#ExpectationsID[type="checkbox"]').change(function () {
  if ($('#ExpectationsID[type="checkbox"]:checked').length > 0)
    $('#ExpectationsID[type="checkbox"]').each(function () {
      $(this).removeAttr('required');
    });
  else
    $('#ExpectationsID[type="checkbox"]').each(function () {
      $(this).attr('required', 'true');
    });
}).change();

$('#NatureID[type="checkbox"]').change(function () {
  if ($('#NatureID[type="checkbox"]:checked').length > 0)
    $('#NatureID[type="checkbox"]').each(function () {
      $(this).removeAttr('required');
    });
  else
    $('#NatureID[type="checkbox"]').each(function () {
      $(this).attr('required', 'true');
    });
}).change();

$('#AgeGroupID[type="checkbox"]').change(function () {
  if ($('#AgeGroupID[type="checkbox"]:checked').length > 0)
    $('#AgeGroupID[type="checkbox"]').each(function () {
      $(this).removeAttr('required');
    });
  else
    $('#AgeGroupID[type="checkbox"]').each(function () {
      $(this).attr('required', 'true');
    });
}).change();

$('#PublicServedID[type="checkbox"]').change(function () {
  if ($('#PublicServedID[type="checkbox"]:checked').length > 0)
    $('#PublicServedID[type="checkbox"]').each(function () {
      $(this).removeAttr('required');
    });
  else
    $('#PublicServedID[type="checkbox"]').each(function () {
      $(this).attr('required', 'true');
    });
}).change();