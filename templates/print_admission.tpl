<table class="form" id="admission">
  <tr><th class="title" colspan="2"><a href="javascript:window.print()">Récapitulatif admission</a></th></tr>

  <tr><th>Chirurgien: </th><td>Dr. {$admission->_ref_chir->user_last_name} {$admission->_ref_chir->user_first_name}</td></tr>
  
  <tr><th class="category" colspan="2">Informations sur le patient</th></tr>
  
  <tr><th>Nom / Prenom: </th><td>{$admission->_ref_pat->nom} {$admission->_ref_pat->prenom}</td></tr>
  <tr><th>Date de naissance / Sexe: </th><td>né(e) le {$admission->_ref_pat->_jour}/{$admission->_ref_pat->_mois}/{$admission->_ref_pat->_annee} de sexe {$admission->_ref_pat->sexe}</td></tr>
  <tr><th>Incapable majeur: </th><td>{$admission->_ref_pat->incapable_majeur}</td></tr>
  <tr><th>Telephone: </th><td>{$admission->_ref_pat->tel}</td></tr>
  <tr><th>Portable: </th><td>{$admission->_ref_pat->tel2}</td></tr>
  <tr><th>Adresse: </th><td>{$admission->_ref_pat->adresse} - {$admission->_ref_pat->cp} {$admission->_ref_pat->ville}</td></tr>
  <tr><th>Numero d'assuré social: </th><td>{$admission->_ref_pat->matricule}</td>
  <tr><th>Remarques: </th><td>{$admission->_ref_pat->rques|nl2br:php}</td></tr>

  {if $admission->_ref_pat->_ref_medecin_traitant}
  <tr><th>Medecin traitant: </th><td>{$admission->_ref_pat->_ref_medecin_traitant->nom} {$admission->_ref_pat->_ref_medecin_traitant->prenom}</td></tr>
  <tr><th></th><td>{$admission->_ref_pat->_ref_medecin_traitant->adresse} - {$admission->_ref_pat->_ref_medecin_traitant->cp} {$admission->_ref_pat->_ref_medecin_traitant->ville}</td></tr>
  {/if}
  {if $admission->_ref_pat->_ref_medecin1}
  <tr><th>Medecin correspondant 1: </th><td>{$admission->_ref_pat->_ref_medecin1->nom} {$admission->_ref_pat->_ref_medecin1->prenom}</td></tr>
  <tr><th></th><td>{$admission->_ref_pat->_ref_medecin1->adresse} - {$admission->_ref_pat->_ref_medecin1->cp} {$admission->_ref_pat->_ref_medecin1->ville}</td></tr>
  {/if}
  {if $admission->_ref_pat->_ref_medecin2}
  <tr><th>Medecin correspondant 2: </th><td>{$admission->_ref_pat->_ref_medecin2->nom} {$admission->_ref_pat->_ref_medecin2->prenom}</td></tr>
  <tr><th></th><td>{$admission->_ref_pat->_ref_medecin2->adresse} - {$admission->_ref_pat->_ref_medecin2->cp} {$admission->_ref_pat->_ref_medecin2->ville}</td></tr>
  {/if}
  {if $admission->_ref_pat->_ref_medecin3}
  <tr><th>Medecin correspondant 3: </th><td>{$admission->_ref_pat->_ref_medecin3->nom} {$admission->_ref_pat->_ref_medecin3->prenom}</td></tr>
  <tr><th></th><td>{$admission->_ref_pat->_ref_medecin3->adresse} - {$admission->_ref_pat->_ref_medecin3->cp} {$admission->_ref_pat->_ref_medecin3->ville}</td></tr>
  {/if}
  
  <tr><th class="category" colspan="2">Informations sur l'admission</th></tr>
  
  <tr><th>Date d'admission: </th><td>{$admission->date_adm|date_format:"%d/%m/%Y"} à {$admission->_hour_adm}h{$admission->_min_adm}</td></tr>
  <tr><th>Durée d'hospitalisation: </th><td>{$admission->duree_hospi} jours</td></tr>
  <tr><th>Bilan pré-opératoire: </th><td class="text">{$admission->examen}</td></tr>
  <tr><th>Admission en: </th><td>{$admission->type_adm}</td></tr>
  <tr><th>Chambre particulière: </th><td>{$admission->chambre}</td></tr>
  
  <tr><th class="category" colspan="2">Informations sur l'intervention</th></tr>

  <tr><th>Date d'intervention: </th><td>{$admission->_ref_plageop->_day}/{$admission->_ref_plageop->_month}/{$admission->_ref_plageop->_year}</td></tr>
  <tr><th>Acte CCAM: </th><td class="text">{$admission->_ext_code_ccam->libelleLong} <i>({$admission->CCAM_code})</i></td></tr>
  <tr><th>Coté: </th><td>{$admission->cote}</td></tr>
  {if $admission->depassement}
  <tr><th>Dépassement d'honoraires: </th><td>{$admission->depassement} €</td></tr>
  {/if}

</table>