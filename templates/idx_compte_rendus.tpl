<!-- $Id$ -->

{literal}
<script language="javascript">
function popPat() {
  var url = './index.php?m=dPpatients';
  url += '&a=pat_selector';
  url += '&dialog=1';
  popup(500, 500, url, 'Patient');
}

function setPat( key, val ) {
  var f = document.patFrm;

  if (val != '') {
    f.patSel.value = key;
    f.patNom.value = val;
    window.patSel = key;
    window.patName = val;
  }
  
  f.submit();
}
</script>
{/literal}

<table class="main">
  <tr><td class="halfPane">
    <form name="patFrm" action="index.php" method="get">
    <table class="form">
      <tr><th>Choix du patient :</th>
        <td class="readonly">
          <input type="hidden" name="m" value="{$m}" />
          <input type="hidden" name="patSel" value="{$patSel->patient_id}" />
          <input type="text" readonly="readonly" name="patNom" value="{$patSel->nom} {$patSel->prenom}" />
        </td>
        <td class="button">
          <input type="button" value="chercher" onclick="popPat()" />
        </td>
      </tr>
    </table>
    {if $patSel->patient_id}
    </form>
    <table class="form" bgcolor="#eee">
      <tr><th class="category" colspan="4">Informations sur le patient</th></tr>
      <tr><th>Nom :</th><td>{$patSel->nom}</td>
        <th>Tel :</th><td>{$patSel->tel}</td></tr>
      <tr><th>Prenom :</th><td>{$patSel->prenom}</td>
        <th>Mobile :</th><td>{$patSel->tel2}</td></tr>
      <tr><th>Age :</th><td>{$patSel->_age} ans</td>
        <th>Adresse :</th><td class="text">{$patSel->adresse}, {$patSel->cp} - {$patSel->ville}</td></tr>
      <tr><th class="category" colspan="4">Consultations</th></tr>
      {foreach from=$patSel->_ref_consultations item=curr_consult}
      <tr><td colspan="4"><strong>Dr. {$curr_consult->_ref_plageconsult->_ref_chir->user_last_name}
        {$curr_consult->_ref_plageconsult->_ref_chir->user_first_name}
        &mdash; {$curr_consult->_ref_plageconsult->date|date_format:"%A %d %B %Y"}
      </strong></td></tr>
      <tr><th>Motif :</th><td class="text" colspan="3">{$curr_consult->motif}</td></tr>
      <tr><th>Compte-rendu de consultation :</th>
        <td class="button" colspan="2">modifier imprimer supprimer</td></tr>
      <tr><th><i>Fichiers associés :</i></th></tr>
      {foreach from=$curr_consult->_ref_files item=curr_file}
      <tr>
        <th><a href="mbfileviewer.php?file_id={$curr_file->file_id}">{$curr_file->file_name}</a></th>
        <td>{$curr_file->_file_size}</td>
        <td class="button">
          <form name="uploadFrm{$curr_file->file_id}" action="?m=dPcabinet" enctype="multipart/form-data" method="post">
          <input type="hidden" name="dosql" value="do_file_aed" />
	      <input type="hidden" name="del" value="1" />
	      <input type="hidden" name="file_id" value="{$curr_file->file_id}" />
	      <input type="button" value="supprimer" onclick="{literal}if (confirm('Veuillez confirmer la suppression')) {this.form.submit();}{/literal}"/>
          </form>
        </td>
	  </tr>
      {/foreach}
      <tr>
        <th colspan="2">
          <form name="uploadFrm" action="?m=dPcabinet" enctype="multipart/form-data" method="post">
          <input type="hidden" name="dosql" value="do_file_aed" />
          <input type="hidden" name="del" value="0" />
	      <input type="hidden" name="file_consultation" value="{$consult->consultation_id}" />
          <input type="file" name="formfile" />
        </th>
        <td class="button">
          <input type="submit" value="ajouter">
          </form>
        </td>
      </tr>
      {/foreach}
      <tr><th class="category" colspan="4">Interventions</th></tr>
      {foreach from=$patSel->_ref_operations item=curr_op}
      <tr><td colspan="4"><strong>Dr. {$curr_op->_ref_chir->user_last_name}
        {$curr_op->_ref_chir->user_first_name}
        &mdash; {$curr_op->_ref_plageop->date|date_format:"%A %d %B %Y"}
      </strong></td></tr>
      <tr><th>{$curr_op->_ext_code_ccam->code} :</th>
        <td colspan="3" class="text">{$curr_op->_ext_code_ccam->libelleLong}</td></tr>
      <tr><th>Compte-rendu opératoire :</th>
        <td class="button" colspan="2">modifier imprimer supprimer</td></tr>
      <tr><th>Compte-rendu d'anesthésie :</th>
        <td class="button" colspan="2">modifier imprimer supprimer</td></tr>
      <tr><th><i>Fichiers associés :</i></th></tr>
      {foreach from=$curr_op->_ref_files item=curr_file}
      <tr>
        <th><a href="mbfileviewer.php?file_id={$curr_file->file_id}">{$curr_file->file_name}</a></th>
        <td>{$curr_file->_file_size}</td>
        <td class="button">
          <form name="uploadFrm{$curr_file->file_id}" action="?m=dPcabinet" enctype="multipart/form-data" method="post">
          <input type="hidden" name="dosql" value="do_file_aed" />
	      <input type="hidden" name="del" value="1" />
	      <input type="hidden" name="file_id" value="{$curr_file->file_id}" />
	      <input type="button" value="supprimer" onclick="{literal}if (confirm('Veuillez confirmer la suppression')) {this.form.submit();}{/literal}"/>
          </form>
        </td>
	  </tr>
      {/foreach}
      <tr>
        <th colspan="2">
          <form name="uploadFrm" action="?m=dPcabinet" enctype="multipart/form-data" method="post">
          <input type="hidden" name="dosql" value="do_file_aed" />
          <input type="hidden" name="del" value="0" />
	      <input type="hidden" name="file_operation" value="{$curr_op->operation_id}" />
          <input type="file" name="formfile" />
        </th>
        <td class="button">
          <input type="submit" value="ajouter">
          </form>
        </td>
      </tr>
      {/foreach}
    </table>
    {/if}
  </td>
  <td class="halfPane">
  <form name="chirFrm" action="index.php" method="get">
    <table class="form">
      <tr>
        <td><strong>Compte-rendu(s) à taper : {$total}</strong></td>
        <th>
          Choix du chirurgien :
          <input type="hidden" name="m" value="{$m}" />
        </th>
        <td>
          <select name="chirSel" onchange="submit()">
            <option value="0">&mdash; Tous &mdash;</option>
            {foreach from=$listPrat item=curr_prat}
            <option value="{$curr_prat->user_id}" {if $chirSel == $curr_prat->user_id}selected="selected"{/if}>
              {$curr_prat->user_last_name} {$curr_prat->user_first_name}
            </option>
            {/foreach}
          </select>
        </td>
      </tr>
    </table>
    <table class="tbl">
      {foreach from=$listPlage item=curr_plage}
      <tr>
        <th colspan="6">Dr. {$curr_plage->_ref_chir->user_first_name} {$curr_plage->_ref_chir->user_last_name} le {$curr_plage->date|date_format:"%a %d %b %Y"} : {$curr_plage->total} compte-rendu(s)</th>
      </tr>
      {foreach from=$curr_plage->_ref_consultations item=curr_consult}
      <tr>
        <td>{$curr_consult->_ref_patient->nom}</td>
        <td>{$curr_consult->_ref_patient->prenom}</td>
        <td>Modifier</td>
        <td>Valider</td>
        <td>Imprimer</td>
        <td>Supprimer</td>
      </tr>
      {/foreach}
      {/foreach}
    </table>
  </form>
  </td></tr>
</table>

