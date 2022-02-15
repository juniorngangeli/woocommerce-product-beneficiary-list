<div id="beneficiaries_options_options" class="panel woocommerce_options_panel">
    <?php
        woocommerce_wp_checkbox(
            array(
                'id'      => 'beneficiaries_options_enabled',
                'label'   => __( 'Enable beneficiaries options', 'woo-pbl' ),
                'value'   => $beneficiaries_options_enabled == 1 ? 'yes' : 'no',
                'cbvalue' => 'yes',
            )
        );

		woocommerce_wp_text_input(
			array(
				'id'        => 'product_price_per_beneficiary',
				'value'     => $product_price_per_beneficiary,
				'label'     => __( 'Price per beneficiary', 'woo-pbl' ),
				'data_type' => 'price',
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'          => 'product_max_beneficiary',
				'value'       => $product_max_beneficiary,
				'data_type'   => 'price',
				'label'       => __( 'Max beneficiaries', 'woo-pbl' ),
			)
		);
    ?>
</div>