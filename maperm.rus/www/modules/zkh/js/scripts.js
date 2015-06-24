 $(document).click(function(e) {
    if (!$(e.target).is(".address2")) {
         $('#address2').addClass('hidden');
    }
   });
   
   $('input[name="address"]').keyup(function() {
    var search = $('input[name="address"]').val();
    if (search != '') {
        $.post("/index.php", {search: search, action: "ajax", act: "fast", module: "main"},
        	function (html) {
        	  if (html!='') {
        	   $('#address2').removeClass('hidden').html(html);
        	  }
              
              else {
                $('#address2').addClass('hidden');
              }
		});
    }
    
    else {
        $('#address2').addClass('hidden');
    }
    });
   
    $('input[name="search"]').click(function() {
    var error = false
    var search = $('input[name="address"]').val();
    if (search == '') {$('input[name="search"]').css('border','1px solid #ff0000'); error = true;}
    else {$('input[name="search"]').css('border','1px solid #abadb3');}
    
    if (error == true) {return false;}
    $('#search_result').html("<h1>Поиск...</h1>");
    
        $.post("/index.php", {search: search, action: "ajax", act: "search", module: "main"},
        	function (html) {
        	  $('#search_result').html(html);
         });
    return false;
    });
  
 $('body').on('click', '.address2', function() {
      var t = $(this).text();
      $('input[name="address"]').val(t);
      $('#address2').addClass('hidden');
  });