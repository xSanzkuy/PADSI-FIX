/******/ (() => { // webpackBootstrap
/*!***************************************!*\
  !*** ./resources/js/cartAnimation.js ***!
  \***************************************/
$(document).on('click', '.add-to-cart-btn', function (e) {
  e.preventDefault();

  // Selector ikon keranjang, pastikan sesuai dengan elemen HTML Anda
  var cartIcon = $('.cart-icon');
  var imgtodrag = $(this).closest('.card').find("img").eq(0);
  if (imgtodrag.length) {
    var imgclone = imgtodrag.clone().offset({
      top: imgtodrag.offset().top,
      left: imgtodrag.offset().left
    }).css({
      'opacity': '0.8',
      'position': 'absolute',
      'height': '150px',
      'width': '150px',
      'z-index': '100'
    }).appendTo($('body')).animate({
      'top': cartIcon.offset().top + 10,
      'left': cartIcon.offset().left + 10,
      'width': 50,
      'height': 50
    }, 800, 'easeInOutExpo', function () {
      $(this).fadeOut('fast', function () {
        $(this).detach(); // Hapus elemen setelah animasi selesai
      });
    });
  }

  // Ajax untuk menambahkan produk ke keranjang tanpa reload
  $.ajax({
    url: $(this).attr('data-url'),
    method: 'POST',
    data: {
      _token: $('meta[name="csrf-token"]').attr('content'),
      product_id: $(this).attr('data-id')
    },
    success: function success(response) {
      // Update jumlah item di keranjang jika perlu
      $('.cart-count').text(response.cartCount);
    }
  });
});
/******/ })()
;