jQuery(document).ready(function($) {
  $('body').on('click', 'button[name="woocommerce_checkout_place_order"]', function(e) {
    e.preventDefault();
    var $btn = $(this);

    $btn.prop('disabled', true);

    $.ajax({
      type: 'POST',
      url: customOrderParams.ajax_url,
      data: {
        action: 'place_order',
        security: customOrderParams.nonce,
        customer_id: customOrderParams.customer_id
      },
      success: function(response, textStatus, jqXHR) {
        if (jqXHR.status === 302) {
          window.location.href = jqXHR.getResponseHeader('Location');
        } else if (jqXHR.getResponseHeader('Content-Type').includes('text/html')) {
          alert('An error occurred. Please try again.');
          console.error('Error response:', response);
        } else if (response.success) {
          window.location.href = response.data.redirect_url;
        } else {
          console.error('Error response:', response);
          if (response.data && response.data.error) {
            alert(response.data.error);
          } else {
            alert('An unknown error occurred.');
          }
        }
      },
      error: function() {
        alert('An error occurred. Please try again.');
        $btn.prop('disabled', false);
      }
    });
  });
});
