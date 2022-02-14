<?php
$default_enabled_product_category = get_option('default_enabled_product_category');
$max_beneficiaries_per_product = get_option('max_beneficiaries_per_product');
?>

<div class="wrap">

  <h1><?php _e('General options', 'woo-pbl'); ?></h1>

  <?php if (isset($_GET['msg'])) : ?>
    <div id="message" class="updated below-h2">
      <?php if ($_GET['msg'] == 'update') : ?>
        <p><?php _e('Settings saved.'); ?></p>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <form method="post">

    <?php if (function_exists('wp_nonce_field')) wp_nonce_field('nonce_woo_pbl_general_options'); ?>
    <div style="display:flex">
      <div style="width:60%; display:inline-block;">

        <h2><?php _e('Products options'); ?></h2>
        <div id="selected_content_types">
          <?php
            $orderby = 'name';
            $order = 'asc';
            $hide_empty = false ;
            $cat_args = array(
                'orderby'    => $orderby,
                'order'      => $order,
                'hide_empty' => $hide_empty,
            );
            
            $product_categories = get_terms( 'product_cat', $cat_args );
          ?>

          <table class="form-table">
            <tbody>
              <tr>
                <th scope="row"><?php _e('Default enabled product categories', 'woo-pbl') ?></th>
                <td>
                  <select name="default_enabled_product_category[]" id="default_enabled_product_category" multiple="multiple" style="max-width:100%;width:100%;">
                    <?php foreach ($product_categories as $category): ?>
                    <option 
                      <?php selected(in_array($category->term_id, $default_enabled_product_category), true, true) ?>
                      value="<?php _e($category->term_id); ?>"
                    >
                      <?php _e($category->name); ?>
                    </option>
                    <?php endforeach; ?>
                  </select>
                </td>
              </tr>

              <tr>
                <th scope="row"><?php _e('Max beneficiaries per product', 'woo-pbl') ?></th>
                <td>
                  <input 
                    style="max-width:100%;width:100%;"
                    type="number" 
                    class="" 
                    name="max_beneficiaries_per_product" 
                    value="<?php _e($max_beneficiaries_per_product); ?>"
                  />
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div>
          <p class="submit">
            <input type="submit" class="button-primary" name="update_woo_pbl_general_options" value="<?php _e('Update settings', 'woo-pbl'); ?>">
          </p>
        </div>

      </div>
    </div>
</div>
</form>

</div>

<script>
  (function($) {
    $('#default_enabled_product_category').select2();
  })(jQuery)
</script>