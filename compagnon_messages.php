<?php


function compagnon_compagnon_messages($flux) {

	$exec = $flux['args']['exec'];
	$pipeline = $flux['args']['pipeline'];
	$aides = &$flux['data'];

	switch ($pipeline) {
		
		case 'affiche_milieu':
			switch ($exec) {

				
				case 'accueil':
					$aides[] = array(
						'id' => 'accueil',
						'inclure' => 'compagnon/accueil',
						'statuts'=> array('1comite', '0minirezo', 'webmestre')
					);
					$aides[] = array(
						'id' => 'accueil_configurer',
						'titre' => _T('compagnon:c_accueil_configurer_site'),
						'texte' => _T('compagnon:c_accueil_configurer_site_texte', array('nom'=>$GLOBALS['meta']['nom_site'])),
						'statuts'=> array('webmestre')
					);
					$aides[] = array(
						'id' => 'accueil_publication',
						'titre' => _T('compagnon:c_accueil_publication'),
						'texte' => _T('compagnon:c_accueil_publication_texte'),
						'statuts'=> array('webmestre')
					);			
					break;

					
				case 'rubriques':
					$aides[] = array(
						'id' => 'rubriques',
						'titre' => _T('compagnon:c_rubriques_creer'),
						'texte' => _T('compagnon:c_rubriques_creer_texte'),
						'statuts'=> array('webmestre')
					);						
					break;

					
				case 'rubrique':
					$aides[] = array(
						'id' => 'rubrique',
						'titre' => _T('compagnon:c_rubrique_publier'),
						'texte' => _T('compagnon:c_rubrique_publier_texte'),
						'statuts'=> array('webmestre')
					);						
					break;

					
				case 'article':
					$aides[] = array(
						'id' => 'article_redaction',
						'inclure' => 'compagnon/article_redaction',
						'statuts'=> array('0minirezo', 'webmestre')
					);	
					$aides[] = array(
						'id' => 'article_redaction_redacteur',
						'inclure' => 'compagnon/article_redaction_redacteur',
						'statuts'=> array('1comite')
					);	
					break;
			}
			break;
	}

	
	return $flux;
}

?>
