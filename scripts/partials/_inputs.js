// MAKE FORM INPUTS BETTER

// upon focus, select entire text of input (UX)
$('input, .seasons').focus(function() {
  var $this = $(this);
  $this.select();

  window.setTimeout(function() {
    $this.select();
  }, 1);

  // Work around WebKit's little problem
  function mouseUpHandler() {
    // Prevent further mouseup intervention
    $this.off("mouseup", mouseUpHandler);
    return false;
  }

  $this.mouseup(mouseUpHandler);
});



// add description upon seasons input focus
$('.seasons').focus(function() {
  $('.seasons-description').removeClass('visuallyhidden');
}).blur(function() {
  $('.seasons-description').addClass('visuallyhidden');
})



// disables text input for season number (only numerical)
$('.seasons').keydown(function(e) {
  var co = e.keyCode
  if ((co > 57 && co < 96) || co > 105) return false
})


// on seasons change if # season bigger than total TV show # seasons reduce it to max
$('.seasons').change(function() {
  var value = parseInt($(this).val(), 10),
      maxValue = $(this).attr('max');

  if (value > maxValue)
    $(this).val(maxValue);

  if (value < 1)
    $(this).val(1);
})