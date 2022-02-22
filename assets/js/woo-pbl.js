(function ($) {
  const productBeneficiariesForm = $(".ProductBeneficiariesForm");

  if (productBeneficiariesForm.length > 0) {
    $(".cart").attr("novalidate", "novalidate");
    $("#product_beneficiaries_form_button").on("click", function (e) {
      e.preventDefault();
      const formListWrapper = $(".ProductBeneficiariesForm--List");
      const formItemTemplate = $(".ProductBeneficiaryForm.Template");
      const productBeneficiaryFormCount = $(formListWrapper).children(
        ".ProductBeneficiaryForm"
      ).length;

      formItemTemplate.find("input").each(function (index, htmlElement) {
        let htmlElementId = $(htmlElement).attr("id");
        $(htmlElement).attr(
          "id",
          htmlElementId + (productBeneficiaryFormCount + 1)
        );
      });

      formItemTemplate.find("label").each(function (index, htmlElement) {
        let htmlElementId = $(htmlElement).attr("for");
        $(htmlElement).attr(
          "for",
          htmlElementId + parseInt(productBeneficiaryFormCount + 1)
        );
      });

      const formItemTemplateClone = formItemTemplate
        .clone()
        .removeClass("Template")
        .attr("data-id", productBeneficiaryFormCount + 1)
        .appendTo(formListWrapper);

      $(formItemTemplateClone)
        .find(".ProductBeneficiaryForm--Buttons button")
        .on("click", function (e) {
          e.preventDefault();
          $(this).parents(".ProductBeneficiaryForm").remove();
        });
    });

    $("#product_beneficiaries_form_button").click();

    $(".single_add_to_cart_button").on("click", function (e) {
      //e.preventDefault();
    });

    $(".cart").on("submit", function (e) {
      let formErrorsCount = 0;
      $(".ProductBeneficiaryForm:not(.Template)")
        .find("input")
        .each(function (index, htmlElement) {
          let id = $(htmlElement).attr("id");
          let type = $(htmlElement).attr("type");
          let required = $(htmlElement).attr("required");
          let value = $(htmlElement).val();

          let inputHasErrors = false;
          let errorMessage = "";

          if (required && !value) {
            inputHasErrors = true;
            errorMessage = "This field is required";
          }

          const emailRegex = new RegExp(
            /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/
          );
          if (type == "email" && !inputHasErrors) {
            inputHasErrors = !emailRegex.test(value);
            errorMessage = "Please type a valid email address";
          }

          $(htmlElement).parent(".form-field").find(`#${id}-error`).remove();

          if (inputHasErrors) {
            formErrorsCount++;
            $(htmlElement)
              .parent(".form-field")
              .append(
                `<div class="form-field-error" id="${id}-error">${errorMessage}</div>`
              );
          }
        });

      if (formErrorsCount) {
        e.preventDefault();
      } else {
        $(".ProductBeneficiaryForm.Template").remove();
      }
    });
  }
})(jQuery);
