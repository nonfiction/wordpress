import './animation.css';
import './style.css';

import $ from 'jquery';

let timer;
$(document).ready(function() {
    let time = 8;

    // Animation properties
    let directions = ['', 'reverse'];
    let keyframes = [
        'kenburns-top',
        'kenburns-top-right',
        'kenburns-bottom-right',
        'kenburns-bottom',
        'kenburns-bottom-left',
        'kenburns-left',
        'kenburns-top-left',
    ];

    // Choose random element in array
    const rand = (arr) => arr[Math.floor(Math.random() * arr.length)];
    var imageindex = 0;
    const swap = (swapSlides = true) => {

        var gallery = $('.nf-hotel-gallery li');
        var totalNumber = gallery.length;
        var ul = $(this);
        var selected_index = imageindex % totalNumber;
        gallery.removeClass("active");

        gallery.eq(selected_index).addClass("active");
        var keyframe = gallery.eq(selected_index).data("keyframe");
        var direction = gallery.eq(selected_index).data("direction");

        var selectedFrame = rand(keyframes.filter(item => item !== keyframe));
        var selecteddirection = rand(directions.filter(item => item !== direction))
        gallery.eq(selected_index).css({
            animation: `${selectedFrame} ${time}.5s linear ${selecteddirection} both`
        });

        gallery.eq(selected_index).data("keyframe", selectedFrame);
        gallery.eq(selected_index).data("direction", selecteddirection);


        $('.nf-hotel-gallery__caption').removeClass('is-on');
        $('.nf-hotel-gallery__caption[data-caption="' + $('.nf-hotel-gallery li.active').attr('data-caption') + '"]').addClass('is-on')


        // if (swapSlides) {
        //   $(ul).append( $('li:first-child', ul).remove() ); 
        // }

        void ul.offsetWidth;

        // $('li:first-child', ul).css({
        //   animation: `${rand(keyframes)} ${time}.5s linear ${rand(directions)} both`
        // });

        imageindex++;

        timer = setTimeout(swap, (time * 1000));

    }

    $('.nf-hotel-gallery > ul').each(function() {

        // Slideshow

        swap(false);

    });

    if ($(".grid-gallery-items").length > 0) {
        // console.log("windows loading for now!!!!");
        var hotelitems = $(".grid-gallery-items").html();

        var galleryThumbnails = '<div class="owl-custom-dots owl-carousel owl-theme">';
        $(".grid-gallery-items img").each(function(index, value) {
            if (index == 0) {
                galleryThumbnails += '<div class="owl-dot-image active"><img src="' + $(value).attr('src') + '" /></div>';
            } else {
                galleryThumbnails += '<div class="owl-dot-image"><img src="' + $(value).attr('src') + '" /></div>';
            }

        })
        galleryThumbnails += '</div>';

        var hotelGallery = '<div class="grid-gallery-carousel active"><div class="owl-carousel-container"><div class="close-gallery-modal">&times;</div><div class="main-owl owl-carousel owl-theme">' + hotelitems + '</div>' + galleryThumbnails + '</div></div>';
        $("body").append(hotelGallery);

        jQuery(".main-owl").owlCarousel({
            margin: 0,
            lazyLoad: true,
            loop: true,
            nav: false,
            items: 1,
            dots: true,
        }).on("dragged.owl.carousel", function(event) {
            change_dot(event.relatedTarget.$element);
        });

        jQuery(".owl-custom-dots").owlCarousel({
            loop: false,
            margin: 7,
            nav: true,
            autoWidth: true,
            dots: true
        });

        jQuery(".grid-gallery-carousel").removeClass("active");
    }

    function change_dot(event) {
        var eventelement = event;
        var activedot = eventelement.find(".owl-dot.active").index();
        eventelement.closest(".owl-carousel-container").find(".owl-custom-dots .owl-dot-image").removeClass("active");
        eventelement.closest(".owl-carousel-container").find(".owl-custom-dots .owl-dot-image:eq(" + activedot + ")").addClass("active");
        var thumbowl = jQuery(".owl-custom-dots");
        var activeslider = thumbowl.trigger('to.owl.carousel', [activedot, 300, true]);
        console.log(activeslider);
    }

    $('.owl-custom-dots .owl-item').click(function() {
        $(this).closest(".owl-custom-dots").find(".owl-dot-image").removeClass("active");
        $(this).find(".owl-dot-image").addClass("active");
        var owl = jQuery(this).closest('.owl-carousel-container').find('.main-owl');
        owl.trigger('to.owl.carousel', [$(this).index(), 300]);
    });

    $(".close-gallery-modal").click(function() {
        $(".grid-gallery-carousel").removeClass("active");
    });

    $(".gallery-grid-select").click(function() {
        $(".grid-gallery-carousel").addClass("active");
    });

    // $(document).on("click", ".owl-custom-dots .owl-next", function(){
    //   var owl = jQuery(this).closest('.owl-carousel-container').find(".main-owl");
    //   change_dot(owl);
    // })
    $(".owl-carousel-container .owl-custom-dots .owl-next").click(function() {
        var owl = jQuery(this).closest('.owl-carousel-container').find(".main-owl");
        owl.trigger('next.owl.carousel');
        change_dot(owl);
    });
    $(".owl-carousel-container .owl-custom-dots .owl-prev").click(function() {
        var owl = jQuery(this).closest('.owl-carousel-container').find(".main-owl");
        owl.trigger('prev.owl.carousel');
        change_dot(owl);
    });


    $(".gallery-step-button").click(function() {
        var gallery = $('.nf-hotel-gallery li');
        var currentindex = $('.nf-hotel-gallery li.active').index();
        if ($(this).hasClass("gallery-prev-button")) {
            if (currentindex == 0) {
                imageindex = gallery.length - 1
            } else {
                imageindex = currentindex - 1
            }
            timer && clearTimeout(timer);
            swap(false);
        } else {
            if ((currentindex + 1) == gallery.length) {
                imageindex = 0
            } else {
                imageindex = currentindex + 1
            }
            timer && clearTimeout(timer);
            swap(false);
        }
    });






});