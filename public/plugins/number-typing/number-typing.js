/*
	Plugin Jquery : Number Typing

	Ce plugin permet de formater automatiquement
	un nombre tapé en direct par l'utilisateur

	Author: Oriane DEVAUX
	version 1.0
*/

(function ($) {

	$.fn.numberTyping=function(options)
	{
		var defauts={"separator" : " "};

       	var parametres=$.extend(defauts, options);

       	// Keys "enum"
		var KEY = {
		    TAB: 9,
		    LEFT: 37,
		    RIGHT: 39,
		    COMMA: 188
		};
		$(this).keyup(function (event) {
			if(event.keyCode!=KEY.TAB && event.keyCode!=KEY.LEFT && event.keyCode!=KEY.RIGHT ){
				var new_val,number_wt_spaces;
				var val=$(this).val(); //On récupère la chaine à chaque fois qu'une touche est relâchée (un ou plusieurs caractères tapés)
				number_pure = val.replace(/\D/g,"");
				number_wt_spaces=number_pure.replace(/\s/g,""); //On enlève les espaces pour avoir uniquement le nombre
				new_val=numberFormat(number_wt_spaces,parametres.separator); //On formate la chaine en rajoutant les espaces
				$(this).val(new_val); //On réaffecte la valeur à l'input
			}
		});
	};
}(jQuery));

function numberFormat(str,separator){
	var newStr='';
	var StrFormat='';

	//On enlève l'éventuel signe négatif en premier pour ne pas gêner le formatage :
	var nb_neg=false;
	if(str.charAt(0)=="-"){
		nb_neg=true;
		str=str.substr(1);
	}

	//On parcourt le tableau de caracteres, en partant de la fin
	var nb_c=str.length;

	var cpt=0;
	var debut=nb_c-1;

	for(i=debut;i>=0;i--){
		if(cpt==3){
			//On ajoute un séparateur tous les 3 caracteres
			StrFormat+=separator+str.charAt(i);
			cpt=1;
		}
		else{
			StrFormat+=str.charAt(i);
			cpt++;
		}
	}

	debut=(StrFormat.length)-1;

	//Le nombre obtenu est encore à l'envers, il le faut le remettre à l'endroit
	for(i=debut;i>=0;i--){
		newStr+=StrFormat.charAt(i);
	}

	if(nb_neg) newStr='-'+newStr;

	return newStr;
}

