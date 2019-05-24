//Equipe POP_UP\\

//PopUp supprimer equipe
$('#confirm-delete').on('show.bs.modal', function (e) {
    $(this).find('.btn-supp').attr('href', $(e.relatedTarget).data('href')); //récupère le data-href pour le récupèrer dans le modal
});


//PopUp rejoindre une équipe
$('#confirm-join').on('show.bs.modal', function (e) {
    $(this).find('.btn-join').attr('href', $(e.relatedTarget).data('href')); //récupère le data-href pour le récupèrer dans le modal
});

//Recherche tableau js
$(document).ready(function() {
    $(".search").keyup(function () {
      var searchTerm = $(".search").val();
      var listItem = $('.results tbody').children('tr');
      var searchSplit = searchTerm.replace(/ /g, "'):containsi('")
      
    $.extend($.expr[':'], {'containsi': function(elem, i, match, array){
          return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
      }
    });
      
    $(".results tbody tr").not(":containsi('" + searchSplit + "')").each(function(e){
      $(this).attr('visible','false');
    });
  
    $(".results tbody tr:containsi('" + searchSplit + "')").each(function(e){
      $(this).attr('visible','true');
    });
  
    var jobCount = $('.results tbody tr[visible="true"]').length;
      $('.counter').text(jobCount + ' résultats');
  
    if(jobCount == '0') {$('.no-result').show();}
      else {$('.no-result').hide();}
            });
  });



 

