// REMOVING SHOW FUNCTIONS

// when removing a show, animate it, and subtract from timer/clock the time wasted
$('.container__list-of-shows').on('click touchstart', '.js-remove-item', function() {
  $(this).parent().removeClass().addClass('remove-tv-show');

  setTimeout(function() {
    $('.remove-tv-show').remove();
  }, 200);

  // time wasted of particular show is saved from before
  timeWastedInMinutes = $(this).siblings('.container__list-of-shows__info').children('.container__list-of-shows__info__wasted-time.visuallyhidden').text();

  // calculate total time wasted in minutes
  totalTimeWastedInMinutes = totalTimeWastedInMinutes - timeWastedInMinutes;
  if (totalTimeWastedInMinutes <= 0)
    totalTimeWastedInMinutes = 0;

  // convert into days, hours, minutes
  convertMinutes(totalTimeWastedInMinutes);

  // save to localstorage
  save(totalTimeWastedInMinutes);



  // if TV list is empty, hide top container
  if (($('.container__list-of-shows li').length) === 1) {
    $('.result-container').parent().addClass('hiding');

    setTimeout(function() {
      $('.result-container').addClass('visuallyhidden');
    }, 150);
  }



  // shows time of removed TV show temporarily
  showTemporaryTimerOfPreviousShow(timeWastedInMinutes, true);

  return false
})