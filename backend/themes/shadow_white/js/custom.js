
$(document).ready(function(){    
 jQuery.fn.liScroll = function(settings) {
  settings = jQuery.extend({
    travelocity: 0.02
    }, settings);   
    return this.each(function(){
        var $strip = jQuery(this);
        $strip.addClass("newsticker")
        var stripHeight = 1;
        $strip.find("li").each(function(i){
          stripHeight += jQuery(this, i).outerHeight(true);
        });
        var $mask = $strip.wrap("<div class='mask'></div>");
        var $tickercontainer = $strip.parent().wrap("<div class='tickercontainer'></div>");               
        var containerHeight = $strip.parent().parent().height();
        $strip.height(stripHeight);     
        var totalTravel = stripHeight;
        var defTiming = totalTravel/settings.travelocity;
        function scrollnews(spazio, tempo){
        $strip.animate({top: '-='+ spazio}, tempo, "linear", function(){$strip.css("top", containerHeight); scrollnews(totalTravel, defTiming);});
        }
        scrollnews(totalTravel, defTiming);       
        $strip.hover(function(){
        jQuery(this).stop();
        },
        function(){
        var offset = jQuery(this).offset();
        var residualSpace = offset.top + stripHeight;
        var residualTime = residualSpace/settings.travelocity;
        scrollnews(residualSpace, residualTime);
        });     
    }); 
};

 });

$(function(){
    $("ul#ticker01").liScroll();
});

 
/* ----------------------------------------------------------- */
  /* header sticky
  /* ----------------------------------------------------------- */
$( document ).ready(function() {
$('#alert').affix({
    offset: {
      top: 10, 
      bottom: function () {
      }
    }
  })  
});

    
         jQuery('.counter-value').counterUp({
                delay: 10,
                time: 2000
            });


    
      $(document).ready(function ($) {
                $(document).on('click', '[data-toggle="lightbox"]:not([data-gallery="navigateTo"]):not([data-gallery="example-gallery"])', function(event) {
                    event.preventDefault();
                    return $(this).fancyLightbox({
                        onShown: function() {
                            if (window.console) {
                                return console.log('Checking our the events huh?');
                            }
                        },
            onNavigate: function(direction, itemIndex) {
                            if (window.console) {
                                return console.log('Navigating '+direction+'. Current item: '+itemIndex);
                            }
            }
                    });
                });

                // disable wrapping
                $(document).on('click', '[data-toggle="lightbox"][data-gallery="example-gallery"]', function(event) {
                    event.preventDefault();
                    return $(this).fancyLightbox({
                        wrapping: false
                    });
                });

                //Programmatically call
                $('#open-image').click(function (e) {
                    e.preventDefault();
                    $(this).fancyLightbox();
                });
                $('#open-youtube').click(function (e) {
                    e.preventDefault();
                    $(this).fancyLightbox();
                });

       
            });

      
   /* ----------------------------------------------------------- */
  /* header sticky
  /* ----------------------------------------------------------- */


    //Check to see if the window is top if not then display button
    jQuery(window).scroll(function(){
      if (jQuery(this).scrollTop() > 300) {
        jQuery('.scrollToTop').fadeIn();
      } else {
        jQuery('.scrollToTop').fadeOut();
      }
    });


     
    //Click event to scroll to top

    jQuery('.scrollToTop').click(function(){
      jQuery('html, body').animate({scrollTop : 0},800);
      return false;
    });  
//end Click event to scroll to top


$('#carouselExample').on('slide.bs.carousel', function (e) {

  
    var $e = $(e.relatedTarget);
    var idx = $e.index();
    var itemsPerSlide = 3;
    var totalItems = $('.carousel-item').length;
    
    if (idx >= totalItems-(itemsPerSlide-1)) {
        var it = itemsPerSlide - (totalItems - idx);
        for (var i=0; i<it; i++) {
            // append slides to end
            if (e.direction=="left") {
                $('.carousel-item').eq(i).appendTo('.carousel-inner');
            }
            else {
                $('.carousel-item').eq(0).appendTo('.carousel-inner');
            }
        }
    }
});



 // Clients carousel (uses the Owl Carousel library)
  $(".staff-carousel").owlCarousel({
    autoplay: true,
    nav    : true,
    dots: true,
    loop: true,
    navText : ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
    responsive: { 0: { items: 1 }, 768: { items: 4 }, 900: { items: 4 }
    }
  });

   // testimonial carousel (uses the Owl Carousel library)
  $(".testimonial-carousel").owlCarousel({
    autoplay: true,
    nav    : false,
    dots: true,
    loop: true,
    //navText : ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
    responsive: { 0: { items: 1 }, 768: { items: 2 }, 900: { items: 2 }
    }
  });

   // course carousel (uses the Owl Carousel library)
  $(".courses-carousel").owlCarousel({
    autoplay: true,
    nav    : true,
    dots: false,
    loop: true,
    navText : ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
    responsive: { 0: { items: 1 }, 768: { items: 3 }, 900: { items: 3 }
    }
  });

   $(document).ready(function() {
    $('a.thumb').click(function(event){
      event.preventDefault();
      var content = $('.modal-body');
      content.empty();
        var title = $(this).attr("title");
        $('.modal-title').html(title);        
        content.html($(this).html());
        $(".modal-profile").modal({show:true});
    });
  });
/*--dropify--*/
$(document).ready(function(){
    $('.filestyle').dropify();
});
/*--end dropify--*/


