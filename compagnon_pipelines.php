<?php

if (!defined('_ECRIRE_INC_VERSION')) return;



function compagnon_affiche_milieu($flux) {
	return compagnonage($flux, 'affiche_milieu');
}
function compagnon_affiche_gauche($flux) {
	return compagnonage($flux, 'affiche_gauche');
}




/**
 *  
 *
 * @param array $flux
 * 		Flux d'informations transmises au pipeline
 * @param string $pipeline
 * 		Nom du pipeline d'origine
 * @return array $flux
 * 		Le flux éventuellement complété de l'aide du compagnon
**/
function compagnonage($flux, $pipeline) {

	// pas de compagnon souhaite ?
	include_spip('inc/config');
	#if (lire_config("compagnon/config/activer") == 'non') {
	#	return $flux;
	#}
	
	$flux['args']['pipeline'] = $pipeline;
	$aides = pipeline('compagnon_messages', array('args'=>$flux['args'], 'data'=>array()));
	
	if (!$aides) {
		return $flux;
	}
	
	
	$moi = $GLOBALS['visiteur_session'];
	$deja_vus = lire_config("compagnon/".$moi['id_auteur']);
	$ajouts = "";
	
	foreach ($aides as $aide) {
		// restreindre l'affichage par statut d'auteur
		$ok = true;
		if (isset($aide['statuts']) and $statuts = $aide['statuts']) {
			$ok = false;
			if (!is_array($statuts)) {
				$statuts = array($statuts);
			}
			if (in_array('webmestre', $statuts) and ($moi['webmestre'] == 'oui')) {
				$ok = true;
			} elseif (in_array($moi['statut'], $statuts)) {
				$ok = true;
			}			
		}

		// si c'est ok, mais que l'auteur a deja lu ca. On s'arrete.
		if ($ok and is_array($deja_vus) and $deja_vus[$aide['id']]) {
			$ok = false;
		}

		if ($ok) {
			// demande d'un squelette
			if (isset($aide['inclure']) and $inclure = $aide['inclure']) {
				unset($aide['inclure']);
				$ajout = recuperer_fond($inclure, array_merge($flux['args'], $aide), array('ajax'=>true));
			}
			// sinon les textes sont fournis
			else {
				$ajout = recuperer_fond('compagnon/_boite', $aide, array('ajax'=>true));
			}

			$ajouts .= $ajout;
		}
	}

	// ajout de nos trouvailles
	if ($ajouts) {
		$twinkle = find_in_path('prive/javascript/jquery.twinkle.js');
		$ajouts.=<<<JS
<script type="text/javascript">
jQuery.getScript('$twinkle',function(){
	jQuery(function(){
		var options = {
			"gap": 300,
			"effectOptions": {
				"color": "rgba(255,223,96,0.5)"
			}
		};
		jQuery('.compagnon').each(function(){
			if (jQuery('.target-highlight',this).length){
			  var target = jQuery('.target-highlight',this).attr('data-target');
			  jQuery(this).mouseover(function(){jQuery(target).twinkle(options);});
			}
		});
	});
});
</script>
JS;

		$flux['data'] = $ajouts . $flux['data'];
	}
	
	return $flux;
}




?>
