(function ($) {
  $(document).ready(function () {
    "use strict";

    // LOST PASSWORD TOGGLE
    $('.woocommerce-LostPassword.lost_password a').on('click', function(e) {
        e.preventDefault();
        $('.woocommerce-form-login.login').hide();
        $('.register-wrapper').hide();
        $('.u-column2.col-2').hide();
        $('.u-column1.col-1').hide();
        $('.lost_reset_password').show();
    });

    // FILTER TOGGLE BUTTON
    $('.shop-filter-button').on('click', function (e) {
      $('.sidebar.shop-filter').slideToggle();
    });


    // ACCOUNT TOGGLE BUTTON
    $('.account-toggle').on('click', function (e) {
      $('.woocommerce-MyAccount-navigation').slideToggle();
    });


    // MOBILE MENU TOP SPACING
    var navbarHeight = $('.navbar').height(); // Get the height of the navbar
    $('.mobile-menu').css('padding-top', navbarHeight + 40 + 'px'); // Set the t


    $('.navbar .hamburger-menu').on('click', function (e) {
      $('.navbar .hamburger-menu svg').toggleClass('active');
      $('body').toggleClass('mobile-menu-active');
    });


    // MOBILE MENU DROPDOWN TOGGLE
    $('.menu-item-has-children i').on('click', function (e) {
      e.preventDefault();
      var $this = $(this),
        $dropdown = $this.next('ul.dropdown'),
        title = $this.prev('a').text(),
        $firstLI = $('<li class="dropdown-first-li"><span>' + title + '</span><button class="close-button"><svg width="23" height="8" viewBox="0 0 23 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M22.3536 3.64644C22.5488 3.84171 22.5488 4.15829 22.3536 4.35355L19.1716 7.53553C18.9763 7.73079 18.6597 7.73079 18.4645 7.53553C18.2692 7.34027 18.2692 7.02369 18.4645 6.82843L21.2929 4L18.4645 1.17157C18.2692 0.976309 18.2692 0.659727 18.4645 0.464464C18.6597 0.269202 18.9763 0.269202 19.1716 0.464464L22.3536 3.64644ZM-4.37114e-08 3.5L22 3.5L22 4.5L4.37114e-08 4.5L-4.37114e-08 3.5Z" fill="black"></path></svg></button></li>');
      $dropdown.prepend($firstLI).addClass('active');
      $(this).siblings('ul.dropdown').addClass('dropdown-open');
      return false;
    });

    $(document).on('click', '.close-button', function () {
      var dropdown = $(this).closest('ul.dropdown');
      dropdown.removeClass('dropdown-open');
      $(this).closest('li').remove();
      return false;
    });


    // AJAX FILTER AND pagination

    	var filters = {
        pageNumber: 1
      };

    	Object.defineProperty(window, 'pageNumber', {
    	get: function() {
    		return filters.pageNumber;
    	},
    	set: function(value) {
    		filters.pageNumber = value;
    		filterProducts();
    	}
    });

    	$('.attribute-select').on('change', function() {
        var taxonomy = $(this).attr('name');
        filters[taxonomy] = $(this).val();
        filters.pageNumber = 1;
        filterProducts();
      });


    $(document).on('click', '.woocommerce-pagination a', function(e) {
    	e.preventDefault();
    	var currentPage = parseInt($(this).text());
    	if (!isNaN(currentPage)) {
    		filters.pageNumber = currentPage;
    	} else if ($(this).hasClass('prev')) {
    		filters.pageNumber--;
    	} else if ($(this).hasClass('next')) {
    		filters.pageNumber++;
    	}
    	window.pageNumber = filters.pageNumber;
    });

    function updateURL() {
    	var url = window.location.origin + window.location.pathname;
    	var segments = url.split('/');
    	segments.pop(); // remove last segment (e.g. "page" or page number)
    	url = segments.join('/') + '/'; // add trailing slash

    	var params = new URLSearchParams();
    	$.each(filters, function(key, value) {
    	  if (value !== '' && key !== 'pageNumber') {
    	    params.set(key, value);
    	  }
    	});
    	if (filters.pageNumber > 1) {
    	  params.set('page', filters.pageNumber);
    	} else {
    	  params.delete('page');
    	}
    	if (params.toString()) {
    	  url += '?' + params.toString();
    	}

    	window.history.pushState(null, null, url);


    }



    	function filterProducts() {
    		 var current_page = 1;
    		 var form = $('#skolka-product-filter');
    		 var filterData = {
           action: 'filter_products',
           page: filters.pageNumber,
           current_category: form.find('input[name="current_category"]').val(),
           custom_page: form.find('input[name="custom_page"]').val()

         };

    		 $.each(filters, function(key, value) {
           if (value !== '' && key !== 'pageNumber') {
             filterData[key] = value;
           }
         });

         $.ajax({
           url: '/wp-admin/admin-ajax.php',
           data: filterData,
           method: 'POST',
    			 beforeSend: function() {
    					$(".ajax-preloader").show();
    			},
           success: function(data) {
             $(".ajax-preloader").hide();
             $('.products').html(data.products);
             $('.woocommerce-pagination').html(data.pagination);
    				 $('.skolka-result-count span').html(data.total_products);

    				 updateURL();


           }
         });
       }



       // CUSTOM AJAX pagination

       $(document).on('click', '.custom_pages_pagination a', function(e) {
         e.preventDefault();
         var link = $(this).attr('href');
         var current_page = 1;
         var match = link.match(/\/page\/(\d+)\/$/);
         if (match) {
           current_page = parseInt(match[1]);
         }
         var form = $('#skolka-product-filter');
         var filter_params = form.serialize() + '&paged=' + current_page + '&action=filter_products';

         window.addEventListener("popstate", function(event) {
           var page = event.state.Page;
           updateProducts(page);
         });

         function updateUrl(page) {
           if (typeof(history.pushState) != "undefined") {
             var currentUrl = window.location.href.split('?')[0];
             var obj = {
               Page: page,
               Url: currentUrl
             };
             currentUrl = currentUrl.replace(/\/page\/\d+\//g, "/");
             currentUrl = currentUrl.endsWith("/") ? currentUrl : currentUrl + "/";
             if (page === 1) {
               history.pushState(obj, obj.Page, currentUrl);
             } else {
               history.pushState(obj, obj.Page, currentUrl + 'page/' + page + '/');
             }
           } else {
             console.log("Browser does not support HTML5.");
           }
         }

         $.ajax({
           url: link,
           type: 'post',
           data: filter_params,
           beforeSend: function() {
             $(".ajax-preloader").show();
           },
           success: function(data) {
             $('.products').html($(data).find('.products').html());
             $('.skolka-pagination').html($(data).find('.skolka-pagination').html());
             $('.ajax-preloader').hide();
             updateUrl(current_page);
           }
         });
       });


    // UPDATE COUNTRY CODE ON PHONE FIELD
    $(document.body).on('updated_checkout', function (data) {
      var ajax_url = "/wp-admin/admin-ajax.php",
        country_code = $('#billing_country').val();

      var ajax_data = {
        action: 'append_country_prefix_in_billing_phone',
        country_code: $('#billing_country').val()
      };

      $.post(ajax_url, ajax_data, function (response) {
        var formatted_phone_number = '';
        if (response) {
          formatted_phone_number += response + ' ';
        }
        $('#billing_phone').val(formatted_phone_number);
      });
    });


    // DEFAULT AJAX PAGINATION
    (function ($) {
      $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();

        var link = $(this).attr('href');
        var current_page = 1;
        var match = link.match(/\/page\/(\d+)\/$/);
        if (match) {
          current_page = parseInt(match[1]);
        }
        window.addEventListener("popstate", function (event) {
          var page = event.state.Page;
          updateProducts(page);
        });

        function updateUrl(page) {
          if (typeof (history.pushState) != "undefined") {
            var currentUrl = window.location.href.split('?')[0];
            var obj = {
              Page: page,
              Url: currentUrl
            };
            currentUrl = currentUrl.replace(/\/page\/\d+\//g, "/");
            currentUrl = currentUrl.endsWith("/") ? currentUrl : currentUrl + "/";
            if (page === 1) {
              history.pushState(obj, obj.Page, currentUrl);
            } else {
              history.pushState(obj, obj.Page, currentUrl + 'page/' + page + '/');
            }
          } else {
            console.log("Browser does not support HTML5.");
          }
        }

        $.ajax({
          url: link,
          type: 'GET',
          dataType: 'html',
          beforeSend: function () {
            $(".ajax-preloader").show();
          },
          success: function (data) {
            $('.ajax-listing-wrapper').html($(data).find('.ajax-listing-wrapper').html());
            $('.pagination').html($(data).find('.pagination').html());
            $('.ajax-preloader').hide();
            updateUrl(current_page);
          }
        });
      });
    })(jQuery);


    // ORDER BY SELECT
    $('.woocommerce-ordering .orderby').change(function (event) {
      event.preventDefault();
      event.stopPropagation();
      var orderby = $(this).val();
      $.ajax({
        url: window.location.href,
        data: {
          orderby: orderby
        },
        beforeSend: function () {
          $(".ajax-preloader").show();
        },
        success: function (response) {
          $('.products').html($(response).find('.products .product'));
          $('.ajax-preloader').hide();
          var url = window.location.href;
          var newUrl = updateQueryStringParameter(url, 'orderby', orderby);
          window.history.pushState({
            path: newUrl
          }, '', newUrl);
        }
      });
    });

    function updateQueryStringParameter(uri, key, value) {
      var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
      var separator = uri.indexOf('?') !== -1 ? "&" : "?";
      if (uri.match(re)) {
        return uri.replace(re, '$1' + key + "=" + value + '$2');
      } else {
        return uri + separator + key + "=" + value;
      }
    }


    // PRODUCT GRID VIEW
    $('.grid-view').on('click', function (e) {
      $(".grid-view").removeClass("active");
      $(this).addClass("active");

      if ($(this).hasClass("two-col")) {
        $(".products").removeClass("columns-3");
        $(".products").addClass("columns-2");
      } else {
        $(".products").removeClass("columns-2");
        $(".products").addClass("columns-3");
      }
    });


    // LOGIN
    $('.woocommerce-account .register-button').on('click', function (e) {
      $('.woocommerce-account .register-button').slideToggle();
      $('.woocommerce-account .login-button').slideToggle();
      $('.woocommerce-account .register-form-toggle').slideToggle();
      $('.woocommerce-account .login-form-toggle').slideToggle();
      return true;
    });
    $('.woocommerce-account .login-button').on('click', function (e) {
      $('.woocommerce-account .login-button').slideToggle();
      $('.woocommerce-account .register-button').slideToggle();
      $('.woocommerce-account .register-form-toggle').slideToggle();
      $('.woocommerce-account .login-form-toggle').slideToggle();
      return true;
    });


    // PRODUCT HERO
    $(".hero-carousel").each(function (index, element) {
      var $this = $(this);
      var hero = new Swiper(this, {
        breakpoints: {
          320: {
            slidesPerView: 'auto',
            spaceBetween: 16,
            centeredSlides: true,
          },
          480: {
            slidesPerView: 'auto',
            spaceBetween: 24,
            centeredSlides: true,
          },
          768: {
            slidesPerView: 'auto',
            spaceBetween: 32,
            centeredSlides: true,
          },
          992: {
            slidesPerView: 'auto',
            spaceBetween: 40,
            centeredSlides: true,
          },
          1140: {
            slidesPerView: 'auto',
            spaceBetween: 44,
            centeredSlides: true,
          },
        }
      });
    });


    // PRODUCT HERO
    $(".skolka-image-carousel").each(function (index, element) {
      var $this = $(this);
      var carousel = new Swiper(this, {
        loop: true,
        breakpoints: {
          380: {
            slidesPerView: 1,
            spaceBetween: 26,
          },
          767: {
            slidesPerView: 2,
            spaceBetween: 26,
          },
          992: {
            slidesPerView: 2,
            spaceBetween: 26,
          },
          1140: {
            slidesPerView: 3,
            spaceBetween: 30,
          },
        }
      });
    });


    // SLIDER
    var sliderImages = new Swiper('.skolka-slider .slider-images', {

      navigation: {
        nextEl: '.slider-button-next',
        prevEl: '.slider-button-prev',
      },
      thumbs: {
        swiper: sliderContent
      },

      breakpoints: {
        320: {
          slidesPerView: 'auto',
          spaceBetween: 16,
          centeredSlides: true,
        },
        480: {
          slidesPerView: 'auto',
          spaceBetween: 24,
          centeredSlides: true,
        },
        768: {
          slidesPerView: 'auto',
          spaceBetween: 32,
          centeredSlides: true,
        },
        992: {
          slidesPerView: 'auto',
          spaceBetween: 40,
          centeredSlides: true,
        },
        1140: {
          slidesPerView: 'auto',
          spaceBetween: 44,
          centeredSlides: true,
        },
      }
    });


    var sliderContent = new Swiper('.skolka-slider .slider-contents', {
      slidesPerView: 1,
      effect: "creative",
      creativeEffect: {
        prev: {
          shadow: true,
          translate: [0, 0, -400],
        },
        next: {
          translate: ["100%", 0, 0],
        },
      },
    });

    if ($(".skolka-slider .slider-images")[0]) {
      sliderImages.controller.control = sliderContent;
      sliderContent.controller.control = sliderImages;
    } else {

    }


    // SKOLKA ACCORDION
    $('.skolka-accordion').find('.accordion-toggle').click(function () {
      $(this).next().slideToggle(250);
      $('.accordion-content').not($(this).next()).slideUp(400);
      $('.arrow-open').removeClass('arrow-open');
      $(this).find('.arrow').toggleClass('arrow-open');
    });
    $('.viewport').click(function () {
      $('body').toggleClass('mobile');
      $(this).text(function (i, v) {
        return v === 'View desktop' ? 'View mobile' : 'View desktop'
      })
      return false
    });


    // SKOLKA TABS
    $(function () {
      var activeIndex = $('.active-tab').index(),
        $contentlis = $('.skolka-tabs-content li'),
        $tabslis = $('.tabs li');
      $contentlis.eq(activeIndex).show();

      $('.skolka-tabs').on('click', 'li', function (e) {
        var $current = $(e.currentTarget),
          index = $current.index();
        $tabslis.removeClass('active-tab');
        $current.addClass('active-tab');
        $contentlis.hide().eq(index).show();
      });
    });


    // ALERT FADE OUT
    setInterval(function () {
      jQuery('.woocommerce-message, .woocommerce-error').fadeOut('slow')
    }, 4000);


    /* DISPLAY MORE LESS FILTER LISTING */
    var max = 8;
    $('.wpf_item > ul').each(function () {
      if ($(this).find(' > li').length > max) {
        $(this).find(' > li:gt(' + max + ')').hide();
        $(this).find(' > li:gt(' + max + ')').end().append('<li class="show-more-less-li"><span class="show_more">Show More</span></li>');

        $(this).find('.show-more-less-li').click(function (e) {
          $(this).siblings(':gt(' + max + ')').slideToggle();
          if ($(this).find('.show_more').length) {
            $(this).html('<span class="show_less">Show Less</span>');
          } else {
            $(this).html('<span class="show_more">Show More</span>');
          };
        });
      };
    });


    // CUSTOM SHOP BAR PRODUCT LIST TOGGLE
    if ($('.wpf_item .wpf_links > li').parent().find('ul').length > 0) {
      $('.wpf_links > li a').parent().find('ul').before('<svg width="16" height="16" class="arrow" viewBox="0 0 16 16" fill="#371F5E" xmlns="http://www.w3.org/2000/svg"><path d="M12 6.66656L11.06 5.72656L8 8.7799L4.94 5.72656L4 6.66656L8 10.6666L12 6.66656Z"></path></svg>');
    }

    $(".shop-filter .wpf_form .wpf_items_wrapper .wpf_links li svg").click(function () {
      if ($(this).closest("li").children("ul").length) {
        $(this).closest("li").children("ul").toggleClass('active');
        $(this).toggleClass('flip');
        $(this).closest("li").children("ul").slideToggle(500);
      }
    });


    /* DISPLAY MORE LESS FILTER WOO LISTING */
    var max = 8;
    $('.wc-block-product-categories > ul').each(function () {
      if ($(this).find(' > li').length > max) {
        $(this).find(' > li:gt(' + max + ')').hide();
        $(this).find(' > li:gt(' + max + ')').end().append('<li class="show-more-less-li"><span class="show_more">Show More</span></li>');

        $(this).find('.show-more-less-li').click(function (e) {
          $(this).siblings(':gt(' + max + ')').slideToggle();
          if ($(this).find('.show_more').length) {
            $(this).html('<span class="show_less">Show Less</span>');
          } else {
            $(this).html('<span class="show_more">Show More</span>');
          };
        });
      };
    });


    // BRANDS CAROUSEL
    $(".brands-carousel").each(function (index, element) {
      var $this = $(this);
      var brands = new Swiper(this, {
        slidesPerView: 1,
        spaceBetween: 10,
        pagination: {
          el: ".brands-carousel .swiper-pagination",
          clickable: true,
        },
        breakpoints: {
          640: {
            slidesPerView: 2,
            slidesPerGroup: 2,
            spaceBetween: 20,
            touchRatio: 1,
          },
          768: {
            slidesPerView: 3,
            slidesPerGroup: 3,
            spaceBetween: 40,
            touchRatio: 1,
          },
          1024: {
            slidesPerView: 5,
            slidesPerGroup: 5,
            spaceBetween: 50,
            touchRatio: 0,
          },

          1140: {
            slidesPerView: 6,
            slidesPerGroup: 6,
            spaceBetween: 50,
            touchRatio: 0,
          },
        },

      });
    });


    // PRODUCT CATEGORY CAROUSEL
    $(".woocommerce-product-category-carousel").each(function (index, element) {
      var carousel_columns = $(this).data('columns');
      if ($(this).data('loop').toString() == 'yes') {
        var loop = true
      }
      var autoplay = $(this).data('autoplay');
      if ($(this).data('autoplay').toString() == 'true') {
        autoplay = {
          delay: $(this).data('autoplay-delay'),
          disableOnInteraction: false,
        };
      }

      var $this = $(this);
      var swiper = new Swiper(this, {
        spaceBetween: 10,
        autoplay: autoplay,
        loop: loop,
        touchRatio: 0,
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
        breakpoints: {
          640: {
            slidesPerView: 2,
            spaceBetween: 20,
            touchRatio: 1,
          },
          768: {
            slidesPerView: 3,
            spaceBetween: 40,
          },
          touchRatio: 1,
          1024: {
            slidesPerView: carousel_columns - 2,
            spaceBetween: 50,
            touchRatio: 0,
          },

          1140: {
            slidesPerView: carousel_columns,
            spaceBetween: 50,
            touchRatio: 1,
          },
        },

      });
    });


    // SITE SLIDER
    $(".site-slider").each(function (index, element) {
      var swiper = new Swiper(this, {
        loop: true,
        slidesPerView: 1,
        touchRatio: 0,
        navigation: {
          prevEl: ".site-slider .swiper-button-prev",
          nextEl: ".site-slider .swiper-button-next",
        },

      });
    });


    // CATEGORY SLIDER
    $(".category-slider").each(function (index, element) {
      var swiper = new Swiper(this, {
        loop: true,
        effect: "fade",
        slidesPerView: 1,
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },

      });
    });


    // HIDE CAROUSEL Controls
    var $carousels = $('.swiper-carousel');
    $carousels.each(function () {
      var $carousel = $(this);
      var numSlides = $carousel.find('.swiper-slide').length;
      var dataColumn = $carousel.attr('data-column');
      if (dataColumn >= numSlides) {
        $carousel.find('.swiper-pagination').hide();
        $carousel.find('.slider-button-prev, .slider-button-next').hide();
      }
    });


    // SEARCH BAR FETCH
    $(".input_search").keyup(function () {
      if ($(this).val().length > 2) {
        $("#datafetch").show();
      } else {
        $("#datafetch").hide();
      }
    });


    // FOCUS OVERLAY
    $('.navbar .navbar-search form input[type=text]').focus(function () {
      $('body').addClass('overlay-layer-active');
      setTimeout(function () {
        $('.navbar').addClass('z-index');
      }, 0);
    });

    $('.overlay-layer').on('click', function () {
      $('body').removeClass('overlay-layer-active');
      setTimeout(function () {
        $('.navbar').removeClass('z-index');
      }, 400);
      $('.navbar .navbar-search .search_result').hide();
    });


    // QTY BUTTONS
    if (!String.prototype.getDecimals) {
      String.prototype.getDecimals = function () {
        var num = this,
          match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
        if (!match) {
          return 0;
        }
        return Math.max(0, (match[1] ? match[1].length : 0) - (match[2] ? +match[2] : 0));
      }
    }
    $(document.body).on('click', '.plus, .minus', function () {
      var $qty = $(this).closest('.quantity').find('.qty'),
        currentVal = parseFloat($qty.val()),
        max = parseFloat($qty.attr('max')),
        min = parseFloat($qty.attr('min')),
        step = $qty.attr('step');

      if (!currentVal || currentVal === '' || currentVal === 'NaN') currentVal = 0;
      if (max === '' || max === 'NaN') max = '';
      if (min === '' || min === 'NaN') min = 0;
      if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN') step = 1;

      if ($(this).is('.plus')) {
        if (max && (currentVal >= max)) {
          $qty.val(max);
        } else {
          $qty.val((currentVal + parseFloat(step)).toFixed(step.getDecimals()));
        }
      } else {
        if (min && (currentVal <= min)) {
          $qty.val(min);
        } else if (currentVal > 0) {
          $qty.val((currentVal - parseFloat(step)).toFixed(step.getDecimals()));
        }
      }

      $qty.trigger('change');
      $("[name='update_cart']").trigger("click");
    });


    // SCROLL UP
    const goTopButton = document.getElementById('go-top-btn');
    const progressBar = document.getElementById('progress-bar');

    if (goTopButton) {
      window.addEventListener('scroll', () => {
        const scrollTop = document.documentElement.scrollTop;
        const windowHeight = document.documentElement.clientHeight;
        const scrollHeight = document.documentElement.scrollHeight;

        const progress = (scrollTop / (scrollHeight - windowHeight)) * 100;
        progressBar.style.height = `${progress}%`;

        if (scrollTop > 200) {
          goTopButton.classList.add('show');
        } else {
          goTopButton.classList.remove('show');
        }
      });

      goTopButton.addEventListener('click', () => {
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      });
    }


  });
  // END DOCUMENT READY


  // SHARE COPY URL BUTTON
  var $temp = $("<input>");
  var $url = $(location).attr('href');
  $('span.share-button').on('click', function () {
    $("body").append($temp);
    $temp.val($url).select();
    document.execCommand("copy");
    $temp.remove();
    $('.woocommerce-notices-wrapper').parent().before('<div class="woocommerce-message share-url">URL copied for share !</div>');
    $(".share-url").text("URL copied for share !");
  })


  // COUNTDOWN
  if ($("#js-countdown").hasClass("countdown")) {
    const countdown = new Date("September 7, 2024");

    function getRemainingTime(endtime) {
      const milliseconds = Date.parse(endtime) - Date.parse(new Date());
      const seconds = Math.floor((milliseconds / 1000) % 60);
      const minutes = Math.floor((milliseconds / 1000 / 60) % 60);
      const hours = Math.floor((milliseconds / (1000 * 60 * 60)) % 24);
      const days = Math.floor(milliseconds / (1000 * 60 * 60 * 24));

      return {
        'total': milliseconds,
        'seconds': seconds,
        'minutes': minutes,
        'hours': hours,
        'days': days,
      };
    }

    function initClock(id, endtime) {
      const counter = document.getElementById(id);
      const daysItem = counter.querySelector('.js-countdown-days');
      const hoursItem = counter.querySelector('.js-countdown-hours');
      const minutesItem = counter.querySelector('.js-countdown-minutes');
      const secondsItem = counter.querySelector('.js-countdown-seconds');

      function updateClock() {
        const time = getRemainingTime(endtime);

        daysItem.innerHTML = time.days;
        hoursItem.innerHTML = ('0' + time.hours).slice(-2);
        minutesItem.innerHTML = ('0' + time.minutes).slice(-2);
        secondsItem.innerHTML = ('0' + time.seconds).slice(-2);

        if (time.total <= 0) {
          clearInterval(timeinterval);
        }
      }

      updateClock();
      const timeinterval = setInterval(updateClock, 1000);
    }

    initClock('js-countdown', countdown);
  }


})(jQuery);
