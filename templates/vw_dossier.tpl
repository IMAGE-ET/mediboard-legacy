<!-- $Id$ -->

{literal}
<script language="javascript">

function pageMain() {
  initGroups("op");
  initGroups("hospi");
}

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

function selectCRC(id, form) {
  var modele = form.modele.value;
  if(modele != 0)
    editModeleC(id, modele);
}

function editModeleC(consult, modele) {
  if(modele != -1) {
    var url = '?m=dPcabinet&a=edit_compte_rendu&dialog=1';
    url +='&consult=' + consult;
    url +='&modele=' + modele;
    popup(700, 700, url, 'Compte-rendu');
  }
}

function validerCompteRenduC(form) {
  if (confirm('Veuillez confirmer la validation du compte-rendu')) {
    form.cr_valide.value = "1";
    form.submit();
  }
}

function supprimerCompteRenduC(form) {
  if (confirm('Veuillez confirmer la suppression')) {
    form.compte_rendu.value = "";
    form.cr_valide.value = "0";
    form.submit();
  }
}

function imprimerCRC(consult) {
  var url = '?m=dPcabinet&a=print_cr&dialog=1';
  url +='&consult_id=' + consult;
  popup(700, 700, url, 'Compte-rendu');
}

function selectCRO(id, form) {
  var modele = form.modele.value;
  if(modele != 0)
    editModeleO(id, modele);
}

function editModeleO(operation, modele) {
  var url = '?m=dPplanningOp&a=edit_compte_rendu&dialog=1';
  url +='&operation=' + operation;
  url +='&modele=' + modele;
  popup(700, 700, url, 'Compte-rendu');
}

function validerCompteRenduO(form) {
  if (confirm('Veuillez confirmer la validation du compte-rendu')) {
    form.cr_valide.value = "1";
    form.submit();
  }
}

function supprimerCompteRenduO(form) {
  if (confirm('Veuillez confirmer la suppression')) {
    form.compte_rendu.value = "";
    form.cr_valide.value = "0";
    form.submit();
  }
}

function imprimerCRO(op) {
  var url = '?m=dPplanningOp&a=print_cr&dialog=1';
  url +='&operation_id=' + op;
  popup(700, 700, url, 'Compte-rendu');
}

function printPack(op, form) {
  if(form.pack.value != 0) {
    var url = '?m=dPcabinet&a=print_pack&dialog=1';
    url +='&operation_id=' + op;
    url +='&pack_id=' + form.pack.value;
    popup(700, 700, url, 'Compte-rendu');
  }
}

</script>
{/literal}

