<table class="form" id="admission">
  <tr><th class="title" colspan="2"><a href="javascript:window.print()">Fiche Patient</a></th></tr>
  
  <tr><th>Date: </th><td>{$today}</td></tr>
  
  <tr><th class="category" colspan="2">Informations sur le patient</th></tr>
  
  <tr><th>Nom / Prenom: </th><td>{$patient->nom} {$patient->prenom}</td></tr>
  <tr><th>Date de naissance / Sexe: </th><td>né(e) le {$patient->_jour}/{$patient->_mois}/{$patient->_annee} de sexe {$patient->sexe}</td></tr>
  <tr><th>Incapable majeur: </th><td>{$patient->incapable_majeur}</td></tr>
  <tr><th>Telephone: </th><td>{$patient->tel}</td></tr>
  <tr><th>Portable: </th><td>{$patient->tel2}</td></tr>
  <tr><th>Adresse: </th><td>{$patient->adresse} - {$patient->cp} {$patient->ville}</td></tr>
  <tr><th>Remarques: </th><td>{$patient->rques|nl2br:php}</td></tr>
  
  <tr><th class="category" colspan="2">Medecins correspondants</th></tr>
  
  <tr><th>Medecin traitant: </th><td>{$patient->_ref_medecin_traitant->nom} {$patient->_ref_medecin_traitant->prenom}</td></tr>
  <tr><th></th><td>{$patient->_ref_medecin_traitant->adresse} - {$patient->_ref_medecin_traitant->cp} {$patient->_ref_medecin_traitant->ville}</td></tr>
  {if $patient->_ref_medecin1}
  <tr><th>Medecin correspondant 1: </th><td>{$patient->_ref_medecin1->nom} {$patient->_ref_medecin1->prenom}</td></tr>
  <tr><th></th><td>{$patient->_ref_medecin1->adresse} - {$patient->_ref_medecin1->cp} {$patient->_ref_medecin1->ville}</td></tr>
  {/if}
  {if $patient->_ref_medecin2}
  <tr><th>Medecin correspondant 2: </th><td>{$patient->_ref_medecin2->nom} {$patient->_ref_medecin2->prenom}</td></tr>
  <tr><th></th><td>{$patient->_ref_medecin2->adresse} - {$patient->_ref_medecin2->cp} {$patient->_ref_medecin2->ville}</td></tr>
  {/if}
  {if $patient->_ref_medecin3}
  <tr><th>Medecin correspondant 3: </th><td>{$patient->_ref_medecin3->nom} {$patient->_ref_medecin3->prenom}</td></tr>
  <tr><th></th><td>{$patient->_ref_medecin3->adresse} - {$patient->_ref_medecin3->cp} {$patient->_ref_medecin3->ville}</td></tr>
  {/if}
  
  <tr><th class="category" colspan="2">Antécédants chirurgicaux</th></tr>
  
  {foreach from=$patient->_ref_operations item=curr_op}
  <tr>
    <th>Dr. {$curr_op->_ref_chir->user_last_name} {$curr_op->_ref_chir->user_first_name}</th>
    <td>le {$curr_op->_ref_plageop->_date}</td>
  </tr>
  {/foreach}

</table>