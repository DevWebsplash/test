jQuery(function ($) {
  'use strict';

  /* function on page ready */
  const isTouchScreen = navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i);
  if (isTouchScreen) $('html').addClass('touch-screen');

  if (/(Mac|iPhone|iPod|iPad)/i.test(navigator.platform)) {
    $('html').addClass('mac-os');
  }

  if (navigator.userAgent.indexOf("Firefox") >= 0) {
    $('html').addClass('firefox');
  }
  if (navigator.userAgent.indexOf('Edge') >= 0) {
    $('html').addClass('edge');
  }
  if (navigator.userAgent.indexOf("Trident") >= 0) {
    $('html').addClass('ie');
  }
  if (navigator.userAgent.indexOf('Safari') >= 0 && navigator.userAgent.indexOf('Chrome') < 0) {
    $('html').addClass('safari');
  }
  if (navigator.userAgent.indexOf('Chrome') >= 0 && navigator.userAgent.indexOf('Edge') < 0) {
    $('html').addClass('chrome');
  }

  //menu
  function freeze() {
    if ($("html").css("position") != "fixed") {
      const top = $("html").scrollTop() ? $("html").scrollTop() : $("body").scrollTop();
      if (window.innerWidth > $("html").width()) {
        $("html").css("overflow-y", "scroll");
      }
      $("html").css({"width": "100%", "height": "100%", "position": "fixed", "top": -top});
    }
  }

  function unfreeze() {
    if ($("html").css("position") == "fixed") {
      $("html").css("position", "static");
      $("html, body").scrollTop(-parseInt($("html").css("top")));
      $("html").css({"position": "", "width": "", "height": "", "top": "", "overflow-y": ""});
    }
  }

  let frozen = false;

  // Toggle menu
  $('.toggle-menu').on('click', function () {
    if (frozen) {
      unfreeze();
      frozen = false;
    } else {
      freeze();
      frozen = true;
    }
    $(this).toggleClass('active');
    $('.header__nav').toggleClass('open').slideToggle(300);
    // $('html').toggleClass('overflow-hidden');
  });

  //anchors scroll
  let anchorOffset = $(window).width() > 992 ? 80 : 40;
  $(function () {
    $('a[href*="#"]:not([href="#"]):not(.open-popup)').on('click', function () {
      unfreeze();
      frozen = false;
      if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
        let target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');

        if (target.length) {
          $('html, body').animate({
            scrollTop: target.offset().top - anchorOffset
          }, 1000);
          return false;
        }
      }
    });
  });

  //active nav items on scroll
  let sections = $('.section');
  let nav = $('.main-menu');

  $(window).on('scroll', function () {
    let cur_pos = $(this).scrollTop();

    sections.each(function () {
      let top = $(this).offset().top;
      let bottom = top + $(this).outerHeight();

      if (cur_pos >= top && cur_pos <= bottom) {
        nav.find('a').removeClass('active');
        nav.find('a[href="#' + $(this).find('.anchor').attr('id') + '"]').addClass('active');
      }
    });
  });

  //accordeon
  $('.acc-head').on('click', function () {
    $(this).closest('.acc-item').toggleClass('active').find('.acc-body').slideToggle(300);
  });

  //tabs
  $('.tabs__caption li').on('click', function () {
    let i = $(this).index();

    $(this).addClass('active').siblings().removeClass('active');

    $(this).closest('.tabs').find('.tabs__content-wrap').each(function () {
      let tab = $(this).find('.tabs__content');
      tab.filter(':visible').fadeOut(150, function () {
        tab.eq(i).fadeIn(150);
      });
    });
  });

  //popup
  let scrollBarWidth = window.innerWidth - $(window).width();

  $('.open-popup').magnificPopup({
    type: 'inline',
    fixedContentPos: true,
    fixedBgPos: true,
    overflowY: 'auto',
    preloader: false,
    midClick: true,
    removalDelay: 300,
    mainClass: 'my-mfp-zoom-in',
    callbacks: {
      open: function () {
        $('.header').css('right', scrollBarWidth + 'px')
      },
      close: function () {
        $('.header').css('right', 0)
      }
    }
  });


  const sliderThumbs = new Swiper('.slider__thumbs .swiper-container', {
    direction: 'vertical',
    slidesPerView: 3,
    spaceBetween: 16,
    navigation: {
      nextEl: '.slider__next',
      prevEl: '.slider__prev'
    },
    freeMode: true,
    breakpoints: {
      0: {
        direction: 'horizontal',
        slidesPerView: 2,
      },
      580: {
        direction: 'horizontal',
        slidesPerView: 3,
      },
      768: {
        direction: 'vertical',

      }
    }
  });

  const sliderImages = new Swiper('.slider__images .swiper-container', {
    direction: 'vertical',
    slidesPerView: 1,
    spaceBetween: 30,
    mousewheel: true,
    navigation: {
      nextEl: '.slider__next',
      prevEl: '.slider__prev'
    },
    grabCursor: true,
    thumbs: {
      swiper: sliderThumbs
    },
    breakpoints: {
      0: {
        direction: 'horizontal',
      },
      768: {
        direction: 'vertical',
      }
    }
  });

  const swiperRelated = new Swiper('.related-products-swiper', {
    // slidesPerView: 'auto',
    slidesPerView: 1,
    spaceBetween: 32,
    initialSlide: 1,
    centeredSlides: true,
    mousewheel: true,
    // pagination: {
    //   el: '.swiper-pagination',
    //   clickable: true,
    // },
    breakpoints: {
      0: {
        slidesPerView: 1,
        initialSlide: 0,
      },
      768: {
        slidesPerView: 2,
      },
      992: {
        slidesPerView: 3,
        initialSlide: 1,
      }
    }
  });

  // Знайдемо всі таблиці з класом .popup_table
  const $popupTable = $('.popup_table');
  if ($popupTable.length) {
    $popupTable.each(function () {
      let $table = $(this);

      let $headerRow = $table.find('tr').has('th').first();
      if ($headerRow.length === 0) {
        return;
      }

      let headers = [];
      $headerRow.find('th').each(function (index, el) {
        let text = $(el).text().trim() || ' ';
        headers.push(text);
      });

      $table.find('tr').not($headerRow).each(function () {
        $(this).find('td').each(function (i) {
          $(this).attr('data-label', headers[i] ? headers[i] : '');
        });
      });
    });
  }
});


