# My Custom Checkout (within the theme)

This module implements custom logic for a WooCommerce checkout without using the standard Checkout page. Specifically, it disables payment and shipping, uses AJAX to create an order, and includes other global configurations (wrappers, title changes, etc.).

## Structure

```
my-custom-checkout/
├── README.md                 // Description and instructions
├── my-custom-checkout.php    // Main "bootstrap" file
├── includes/
│   ├── class-my-checkout.php          // Custom checkout logic
└── assets/
    └── js/
        └── custom-order.js   // JS for AJAX order creation
```

### `my-custom-checkout.php`
- The **main file** that **initializes** the module:
    - Loads the primary classes from `includes/`.
    - Instantiates these classes so that their hooks and filters are registered.
    - Enqueues the `custom-order.js` script (handling the AJAX “Place Order” click) and passes in `ajax_url` and the `nonce`.

### `includes/class-my-checkout.php`
- **Core logic** of the custom checkout:
    - Disables payment (`woocommerce_cart_needs_payment`) and shipping (`woocommerce_cart_needs_shipping`).
    - Removes Shipping/Billing fields from checkout.
    - Prevents access to the standard `/checkout` page by redirecting to the Cart page (except for the `order-received` URL).
    - Registers AJAX handlers `wp_ajax_place_order` and `wp_ajax_nopriv_place_order` to create orders without the standard checkout.
    - In the `handle_place_order()` method:
        1. Verifies the `nonce`.
        2. Checks if the cart is empty and if the user is logged in.
        3. Creates an order via `wc_create_order()`.
        4. Adds products from the cart to the order and sets `billing_first_name/last_name/email` from the user profile.
        5. Empties the cart and returns a `wp_send_json_success(...)` response with the “Order Received” URL.
    - (Optional) Changes the order status to `completed` in the `woocommerce_thankyou` hook.


### `assets/js/custom-order.js`
- **JS file** that handles the click on the custom “Place Order” button.
- On button click:
    1. Sends an AJAX request to `customOrderParams.ajax_url`.
    2. Passes `action = place_order` and `nonce = customOrderParams.nonce`.
    3. On success → redirects to `response.data.redirect_url` (the “Order Received” page).
    4. On error → displays `alert(...)`.

## How it’s included in the theme

1. In your theme’s **`functions.php`** (or another core file), add:
   ```php
   require_once get_template_directory() . '/functions/my-custom-checkout/my-custom-checkout.php';
   ```
2. The module loads automatically as soon as your theme is active.

## Using the “Place Order” button

To allow the user to place an order in one click (without going to `/checkout`), add a button on the Cart page. For example:
```php
add_action('woocommerce_proceed_to_checkout', function() {
    echo '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" value="1">';
    _e('Place Order', 'woocommerce');
    echo '</button>';
});
```
- Clicking this button triggers the JS (`custom-order.js`) that calls the AJAX endpoint `handle_place_order()`, thereby creating the order.

## Managing User Name
- The user’s (First/Last name) and Email are pulled from the WordPress profile (`$user_info->first_name`, `$user_info->last_name`, `$user_info->user_email`).
- If needed, you can **expand** `set_billing_data()` to fetch other fields from usermeta or ACF.

## Attention to Order Statuses
- By default, the example code sets the order to `processing` and then changes it to `completed` in the `woocommerce_thankyou` hook.
- If you need a different workflow (e.g., leave it at “processing” or “on-hold”), just adjust this in `class-my-checkout.php`.

## Summary
- This module **replaces** the standard WooCommerce checkout.
- All changes and logic are **tied** to this specific theme. If you switch or deactivate the theme, the functionality is lost.
- The code is structured so that the checkout logic is **contained** in `my-custom-checkout/`, avoiding clutter in `functions.php`.

> **Tip**: If you ever need to make this code theme-independent, you can extract the same structure into a dedicated plugin under `wp-content/plugins/` with minimal changes.
