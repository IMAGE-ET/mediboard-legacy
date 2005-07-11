<!-- $Id$ -->

{literal}
<script language="javascript">

function pageMain() {
  initGroups("consult");
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
        <tr><th class="category" colspan="4">Consultations</th></tr>
        {foreach from=$patSel->_ref_consultations item=curr_consult}
        <tr class="groupcollapse" id="consult{$curr_consult->consultation_id}" onclick="flipGroup({$curr_consult->consultation_id}, 'consult')">
          <td colspan="4">
            <strong>
            Dr. {$curr_consult->_ref_plageconsult->_ref_chir->_view} &mdash;
            {$curr_consult->_ref_plageconsult->date|date_format:"%A %d %B %Y"}
            </strong>
          </td>
        </tr>
        <tr class="consult{$curr_consult->consultation_id}">
          <th colspan="2">Motif :</th>
          <td class="text" colspan="2">{$curr_consult->motif}</td>
        </tr>
        {foreach from=$curr_consult->_ref_documents item=document}
        <tr class="consult{$curr_consult->consultation_id}">
          <th colspan="2">{$document->nom}</th>
          {if $document->source}
          <td colspan="2" class="button">
            <input type="hidden" name="{$document->_consult_prop_name}" value="{$document->source|escape:html}" />
            <input type="hidden" name="{$document->_consult_valid_name}" value="{$document->valide}" />
            <button onclick="editDocument({$consult->consultation_id}, 0, '{$document->_consult_prop_name}', '{$document->_consult_valid_name}')">
              <img src="modules/dPcabinet/images/edit.png" /> 
            </button>
                          
            {if !$document->valide}
            <button onclick="validerDocument('{$document->_consult_prop_name}', '{$document->_consult_valid_name}')">
              <img src="modules/dPcabinet/images/check.png" /> 
            </button>
            {/if}
                          
            <button onclick="supprimerDocument('{$document->_consult_prop_name}', '{$document->_consult_valid_name}')">
              <img src="modules/dPcabinet/images/trash.png" /> 
            </button>
          </td>
          {else}
          <td colspan="2">
            <select name="_choix_modele" onchange="if (this.value) editDocument({$curr_consult->consultation_id}, this.value, '{$document->_consult_prop_name}', '{$document->_consult_valid_name}')">
              <option value="">&mdash; Choisir un mod�le</option>
              <optgroup label="Mod�les du praticien">
                {foreach from=$listModelePrat item=curr_modele}
                <option value="{$curr_modele->compte_rendu_id}">{$curr_modele->nom}</option>
                {/foreach}
              </optgroup>
              <optgroup label="Mod�les du cabinet">
                {foreach from=$listModeleFunc item=curr_modele}
                <option value="{$curr_modele->compte_rendu_id}">{$curr_modele->nom}</option>
                {/foreach}
              </optgroup>
            </select>
          </td>
          {/if}
        </tr>
        {/foreach}
        <tr class="consult{$curr_consult->consultation_id}">
          <th colspan="2"><i>Fichiers associ�s :</i></th>
          <td colspan="2" />
        </tr>
        {foreach from=$curr_consult->_ref_files item=curr_file}
        <tr class="consult{$curr_consult->consultation_id}">
          <th colspan="2"><a href="mbfileviewer.php?file_id={$curr_file->file_id}">{$curr_file->file_name}</a></th>
          <td>{$curr_file->_file_size}</td>
          <td>
            <form name="uploadFrm{$curr_file->file_id}" action="?m=dPcabinet" enctype="multipart/form-data" method="post">
            <input type="hidden" name="dosql" value="do_file_aed" />
	        <input type="hidden" name="del" value="1" />
	        <input type="hidden" name="file_id" value="{$curr_file->file_id}" />
	        <input type="button" value="supprimer" onclick="confirmDeletion(this.form, 'le fichier', '{$curr_file->file_name|escape:javascript}')"/>
            </form>
          </td>
	    </tr>
        {/foreach}
        <tr class="consult{$curr_consult->consultation_id}">
          <th colspan="3">
            <form name="uploadFrm" action="?m=dPcabinet" enctype="multipart/form-data" method="post">
            <input type="hidden" name="dosql" value="do_file_aed" />
            <input type="hidden" name="del" value="0" />
	        <input type="hidden" name="file_consultation" value="{$curr_consult->consultation_id}" />
            <input type="file" name="formfile" />
          </th>
          <td>
            <input type="submit" value="ajouter">
            </form>
          </td>
        </tr>
        {/foreach}
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
        {if $chirSel}
        <tr class="op{$curr_op->operation_id}">
          <th colspan="2">Pack de sortie :</th>
          <td colspan="2">
            <form name="printPackFrm{$curr_op->operation_id}" action="?m=dPhospi" method="POST">
            <select name="pack" onchange="printPack({$curr_op->operation_id}, this.form)">
              <option value="0">&mdash; packs &mdash;</option>
              {foreach from=$packs item=curr_pack}
              <option value="{$curr_pack->pack_id}">{$curr_pack->nom}</option>
              {/foreach}
            </select>
            </form>
          </td>
        </tr>
        <tr class="op{$curr_op->operation_id}"><th colspan="2">Compte-rendu op�ratoire :</th>
          <td colspan="2">
            <form name="editCROListFrm{$curr_op->operation_id}" action="?m=dPplanningOp" method="POST">
            {if $curr_op->compte_rendu}
            <input type="hidden" name="m" value="dPplanningOp" />
            <input type="hidden" name="del" value="0" />
            <input type="hidden" name="dosql" value="do_planning_aed" />
            <input type="hidden" name="operation_id" value="{$curr_op->operation_id}" />
            <input type="hidden" name="compte_rendu" value="{$curr_op->compte_rendu|escape:html}" />
            <input type="hidden" name="cr_valide" value="{$curr_op->cr_valide}" />
            <button type="button" onclick="editModeleO({$curr_op->operation_id}, 0)"><img src="modules/dPcabinet/images/edit.png" /></button>
              {if !$curr_op->cr_valide}
              <button type="button" onclick="validerCompteRenduO(this.form)"><img src="modules/dPcabinet/images/check.png" /></button>
              {/if}
            <button type="button" onclick="supprimerCompteRenduO(this.form)"><img src="modules/dPcabinet/images/trash.png" /></button>
            <button type="button" onclick="imprimerCRO({$curr_op->operation_id})"><img src="modules/dPcabinet/images/print.png" /></button>
            {else}
              {if $chirSel}
              <select name="modele" onchange="selectCRO({$curr_op->operation_id}, this.form)">
                <option value="0">&mdash; modeles &mdash;</option>
                {foreach from=$crOp item=curr_cr}
                <option value="{$curr_cr->compte_rendu_id}">{$curr_cr->nom}</option>
                {/foreach}
              </select>
              {else}
              <button type="button" onclick="editModeleO({$curr_op->operation_id}, 0)"><img src="modules/dPcabinet/images/edit.png" /></button>
              {/if}
            {/if}
            </form>
          </td>
        </tr>
        {/if}
        <tr class="op{$curr_op->operation_id}">
          <th colspan="2">Compte-rendu d'anesth�sie :</th>
          <td class="button" colspan="2">modifier imprimer supprimer</td>
        </tr>
        <tr class="op{$curr_op->operation_id}">
          <th colspan="2"><i>Fichiers associ�s :</i></th>
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
	        <input type="button" value="supprimer" onclick="confirmDeletion(this.form, 'le fichier', '{$curr_file->file_name|escape:javascript}')"/>
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
        {if $chirSel}
        <tr class="hospi{$curr_hospi->operation_id}">
          <th colspan="2">Pack de sortie :</th>
          <td colspan="2">
            <form name="printPackFrm{$curr_hospi->operation_id}" action="?m=dPhospi" method="POST">
            <select name="pack" onchange="printPack({$curr_hospi->operation_id}, this.form)">
              <option value="0">&mdash; packs &mdash;</option>
              {foreach from=$packs item=curr_pack}
              <option value="{$curr_pack->pack_id}">{$curr_pack->nom}</option>
              {/foreach}
            </select>
          </td>
        </tr>
        {/if}
        <tr class="hospi{$curr_hospi->operation_id}">
          <th colspan="2">Compte-rendu d'anesth�sie :</th>
          <td class="button" colspan="2">modifier imprimer supprimer</td>
        </tr>
        <tr class="hospi{$curr_hospi->operation_id}">
          <th colspan="2"><i>Fichiers associ�s :</i></th>
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
	        <input type="button" value="supprimer" onclick="confirmDeletion(this.form, 'le fichier', '{$curr_file->file_name|escape:javascript}')"/>
            </form>
          </td>
	    </tr>
        {/foreach}
        <tr class="hospi{$curr_hospi->operation_id}">
          <th colspan="3">
            <form name="uploadFrm" action="?m=dPcabinet" enctype="multipart/form-data" method="post">
            <input type="hidden" name="dosql" value="do_file_aed" />
            <input type="hidden" name="del" value="0" />
	        <input type="hidden" name="file_operation" value="{$curr_hospi->operation_id}" />
            <input type="file" name="formfile" />
          </th>
          <td>
            <input type="submit" value="ajouter">
            </form>
          </td>
        </tr>
        {/foreach}
       </table>
      {/if}
    </td>
  </tr>
</table>

