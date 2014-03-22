// KEYBOARD SHORTCUTS

// show about text on ESC
$('body').keydown(function(e) {

  // if ESC key
  if (e.keyCode === 27) {
    // hide dialog/modal window
    if ($('body').hasClass('noScroll')) {
      hideModalWindow();
    } else {
      showModalWindow();
    }
  }
})





// disable ENTER if no valid TV show has been selected
$(window).keydown(function(event){
  if(event.keyCode == 13)
    if ($('.seasons').is(':focus') || $('.submit').is(':focus'))
      if (($('.select2-input').val().length <= 0) || ($('.select2-input').val().length !== 0))
        if (!hasSelectedShow) {
          // if (!$(this).val().length !== 0) {
            event.preventDefault();
            return false;
          }
});