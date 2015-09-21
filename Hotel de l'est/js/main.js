jQuery(function($){
	$('#datepicker').datepicker({
altField: "#datepicker",
closeText: 'Fermer',
prevText: 'Précédent',
nextText: 'Suivant',
currentText: 'Aujourd\'hui',
monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
dayNames: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi','Dimanche'],
dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
dayNamesMin: ['L', 'M', 'M', 'J', 'V', 'S','D'],
weekHeader: 'Sem.',

		dateFormat : 'dd/mm/yy',
		minDate : 0,
	});


$(document).ready(function() {
     
    $("#owl-demo").owlCarousel({
     
    navigation : false, // Show next and prev buttons
    slideSpeed : 300,
    paginationSpeed : 300,
    singleItem:true
     
    // "singleItem:true" is a shortcut for:
    // items : 1,
    // itemsDesktop : false,
    // itemsDesktopSmall : false,
    // itemsTablet: false,
    // itemsMobile : false
     
    });
     
    });

$(document).ready(function() {
     
    $("#owl-demo-seminaire").owlCarousel({
     
    navigation : false, // Show next and prev buttons
    slideSpeed : 300,
    paginationSpeed : 300,
    singleItem:true
     
    // "singleItem:true" is a shortcut for:
    // items : 1,
    // itemsDesktop : false,
    // itemsDesktopSmall : false,
    // itemsTablet: false,
    // itemsMobile : false
     
    });
     
    });

})


