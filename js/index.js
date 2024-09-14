$(document).ready(function() {


    progressBarAnimation();

    function progressBarAnimation(){
        $('.progress').each(function() {
            var percentage = $(this).data('percentage');
            $(this).animate({width: percentage + '%'}, 1500);  // Animates the width to the percentage in 1 second
            // $(this).animate({width: percentage + '%'}, 1500);
        });
    }

    function startTypingAnimation() {
      var $typingText = $('#typing-text');
      var text = $typingText.text(); // Get the text
      $typingText.text(''); // Clear the text

      // Function to simulate typing effect
      function typeWriter(text, i, fnCallback) {
        if (i < text.length) {
          $typingText.append(text.charAt(i));
          i++;
          setTimeout(function() {
            typeWriter(text, i, fnCallback);
          }, 100); // Adjust the speed here
        } else if (typeof fnCallback === 'function') {
          fnCallback();
        }
      }

      // Start typing animation
      typeWriter(text, 0);
    }
    // Call the function to start animation when the home link is clicked
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


      // Optionally, you can add a delay before navigating to the link
      var link = $(this).attr('href');
      setTimeout(function() {
        window.open(link, '_blank'); // Navigate after animation
      }, 1000); // 1 second delay to allow the animation to complete

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
        $(nav).addClass("responsive");
      }else{
        $(nav).removeClass("responsive");
      }
    })

});
