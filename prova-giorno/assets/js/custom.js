$(document).ready(function(){
	// Calcola la posizione in altezza dell'elemento. Usa outerHeight() se hai del padding https://api.jquery.com/outerHeight/ o offset().top. 
	//Altrimenti usa anche height() https://api.jquery.com/height/
	//#nav è il selettore del menù di navigazione
	var navHeight = $('#nav').outerHeight();
	// Entra quando si scrolla la pagina
	$(window).scroll(function(){
		// Se scorre la pagina oltre il valore salvato in navHeight (quindi la posizione della nav Bar), esegue altre istruzioni
		if ($(window).scrollTop() > navHeight){
			// Aggiunge la classe .fixed al menù, così da renderlo fisso nella parte superiore dello schermo (impostato tramite style.css)
			$('#nav').addClass('fixed');
			// aggiungo il padding al contenuto principale, altrimenti l'inizio non si vedrebbe a causa della sovrapposizione della barra di navigazione
			$('#content').css('padding-top', navHeight+'px'); 
		}else{
			// se torna sopra il valore di navHeight, rimuove la classe .fixed, e la barra ritorna alla sua posizione originale
			$('#nav').removeClass('fixed');
			// rimuovo il padding
			$('#content').css('padding-top', '0'); 
		}
	});
});