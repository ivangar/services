
$(document).ready(function () {

      //Create Excel Forum Report file and export asynchronously
      $('body').on('click', 'a.btn-default', function(event) {
          event.preventDefault();
          image = $(this).attr("id");

          $.fileDownload('http://www.dxlink.ca/services/programs/get_programs/image/' + image + '.jpg');
          //$.fileDownload('http://www.dxlink.ca/services/programs/get_programs/image/demo.xlsx');

          //console.log(image);
          /*$.ajax({
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
        */

      });


  });//end document.ready
