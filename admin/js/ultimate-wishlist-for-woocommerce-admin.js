(function($) {
  "use strict";

  /**
   * All of the code for your admin-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */

  $(document).ready(function() {
    const MDCText = mdc.textField.MDCTextField;
    const textField = [].map.call(
      document.querySelectorAll(".mdc-text-field"),
      function(el) {
        return new MDCText(el);
      }
    );
    const MDCRipple = mdc.ripple.MDCRipple;
    const buttonRipple = [].map.call(
      document.querySelectorAll(".mdc-button"),
      function(el) {
        return new MDCRipple(el);
      }
    );
    const MDCSwitch = mdc.switchControl.MDCSwitch;
    const switchControl = [].map.call(
      document.querySelectorAll(".mdc-switch"),
      function(el) {
        return new MDCSwitch(el);
      }
    );

    $(".mwb-password-hidden").on('click',function() {
      if ($(".mwb-form__password").attr("type") == "text") {
        $(".mwb-form__password").attr("type", "password");
      } else {
        $(".mwb-form__password").attr("type", "text");
      }
    });
  });

  $(window).load(function() {
    // add select2 for multiselect.
    if ($(document).find(".mwb-defaut-multiselect").length > 0) {
      $(document)
        .find(".mwb-defaut-multiselect")
        .select2();
    }
  });

  jQuery(document).ready(function($) {
    $(".uwfw-select-icon-color-class").wpColorPicker();
    var entries="entries";
    $('#uwfw-performance-analytics-table').dataTable(
      {
        language: {
          "decimal":        "",
          "emptyTable":     uwfw_admin_param.data_table_language['nodata'],
          "info":            uwfw_admin_param.data_table_language['info'],
          "infoEmpty":       uwfw_admin_param.data_table_language['infoEmpty'],
          "infoFiltered":    uwfw_admin_param.data_table_language['infoFiltered'],
          "infoPostFix":    "",
          "thousands":      ",",
          "lengthMenu":      uwfw_admin_param.data_table_language['lengthMenu'],
          "loadingRecords":  uwfw_admin_param.data_table_language['loadingRecords'],
          "processing":      uwfw_admin_param.data_table_language['processing'],
          "search":          uwfw_admin_param.data_table_language['search'],
          "zeroRecords":    uwfw_admin_param.data_table_language['zeroRecords'],
          "paginate": {
              "first":       uwfw_admin_param.data_table_language['first'],
              "last":        uwfw_admin_param.data_table_language['last'],
              "next":       uwfw_admin_param.data_table_language['next'],
              "previous":    uwfw_admin_param.data_table_language['previous']
          },
          "aria": {
              "sortAscending":  uwfw_admin_param.data_table_language['sortAscending'],
              "sortDescending":  uwfw_admin_param.data_table_language['sortDescending']
          
        }
        },
        columnDefs: [
          { orderable: true, className: 'reorder', targets: [0,2,3,5] },
          { orderable: false, targets: '_all' }
          ]
    });
    var value =$(document).find('#wfw-view-type').val();
    whishlistDisplaySettings(value);
    $(document).find('#wfw-add-to-wishlist-button-color').wpColorPicker();
  function whishlistDisplaySettings( value ) {
  if ( 'button' === value ) {
    $(document).find('#wfw-icon-view').parents('.mwb-form-group').hide();
    $(document).find('#wfw-loop-button-view').parents('.mwb-form-group').show();
    $(document).find('#wfw-product-button-view').parents('.mwb-form-group').show();
    $(document).find('#wfw-add-to-wishlist-button-color').parents('.mwb-form-group').show();

  } else {
    $(document).find('#wfw-add-to-wishlist-button-color').parents('.mwb-form-group').hide();
    $(document).find('#wfw-loop-button-view').parents('.mwb-form-group').hide();
    $(document).find('#wfw-product-button-view').parents('.mwb-form-group').hide();
    $(document).find('#wfw-icon-view').parents('.mwb-form-group').show();
  }
}
  $(document).find('#wfw-view-type').on('change', function() {
    var value =$(this).val();
    whishlistDisplaySettings(value);
  });
});

})(jQuery);

