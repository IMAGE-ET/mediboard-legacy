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

function imprimerCRO(op) {
  var url = '?m=dPplanningOp&a=print_cr&dialog=1';
  url +='&operation_id=' + op;
  popup(700, 700, url, 'Compte-rendu');
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
          <th colspan="2">CCAM 1 :</th>
          <td class="text" colspan="2">
            {$curr_op->_ext_code_ccam->code} : {$curr_op->_ext_code_ccam->libelleLong}
          </td>
        </tr>
        {if $curr_op->CCAM_code2}
        <tr class="op{$curr_op->operation_id}">
          <th colspan="2">CCAM 2 :</th>
          <td class="text" colspan="2">
            {$curr_op->_ext_code_ccam2->code} : {$curr_op->_ext_code_ccam2->libelleLong}
          </td>
        </tr>
        {/if}
        <tr class="op{$curr_op->operation_id}">
          <th colspan="2">Compte-rendu opératoire :</th>
          <td colspan="2" class="greedyPane">
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
          <td colspan="2">Pas de compte rendu</td>
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
          <th colspan="2">CCAM 1 :</th>
          <td class="text" colspan="2">
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
          <th colspan="2">CCAM 2 :</th>
          <td class="text" colspan="2">
            {$curr_hospi->_ext_code_ccam2->code} : {$curr_hospi->_ext_code_ccam2->libelleLong}
          </td>
        </tr>
        {/if}
        <tr class="hospi{$curr_hospi->operation_id}">
          <th colspan="2">Compte-rendu d'anesthésie :</th>
          <td colspan="2" class="greedyPane">Pas de compte rendu</td>
        </tr>
        {/foreach}
       </table>
      {/if}
    </td>
  </tr>
</table>

