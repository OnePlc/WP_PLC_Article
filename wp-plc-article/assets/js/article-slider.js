jQuery(function() {
    jQuery('.plc-article-swiper-container').each(function () {
        var iSliderPerView = jQuery(this).attr('data-slides-per-view');
        var mySwiper = new Swiper(jQuery(this), {
            speed: 400,
            spaceBetween: 8,
            slidesPerView: iSliderPerView,
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                    type: 'bullets',
            },
            autoplay: {
                delay: 5000,
            },
            breakpoints: {
                // when window width is >= 320px
                320: {
                    slidesPerView: 1,
                    spaceBetween: 8
                },
                // when window width is >= 480px
                480: {
                    slidesPerView: 1,
                    spaceBetween: 8
                },
                // when window width is >= 640px
                640: {
                    slidesPerView: iSliderPerView,
                    spaceBetween: 8
                }
            }
        });
    });
});