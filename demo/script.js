$(document).ready(function() {
  
  var animating = false,
      submitPhase1 = 1100,
      submitPhase2 = 400,
      logoutPhase1 = 800,
      channelId = "",
      $login = $(".login"),
      $app = $(".app");
  
  function ripple(elem, e) {
    $(".ripple").remove();
    var elTop = elem.offset().top,
        elLeft = elem.offset().left,
        x = e.pageX - elLeft,
        y = e.pageY - elTop;
    var $ripple = $("<div class='ripple'></div>");
    $ripple.css({top: y, left: x});
    elem.append($ripple);
  };
  
	$(document).on("click", ".login__submit", function(e) {
		var str = $("#username").val();
		if (str.length == 0) {
			alert('Enter channel username!');
			return
		} else {
			if (animating) return;
			animating = true;
			channelId = str;
			var that = this;
			ripple($(that), e);
			$(that).addClass("processing");
			$.getJSON('http://miladesign.ir/tchannel/telegram.php?id=' + str, function(data) {
				if (data.is_channel == false) {
					$(that).removeClass("processing");
					animating = false;
					alert('Channel not found!');
				} else {
					$(that).addClass("success");
					$app.show();
					$app.css("top");
					$app.addClass("active");
					$login.hide();
					$login.addClass("inactive");
					animating = false;
					$(that).removeClass("success processing");
					$("#channel_name").html(data.title);
					$("#channel_members").html(data.members + " Members");
					$("#channel_desc").html(data.description);
					$("#channel_photo").attr('src', data.photo);
				}
			});
		}
	});
  
  $(document).on("click", ".app__logout", function(e) {
    if (animating) return;
    $(".ripple").remove();
    animating = true;
    var that = this;
    $(that).addClass("clicked");
    setTimeout(function() {
      $app.removeClass("active");
      $login.show();
      $login.css("top");
      $login.removeClass("inactive");
    }, logoutPhase1 - 120);
    setTimeout(function() {
      $app.hide();
      animating = false;
	  channelId = "";
      $(that).removeClass("clicked");
    }, logoutPhase1);
  });
  
  $(document).on("click", ".app__visit", function(e) {
    window.open("https://t.me/" + channelId, '_blank');
  });
  
});