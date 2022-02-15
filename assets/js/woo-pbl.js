(function ($) {
  $("#product_beneficiary_number").on("change", function (e) {
    const beneficiary_number = e.target.value;
    const formListWrapper = $(".ProductBeneficiariesForm--List");
    const formItemTemplate = $(".ProductBeneficiaryForm.Template");
    formItemTemplate.clone().removeClass("Template").appendTo(formListWrapper);
  });
})(jQuery);
