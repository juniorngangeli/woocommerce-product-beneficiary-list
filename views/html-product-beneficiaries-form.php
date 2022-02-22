<div 
    class="ProductBeneficiariesForm" 
    data-product-max-beneficiary="<?php _e($product_max_beneficiary); ?>"
    data-product-price-per-beneficiary="<?php _e($product_price_per_beneficiary); ?>"
>
    <button id="product_beneficiaries_form_button" class="ProductBeneficiariesForm--Button">
        &plus; Add a beneficiary
    </button>

    <div class="ProductBeneficiariesForm--List">
    </div>
</div>

<div class="ProductBeneficiaryForm Template">
    <div class="ProductBeneficiaryForm--Buttons">
        <button>
            &times; Remove
        </button>
    </div>
        <?php
            woocommerce_wp_text_input(
                array(
                    'id' => 'first_name',
                    'name' => 'first_name[]',
                    'label' => 'First name',
                    'type' => 'text',
                    'value' => '', // WPCS: CSRF ok, input var ok.
                    'custom_attributes' => [
                        'required' => true,
                    ]
                )
            );

            woocommerce_wp_text_input(
                array(
                    'id' => 'last_name',
                    'name' => 'last_name[]',
                    'label' => 'Last name',
                    'type' => 'text',
                    'value' => '', // WPCS: CSRF ok, input var ok.
                    'custom_attributes' => [
                        'required' => true,
                    ]
                )
            );

            woocommerce_wp_text_input(
                array(
                    'id' => 'email',
                    'name' => 'email[]',
                    'label' => 'Email',
                    'type' => 'email',
                    'value' => '',
                    'custom_attributes' => [
                        'required' => true,
                    ]
                )
            );
        ?>
    </div>