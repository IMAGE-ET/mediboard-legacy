<table class="form" id="admission">
  <tr><th colspan="2">Dr. {$admission->_ref_chir->_view}</th></tr>
  <tr><th colspan="2">Centre Médico Chirurgical de l'Atlantique</th></tr>
  <tr><th colspan="2">26 rue Moulin des justices</th></tr>
  <tr><th colspan="2">17138 - Puilboreau</th></tr>
  <tr><th colspan="2">Tel : 05 46 00 40 40</th></tr>
  <tr><th class="title" colspan="2"><a href="javascript:window.print()">Supplément d'honoraire</a></th></tr>

  <tr><th>Nom / Prenom du patient: </th><td>{$admission->_ref_pat->_view}</td></tr>

  <tr><th>Date d'intervention: </th><td>{$admission->_ref_plageop->_day}/{$admission->_ref_plageop->_month}/{$admission->_ref_plageop->_year}</td></tr>
  <tr><th>Acte CCAM: </th><td class="text">{$admission->_ext_code_ccam->libelleLong} <i>({$admission->CCAM_code})</i></td></tr>
  {if $admission->CCAM_code2}
  <tr><th>Acte CCAM secondaire: </th><td class="text">{$admission->_ext_code_ccam2->libelleLong} <i>({$admission->CCAM_code2})</i></td></tr>
  {/if}
  <tr><th>Coté: </th><td>{$admission->cote}</td></tr>
  <tr><th>Dépassement d'honoraires: </th><td>{$admission->depassement} €</td></tr>

  <tr><th>Signature du patient</th></tr>

</table>