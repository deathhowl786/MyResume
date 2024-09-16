$(document).ready(function() {
    
    $("body").addClass("default-theme");
    progressBarAnimation();

    $(".theme").children().each(function(){

      var value = "linear-gradient(to bottom right, "+
                    $(this).css("--accent-color") +", "+
                    $(this).css("--background-color")+", "+
                    $(this).css("--highlight-color")+")";

      $(this).children("span").css("background", value);
    })

    $(".theme li").on("click", function(){
      $("body").removeClass($("body").attr("class"));
      $("body").addClass($(this).attr("class"));
    });


    function progressBarAnimation(){
        $('.progress').each(function() {
            var percentage = $(this).data('percentage');
            $(this).animate({width: percentage + '%'}, 1500);
        });
    }

    function startTypingAnimation() {
      var $typingText = $('#typing-text');
      var text = $typingText.text();
      $typingText.text('');

      function typeWriter(text, i, fnCallback) {
        if (i < text.length) {
          $typingText.append(text.charAt(i));
          i++;
          setTimeout(function() {
            typeWriter(text, i, fnCallback);
          }, 100);
        } else if (typeof fnCallback === 'function') {
          fnCallback();
        }
      }

      typeWriter(text, 0);
    }

    $('a[href="#home"]').on('click', function() {
      startTypingAnimation();
    });


    $('a[href="#skills"]').on('click', function() {
      $('.progress').each(function(){
        $(this).css({"width":"0%"});
      })
      setTimeout(progressBarAnimation, 1000);
    });



    $('.socials a').on('click', function(e) {
      e.preventDefault();
       $('#paper-plane').addClass('fly');

      setTimeout(function() {
        $('#paper-plane').removeClass('fly');
      }, 2000);


      var link = $(this).attr('href');
      setTimeout(function() {
        window.open(link, '_blank');
      }, 1000);

    });

    function showNav() {
      var x = document.getElementById("myTopnav");
      if (x.className === "topnav") {
        x.className += " responsive";
      } else {
        x.className = "topnav";
      }
    }


    $("#tiles").on("click", function(){
      var nav = $(".nav")[0];
      if($(nav).attr("class") === "nav"){
        $("#tiles-icon").attr("class", "fa fa-bars-staggered");
        $(nav).addClass("responsive");
      }else{
        $(nav).removeClass("responsive");
        $("#tiles-icon").attr("class", "fa fa-bars");
      }
    })


    $(".nav li a").on("click", function(){
      var nav = $(".nav")[0];
      if($(this).attr("href").startsWith("#")){
        $(nav).removeClass("responsive");
        $("#tiles-icon").attr("class", "fa fa-bars");  
      }
    });
});
