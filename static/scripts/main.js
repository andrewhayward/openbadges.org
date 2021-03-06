(function(){
  $(document).ready(function(){
    /* Cheating takes you straight to the win panel 
       when you 'get started' in the UI 
     */
    var cheat = false;
    var startSlide = 0;

    initWinPanel();
    if( cheat ){
      showWinPanel();
      startSlide = $('.slide').length;
    }

    $('#slides').slides({
      start: startSlide
    });
    $().quiz({ 
      onWin: showWinPanel 
    });
    $('.fancybox').fancybox({
      closeBtn: true,
      arrows: false
    });
  });

  function initWinPanel() {
    /* Badges 101 badge acceptance panel */
    /* 0. If user skips to end, sees #incomplete panel
       1. User "wins", sees #win panel with badge and backpack/navigator badge links
       2. Clicks backpack, sees #get-badge subpanel with email submission form
       3. Submits email, sees #after-badge subpanel with external links
    */
    $('#win').hide();

    $('#get-badge').hide();
    $('#after-badge').hide();
    $('#push-to-backpack').one('click', function(){
      $('#nav-panel').hide();
      $('#get-badge').fadeIn();
    }); 
    
    $("#get-badge form").submit(function() {
      var email = $(this).find("input#email").val().trim();
      if (QuickBadge.validateEmail(email)) {
        var hashedEmail = 'sha256$' + sha256_digest(email);
        var publish = QuickBadge.publish({
          service: "http://hackpub.hackasaurus.org/publish",
          assertion: {
            "recipient": hashedEmail,
            "badge": {
              "version": "0.5.0",
              "name": "Badges 101",
              "image": $("#badges101")[0].src,
              "description": "You really get badges!",
              "criteria": "http://badges-101.openbadges.org/",
              "issuer": {
                "origin": "http://www.openbadges.org",
                "name": "Open Badges",
                "org": "Mozilla Foundation",
                "contact": "hai2u@openbadges.org"
              }
            }
          }
        });
        $("#get-badge form").fadeOut(function() {
          $("#throbber").fadeIn(function() {
            publish.fail(function() {
              alert("Sorry, an error occurred. Please try again later.");
              $("#get-badge form").show();
              $("#throbber").hide();
            });
            publish.done(function(url) {
              $("#throbber").fadeOut();
              QuickBadge.issue(url).done(function(errors, successes) {
                if (successes.length) {
                  $("#get-badge").hide();
                  $("#after-badge").show();
                }
                else {
                  $("#get-badge form").show();
                  $("#throbber").hide();
                }
                console.log("errors", errors, "successes", successes);
              });
            });
          });
        });
      } else
        alert("Please provide a valid e-mail address.");
      return false;
    });
  }

  function showWinPanel(){
    $('#incomplete').hide();
    $('#win').show();
  }
})();
