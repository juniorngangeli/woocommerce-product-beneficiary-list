<?php
  $order_status_email_trigger = get_option('order_status_email_trigger');
  $email_notification_content = get_option('email_notification_content');
  $order_statuses = wc_get_order_statuses();
?>

<div class="wrap">

  <h1><?php esc_html_e('General options', 'woo-pbl'); ?></h1>

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
                <th scope="row"><?php esc_html_e('Order status email trigger', 'woo-pbl') ?></th>
                <td>
                  <select name="order_status_email_trigger" id="order_status_email_trigger" style="max-width:100%;width:100%;">
                      <?php foreach ($order_statuses as $order_status_slug => $order_status_name): ?>
                      <option 
                        <?php selected($order_status_email_trigger, str_replace("wc-", "", $order_status_slug), true) ?>
                        value="<?php esc_html_e(str_replace("wc-", "", $order_status_slug)); ?>"
                      >
                        <?php _e($order_status_name); ?>
                      </option>
                      <?php endforeach; ?>
                    </select>
                </td>
              </tr>
              
              <tr>
                <th scope="row"><?php esc_html_e('Email notification content', 'woo-pbl') ?></th>
                <td>
                  <?php 
                    wp_editor( 
                      $email_notification_content, 
                      'email_notification_content', 
                      $settings = array(
                        'textarea_name'=>'email_notification_content',
                        'media_buttons' => false,
                        'textarea_rows' => 8,
                      ) 
                    ); 
                  ?>
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