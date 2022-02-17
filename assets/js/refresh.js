var idleTime = 0;
$(document).on("DOMContentLoaded", function () {
  //Increment the idle time counter every minute.
  var idleInterval = setInterval(timerIncrement, 6000); 

  //Zero the idle timer on mouse movement.
  $(this).on("mousemove", function (e) {
    idleTime = 0;
  });
  $(this).on("keypress", function (e) {
    idleTime = 0;
  });
  //Zero the idle timer on touch events.
  $(this).on('touchstart', function(){
   idleTime = 0;
  });
  $(this).on('touchmove', function(){
   idleTime = 0;
  });
});

function timerIncrement() {
  idleTime = idleTime + 1;
  if (idleTime > 1) { 
    window.location.href = "../";
  }
}