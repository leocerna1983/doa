<!doctype html>
<html lang = "en">
   <head>
      <meta charset = "utf-8">
      <title>jQuery UI Autocomplete functionality</title>
      <link href = "https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.min.css"
         rel = "stylesheet">
      <script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
      <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
      
      <!-- Javascript -->
      <script>
         $(function() {
            var availableTutorials  =  [
               "ActionScript",
               "Bootstrap",
               "C",
               "C++",
            ];

                function log( message ) {
                  $( "<div>" ).text( message ).prependTo( "#log" );
                  $( "#log" ).scrollTop( 0 );
                }
            $( "#term" ).autocomplete({
               source: function( request, response ) {
                    $.ajax( {
                      type: "POST",
                      url: "selecttareas.php",
                      dataType: "json",
                      data: {
                        term: request.term
                      },
                      success: function(data) {                      
                         response(data);                        
                     },
                     error: function() {
                          console.log("No se ha podido obtener la información");
                      }
                    } );
                  },
            select: function( event, ui ) {
                 log( "Selected: " + ui.item.value + " aka " + ui.item.id );
                 //$("#term").val(ui.item.label);
                 //return false;
               }
            });
         });
      </script>
   </head>
   
   <body>
      <!-- HTML --> 
      <div class = "ui-widget">
         <p>Type "a" or "s"</p>
         <label for = "term">Tags: </label>
         <input id = "term">
      </div>
   </body>
</html>