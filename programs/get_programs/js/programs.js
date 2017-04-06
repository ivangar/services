
$(document).ready(function () {

    var password = $( "#pwd" );
    var tips = $( ".validateTips" );
    
    function updateTips( t ) {
      tips
        .text( t )
        .addClass( "ui-state-error" );
    }

    function processPwd() {

        var form = document.getElementById("pwd-access");
        var jsonData = {};

        for (i = 0; i < form.length ;i++) { 
            var columnName = form.elements[i].name;
            jsonData[columnName] = form.elements[i].value;
        } 

        console.log(jsonData);

        $.ajax({
            url: "lib/process_pwd.php",
            cache: false,
            type: "POST",
            dataType: "html",
            data: jsonData
        }) 
        .done(function( data ) {

              if(data === 'access'){
                 document.location.reload(true);
              }

              else{
                 password.addClass( "ui-state-error" );
                 updateTips(data);
              }
        });

    }

    $( "#password-dialog-form" ).dialog({
         autoOpen: false,
         height: 350,
         width: 500,
         modal: true,
         buttons: {
           "Submit": function() {
              processPwd();
           },
           "Cancel": function() {
             tips.removeClass( "ui-state-error" );
             tips.empty();
             password.removeClass( "ui-state-error" );
             password.val("");
           }
         },
          close: function() {
             document.location.reload(true);
          }
    });

    /* Disable enter key when submit the form */
    $(window).keydown(function(event){
      if(event.keyCode == 13) {
        event.preventDefault();
        return false;
      }
    });


    /*  
      $('body').on('click', 'a.btn-default', function(event) {
          event.preventDefault();
          image = $(this).attr("id");
          console.log(image);

         $.fileDownload('http://www.dxlink.ca/services/programs/get_programs/image/' + image + '.jpg');

          //console.log(image);
            $.ajax({
              url: "lib/accounts.php?action=exportUsers",
              cache: false,
              type: "POST",
              dataType: "html"
            }) 
          .done(function( data ) {
              if (data === "exported"){
                 $.fileDownload('https://dxlink.ca/admin/Reports/dxLink_Users.xlsx');
               }
          });
        

      });
*/

  });//end document.ready
