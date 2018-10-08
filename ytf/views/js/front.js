/**
 * @name ytf
 * @version 1.0 [March 07, 2018]
 * @author FadiDev
 * @copyright Copyright 2018 FadiDev [gary at pimclick.com]
 * @fileoverview A controller document for Ytf module.
 */
/* A code to control the ytf module frontend
 */
function jCRequest(finalToken) {
  try {
    $.ajax({
      url: window.location.href.replace('youtube', '') + 'module/ytf/default',
      type: 'POST',
      data: 'ajax=true&m=more&t=' + finalToken + '&l=4',
      beforeSend: function() {
        jQuery('.ajax-load').show();
      },
      error: function(xhr) {
        swal('Error', xhr, 'error');
        window.location.reload();
      },
      complete: function(data) {
        jQuery('.ajax-load').hide();
        if (data.responseJSON.status === 'hasNoAccess') {
          swal('Error', 'You has no access! page will reload.', 'error');
          window.location.reload();
        } else if (data.responseJSON.error === 'No data found') {
          swal('Error', data.responseJSON.error + ', page will reload.', 'error');
          window.location.reload();
        } else {
          if (data.responseJSON.JCYouTube.length === 3 || data.responseJSON.JCYouTube.length === 4) {
            localStorage.setItem('token', data.responseJSON.nextPageToken);
            for (var i = 0; i < data.responseJSON.JCYouTube.length; i++) {
              var videoId = data.responseJSON.JCYouTube[i].videoId;
              var videoTitle = data.responseJSON.JCYouTube[i].videoTitle;
              var videoImage = data.responseJSON.JCYouTube[i].videoImage;
              var template = '<div class="col-sm-3 col-md-3 col-xs-12 col-lg-3 animated fadeIn" id="youTube">';
              template += '<a href="https://www.youtube.com/embed/' + videoId + '" alt="' + videoTitle + '" title="' + videoTitle + '">';
              template += '<img src="' + videoImage + '" alt="' + videoTitle + '" title="' + videoTitle + '" style="width:100%;cursor:pointer;"/>';
              template += '</a>';
              template += '<p style="color: #4f4e4e;padding-top: 20px;min-height: 55px;"><strong>' + videoTitle + '</strong></p>';
              template += '</div>';
              jQuery('#mainRow').append(template);
            }
          }
        }
      },
      dataType: 'JSON',
      encode: true
    });
  } catch (err) {
    swal('Error', err.message, 'error');
  }
}
/* A onload() event with some jquery
 */
window.onload = function() {
  localStorage.clear();
  $('body').delegate('.col-sm-3.col-md-3.animated.fadeIn a','click',function() {
    jQuery(this)[0].setAttribute('data-fancybox', '');
  });
}
