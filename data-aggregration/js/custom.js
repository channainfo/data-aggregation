function show_loading(message, container){
       if(typeof container == "undefined" || !container )
           container = document.body
       var $containner = $(container);

       var $loading = $("#ajax_loading")
       if(typeof message == "undefined" || !message)
           $loading.html("Waiting for server response");
       else
           $loading.html(message);
       
       var pos =  $containner.offset();

       var loading_width = $loading.width();
       var loading_height= $loading.height();

       var width = $containner.width();
       var height = $containner.height();

       var top = pos.top + (height/2) -(loading_height/2) + "px" ;
       var left = pos.left + (width/2) -(loading_width/2) + "px" ;
       $("#ajax_loading").css("left",left);
       $("#ajax_loading").css("top",top);
       $("#ajax_loading").css("position","absolute");
       
       $("#ajax_loading").show();
       return $("#ajax_loading");
}
function hide_loading(){
  $("#ajax_loading").hide();
}

function notificationId(){
  return "fade-ajax" ;
}

function createNotification( message, container ) {
  if(typeof container == "undefined" || !container )
      container = document.body
  var $container = $(container);
  var $element = $("<div class='fade hide' id='" + notificationId() +"' >" + message  + " </div>");
  $element.prependTo($container);
  return $element;
}
function fadeNotification(message, container, time){
  if(typeof timer == "undefined" || !timer )
     timer = 5000;
  $element = createNotification(message, container);
  $element.fadeIn(1000, function(){
    
  }).fadeOut(timer, function(elm){
    $(elm).remove();
  });
}







