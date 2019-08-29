(function () {

    //galerie home
    jQuery(window).on("load", function () {
        jQuery('.masonry').masonry({
            itemSelector: '.grid-item',
            columnWidth: 270,
            gutter: 20,
            fitWidth: true
        });
    });

    jQuery('.galerie-filter').on("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        jQuery('.grid-item').css({'display': 'none'});

        var filter = jQuery(this).attr('data-filter');

        jQuery('.' + filter).show();

        jQuery('.masonry').masonry({
            itemSelector: '.grid-item',
            columnWidth: 270,
            gutter: 20,
            fitWidth: true
        });

    });

    jQuery('.diapo-video').slick({
        infinite: false,
        // slidesToShow: 3,
        slidesToScroll: 1,
        centerMode: false,
        responsive: [
            {
                breakpoint: 2500,
                settings: {
                    slidesToShow: 3,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 550,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });

    // sroll observer
    if (typeof IntersectionObserver === 'undefined') {
        jQuery('.show-scroll').each(function (v) {
            jQuery(this).addClass('show-scroll-visible');
        });
    }else{
        const ratio = .2;
        const options = {
            root: null,
            rootMargin: '0px',
            threshold: ratio
        };

        const visible_element = function (entries, observer) {
            entries.forEach(function (entry) {
                if (entry.intersectionRatio > ratio) {
                    entry.target.classList.add('show-scroll-visible');
                    observer.unobserve(entry.target);
                }
            })
        };

        const observer = new IntersectionObserver(visible_element, options);

        const target = document.querySelectorAll('.show-scroll').forEach(function (v) {
            observer.observe(v);
        });
    }


    //vidéos
    if (jQuery('#video_full').length) {
        const first_url_video = getEmbedUrl(jQuery('.video_change').first().attr('data-embed-url'));
        jQuery('#video_full').attr('src', first_url_video);
    }

    jQuery('.video_change').on('click', function () {
        const videoUrl = getEmbedUrl(jQuery(this).attr('data-embed-url'));
        jQuery('#video_full').attr('src', videoUrl);
    });

    function getEmbedUrl(url) {
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
        const match = url.match(regExp);

        if (match && match[2].length == 11) {
            return '//www.youtube.com/embed/' + match[2];
        } else {
            return 'error';
        }
    }

    //share popup
    jQuery('.share-btn').click(function (e) {
        e.stopPropagation();
        jQuery('.share-box').hide(200);
        jQuery(this).next().toggle(200);
    });

    jQuery('html').click(function () {
        jQuery('.share-box').hide(200);
    });

    //return to top
    jQuery(window).scroll(function () {
        if (jQuery(this).scrollTop() >= 250) {        // If page is scrolled more than 50px
            jQuery('#return-to-top').fadeIn();    // Fade in the arrow
        } else {
            jQuery('#return-to-top').fadeOut();   // Else fade out the arrow
        }
    });
    jQuery('#return-to-top').click(function () {      // When arrow is clicked
        jQuery('body,html').animate({
            scrollTop: 0                       // Scroll to top of body
        }, 500);
    });

})();

window.onload = function () {
    jQuery( ".loader" ).animate({
        opacity: 0,
        transition: "opacity 2s ease-out",
    }, 1500, function(){
        jQuery(this).css({'display':'none'})
    } );
}