document.addEventListener('DOMContentLoaded', function () {
  let countdownElement = document.getElementById('countdown-timer');
  if (countdownElement) {
    let timerElement = countdownElement.querySelector('.timer');
    let eventDate = new Date(countdownElement.getAttribute('data-datetime')).getTime();

    let countdownInterval = setInterval(function () {
      let now = new Date().getTime();
      let distance = eventDate - now;

      if (distance < 0) {
        clearInterval(countdownInterval);
        countdownElement.classList.add('time-up');
        timerElement.innerHTML = '00:00:00';
        return;
      }

      countdownElement.classList.add('time-active');
    let totalHours = Math.floor(distance / (1000 * 60 * 60));
    let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    let seconds = Math.floor((distance % (1000 * 60)) / 1000);

    let formattedHours = totalHours.toString().padStart(2, '0');
    let formattedMinutes = minutes.toString().padStart(2, '0');
    let formattedSeconds = seconds.toString().padStart(2, '0');

    timerElement.innerHTML = '<div class="time__item">' + formattedHours + '<span>h</span></div>:<div class="time__item">' + formattedMinutes + '<span>min</span></div>:<div class="time__item">' + formattedSeconds + '<span>sec</span></div>';
    }, 1000);
  }

  // Add plus and minus buttons to quantity inputs
  jQuery('.quantity').each(function() {
    let $this = jQuery(this),
        $input = $this.find('input.qty'),
        $increase = jQuery('<button type="button" class="plus">+</button>'),
        $decrease = jQuery('<button type="button" class="minus">-</button>');

    $increase.insertAfter($input);
    $decrease.insertBefore($input);

    $increase.on('click', function() {
      let currentVal = parseInt($input.val(), 10);
      if (!isNaN(currentVal)) {
        $input.val(currentVal + 1).change();
      }
    });

    $decrease.on('click', function() {
      let currentVal = parseInt($input.val(), 10);
      if (!isNaN(currentVal) && currentVal > 1) {
        $input.val(currentVal - 1).change();
      }
    });
  });
});
