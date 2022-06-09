jQuery(document).ready(function ($) {
  jQuery(".cdfeedback-form").validate({
    submitHandler: function (form) {
      var ajax_url = localizedVars.ajax_url;
      var success_msg = localizedVars.success_msg;
      var fname = jQuery(".first-name", form).val();
      var lname = jQuery(".last-name", form).val();
      var email = jQuery(".email", form).val();
      var subject = jQuery(".subject", form).val();
      var message = jQuery(".message", form).val();

      var formData = new FormData();
      formData.append("action", "submit_display_cdfeedbackform");
      formData.append("first-name", fname);
      formData.append("last-name", lname);
      formData.append("email", email);
      formData.append("subject", subject);
      formData.append("message", message);
      formData.append("nonce", localizedVars.nonce);

      $.ajax({
        // JQuery Ajax
        type: "POST",
        url: ajax_url,
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function (data) {
          jQuery(".cdfeedback-form-section").css("display", "none");
          jQuery(".cdfeedback-form-section").html("");
          jQuery(".cdfeedback-form-message").html(success_msg);
          jQuery(".cdfeedback-form-message").attr("tabindex", -1).focus();
          jQuery(".cdfeedback-form").trigger("reset");
        },
      });
    },
  });

  jQuery("body").on("click", ".pagination", function () {
    var paged = $(this).attr("data-page-id");
    var ajax_url = localizedVars.ajax_url;

    var formData = new FormData();
    formData.append("action", "form_table_data");
    formData.append("paged", paged);

    $.ajax({
      // JQuery Ajax
      type: "POST",
      url: ajax_url,
      data: formData,
      cache: false,
      processData: false,
      contentType: false,
      success: function (data) {
        jQuery(".cdfeedback-ajax-table").html(data);
      },
    });
  });

  jQuery("body").on("click", ".form-short-details", function () {
    console.log("long details here");
    var formid = $(this).attr("data-form-id");

    var ajax_url = localizedVars.ajax_url;

    var formData = new FormData();
    formData.append("action", "form_details_data");
    formData.append("form-id", formid);

    $.ajax({
      // JQuery Ajax
      type: "POST",
      url: ajax_url,
      data: formData,
      cache: false,
      processData: false,
      contentType: false,
      success: function (data) {
        jQuery(".full-form-details").css("display", "block");
        jQuery(".full-form-details").html(data);
        jQuery(".full-form-details").attr("tabindex", -1).focus();
      },
    });
  });
});
