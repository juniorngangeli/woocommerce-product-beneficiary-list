<div class="ProductBeneficiariesForm">
    <?php
        woocommerce_wp_text_input(
			array(
                'id' => 'product_beneficiary_number',
                'name' => 'product_beneficiary_number',
                'label' => 'Number of beneficiaries',
                'type' => 'number',
				'min_value'   => 1,
				'max_value'   => $product_max_beneficiary,
				'value' => isset( $_POST['product_beneficiary_number'] ) ? wp_unslash( $_POST['product_beneficiary_number'] ) : 1, // WPCS: CSRF ok, input var ok.
			)
		);
    ?>

    <div class="ProductBeneficiariesForm--List">
    </div>
</div>

<div class="ProductBeneficiaryForm Template">
        <?php
            woocommerce_wp_text_input(
                array(
                    'id' => 'first_name',
                    'name' => 'first_name[]',
                    'label' => 'First name',
                    'type' => 'text',
                    'value' => '', // WPCS: CSRF ok, input var ok.
                )
            );

            woocommerce_wp_text_input(
                array(
                    'id' => 'last_name',
                    'name' => 'last_name[]',
                    'label' => 'Last name',
                    'type' => 'text',
                    'value' => '', // WPCS: CSRF ok, input var ok.
                )
            );

            woocommerce_wp_text_input(
                array(
                    'id' => 'email',
                    'name' => 'email[]',
                    'label' => 'Email',
                    'type' => 'text',
                    'value' => '',
                )
            );
        ?>
    </div>