<table class="main">
  <tr>
    <td class="greedyPane">
    <form name="patFrm" action="index.php" method="get">
      <table class="form">
        <tr><th>Choix du patient :</th>
          <td class="readonly">
            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="patSel" value="{$patSel->patient_id}" />
            <input type="text" readonly="readonly" name="patNom" value="{$patSel->_view}" />
          </td>
          <td class="button">
            <input type="button" value="chercher" onclick="popPat()" />
          </td>
        </tr>
      </table>
      </form>
      {if $patSel->patient_id}
      <table class="form">
        <tr><th class="category" colspan="4">Informations sur le patient</th></tr>
        <tr>
          <th>Nom :</th><td>{$patSel->_view}</td>
          <th>Tel :</th><td>{$patSel->tel}</td>
        </tr>
        <tr>
          <th>Age :</th>
          <td>{$patSel->_age} ans</td>
          <th>Mobile :</th>
          <td>{$patSel->tel2}</td>
        </tr>
        <tr>
          <th>Adresse :</th>
          <td colspan="3" class="text">{$patSel->adresse}, {$patSel->cp} - {$patSel->ville}</td>
        </tr>
      </table>
      <table class="form">
        <tr><th class="category" colspan="4">Interventions</th></tr>
        {foreach from=$patSel->_ref_operations item=curr_op}
        <tr class="groupcollapse" id="op{$curr_op->operation_id}" onclick="flipGroup({$curr_op->operation_id}, 'op')">
          <td colspan="4">
            <strong>
            Dr. {$curr_op->_ref_chir->_view} &mdash;
            {$curr_op->_ref_plageop->date|date_format:"%A %d %B %Y"}
            </strong>
          </td>
        </tr>
        <tr class="op{$curr_op->operation_id}">
          <td class="text" colspan="4">
            {$curr_op->_ext_code_ccam->code} : {$curr_op->_ext_code_ccam->libelleLong}
          </td>
        </tr>
        {if $curr_op->CCAM_code2}
        <tr class="op{$curr_op->operation_id}">
          <td class="text" colspan="4">
            {$curr_op->_ext_code_ccam2->code} : {$curr_op->_ext_code_ccam2->libelleLong}
          </td>
        </tr>
        {/if}
        <tr class="op{$curr_op->operation_id}">
          <th colspan="2">Compte-rendu opératoire :</th>
          <td colspan="2">
            {if $curr_op->compte_rendu}
            <form name="editCROListFrm{$curr_op->operation_id}" action="?m=dPplanningOp" method="POST">
            <input type="hidden" name="m" value="dPplanningOp" />
            <input type="hidden" name="del" value="0" />
            <input type="hidden" name="dosql" value="do_planning_aed" />
            <input type="hidden" name="operation_id" value="{$curr_op->operation_id}" />
            <input type="hidden" name="compte_rendu" value="{$curr_op->compte_rendu|escape:html}" />
            <input type="hidden" name="cr_valide" value="{$curr_op->cr_valide}" />
            <button type="button" onclick="imprimerCRO({$curr_op->operation_id})"><img src="modules/dPcabinet/images/print.png" /></button>
            </form>
            {else}
            Pas de compte Rendu
            {/if}
          </td>
        </tr>
        <tr class="op{$curr_op->operation_id}">
          <th colspan="2">Compte-rendu d'anesthésie :</th>
          <td class="button" colspan="2">modifier imprimer supprimer</td>
        </tr>
        <tr class="op{$curr_op->operation_id}">
          <th colspan="2"><i>Fichiers associés :</i></th>
          <td colspan="2" />
        </tr>
        {foreach from=$curr_op->_ref_files item=curr_file}
        <tr class="op{$curr_op->operation_id}">
          <th colspan="2">
            <a href="mbfileviewer.php?file_id={$curr_file->file_id}">{$curr_file->file_name}</a>
          </th>
          <td>{$curr_file->_file_size}</td>
          <td>
            <form name="uploadFrm{$curr_file->file_id}" action="?m=dPcabinet" enctype="multipart/form-data" method="post">
            <input type="hidden" name="dosql" value="do_file_aed" />
	        <input type="hidden" name="del" value="1" />
	        <input type="hidden" name="file_id" value="{$curr_file->file_id}" />
	        </form>
          </td>
	    </tr>
        {/foreach}
        <tr class="op{$curr_op->operation_id}">
          <th colspan="3">
            <form name="uploadFrm" action="?m=dPcabinet" enctype="multipart/form-data" method="post">
            <input type="hidden" name="dosql" value="do_file_aed" />
            <input type="hidden" name="del" value="0" />
	        <input type="hidden" name="file_operation" value="{$curr_op->operation_id}" />
            <input type="file" name="formfile" />
          </th>
          <td>
            <input type="submit" value="ajouter">
            </form>
          </td>
        </tr>
        {/foreach}
        <tr>
          <th class="category" colspan="4">Hospitalisations</th>
        </tr>
        {foreach from=$patSel->_ref_hospitalisations item=curr_hospi}
        <tr class="groupcollapse" id="hospi{$curr_hospi->operation_id}" onclick="flipGroup({$curr_hospi->operation_id}, 'hospi')">
          <td colspan="4">
            <strong>
            Dr. {$curr_hospi->_ref_chir->_view} &mdash;
            {$curr_hospi->date_adm|date_format:"%A %d %B %Y"}
            </strong>
          </td>
        </tr>
        {if $curr_hospi->CCAM_code}
        <tr class="hospi{$curr_hospi->operation_id}">
          <td class="text" colspan="4">
            {$curr_hospi->_ext_code_ccam->code} : {$curr_hospi->_ext_code_ccam->libelleLong}
          </td>
        </tr>
        {else}
        <tr class="hospi{$curr_hospi->operation_id}">
          <td class="text" colspan="4">Simple observation</td>
        </tr>
        {/if}
        {if $curr_hospi->CCAM_code2}
        <tr class="hospi{$curr_hospi->operation_id}">
          <td class="text" colspan="4">
            {$curr_hospi->_ext_code_ccam2->code} : {$curr_hospi->_ext_code_ccam2->libelleLong}
          </td>
        </tr>
        {/if}
        <tr class="hospi{$curr_hospi->operation_id}">
          <th colspan="2">Compte-rendu d'anesthésie :</th>
          <td class="button" colspan="2">modifier imprimer supprimer</td>
        </tr>
        <tr class="hospi{$curr_hospi->operation_id}">
          <th colspan="2"><i>Fichiers associés :</i></th>
          <td colspan="2" />
        </tr>
        {foreach from=$curr_hospi->_ref_files item=curr_file}
        <tr class="hospi{$curr_hospi->operation_id}">
          <th colspan="2"><a href="mbfileviewer.php?file_id={$curr_file->file_id}">{$curr_file->file_name}</a></th>
          <td>{$curr_file->_file_size}</td>
          <td>
            <form name="uploadFrm{$curr_file->file_id}" action="?m=dPcabinet" enctype="multipart/form-data" method="post">
            <input type="hidden" name="dosql" value="do_file_aed" />
	        <input type="hidden" name="del" value="1" />
	        <input type="hidden" name="file_id" value="{$curr_file->file_id}" />
	        </form>
          </td>
	    </tr>
        {/foreach}
        {/foreach}
       </table>
      {/if}
    </td>
  </tr>
</table>

