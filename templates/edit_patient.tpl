{literal}
<script language="JavaScript" type="text/javascript">

</script>
{/literal}

<table class="main">
  <tr>
    <td style="vertical-align: top">

    <table align="center" width="100%">
      <tr>
        <th></th>
        <th><a href="?m={$m}&amp;change=1&amp;yearconsult={$pyear}"><</a></th>
        <th>{$year}</th>
        <th><a href="?m={$m}&amp;change=1&amp;yearconsult={$nyear}">></a></th>
        <th></th>
      </tr>
      <tr>
        <th><a href="?m={$m}&amp;change=1&amp;monthconsult={$ppmonth}"><<</a></th>
        <th><a href="?m={$m}&amp;change=1&amp;monthconsult={$pmonth}"><</a></th>
        <th>{$monthName}</th>
        <th><a href="?m={$m}&amp;change=1&amp;monthconsult={$nmonth}">></a></th>
        <th><a href="?m={$m}&amp;change=1&amp;monthconsult={$nnmonth}">>></a></th>
      </tr>
      <tr>
        <th><a href="?m={$m}&amp;change=1&amp;dayconsult={$ppday}"><<</a></th>
        <th><a href="?m={$m}&amp;change=1&amp;dayconsult={$pday}"><</a></th>
        <th>{$dayName} {$day}</th>
        <th><a href="?m={$m}&amp;change=1&amp;dayconsult={$nday}">></a></th>
        <th><a href="?m={$m}&amp;change=1&amp;dayconsult={$nnday}">>></a></th>
      </tr>
    </table>

    <table class="tbl">
      {if $listPlage}
      {foreach from=$listPlage item=curr_plage}
        <tr>
          <th colspan="4" style="font-weight: bold;">Consultations de {$curr_plage->_hour_deb}h à {$curr_plage->_hour_fin}h</th>
        </tr>
        <tr>
          <th>Heure</th>
          <th>Patient</th>
          <th>RDV</th>
          <th>Etat</th>
        </tr>
        {foreach from=$curr_plage->_ref_consultations_anesth item=curr_consult}
          {if $curr_consult->premiere} 
            {assign var="style" value="style='background: #faa;'"}
          {else} 
            {assign var="style" value=""}
          {/if}
        
        <tr {if $curr_consult->consultation_anesth_id == $consult->consultation_anesth_id} style="font-weight: bold;" {/if}>
          <td {$style}>
            <a href="index.php?m={$m}&amp;tab=edit_consultation&amp;selConsult={$curr_consult->consultation_anesth_id}">{$curr_consult->heure|truncate:5:"":true}</a>
          </td>
          <td {$style}>
            <a href="index.php?m={$m}&amp;tab=edit_consultation&amp;selConsult={$curr_consult->consultation_anesth_id}">{$curr_consult->_ref_patient->_view}</a>
          </td>
          <td {$style}>
            <a href="index.php?m={$m}&amp;tab=edit_planning&amp;consultation_anesth_id={$curr_consult->consultation_anesth_id}" title="Modifier le RDV">
              <img src="modules/{$m}/images/planning.png" />
            </a>
          </td>
          <td {$style}>{$curr_consult->_etat}</td>
        </tr>
        {/foreach}
      {/foreach}
      {else}
        <tr>
          <th colspan="2" style="font-weight: bold;">Pas de consultations</th>
        </tr>
      {/if}
    </table>
    
	</td>
	
	<td class="greedyPane" style="vertical-align: top">
	  <table width="100%">
	    <tr>
	      <th class="title" colspan="2">
	        {if $mode == "antcMain"}
	          Principaux antécédents
	        {elseif $mode == "antcMed"}
	          Antécédents médicaux
	        {elseif $mode == "antcChir"}
	         Antécédents Chirurgicaux et anesthésiques
	        {elseif $mode == "antcObst"}
	          Antécédents obstétriques
	        {elseif $mode == "antcTrans"}
	          Antécédents transfusionnels
	        {elseif $mode == "traitements"}
	          Traitements
	        {else}
	          Antécédents
	        {/if}
	      </th>
	      <td rowspan="2">
	        <form name="editFrm" action="?m={$m}" method="POST">
            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="del" value="0" />
            <input type="hidden" name="dosql" value="do_consultation_anesth_aed" />
            <input type="hidden" name="consultation_anesth_id" value="{$consult->consultation_anesth_id}" />
            <input type="hidden" name="_check_premiere" value="{$consult->_check_premiere}" />
	        <table class="form">
	          <tr><th>Nom :</th><td>{$consult->_ref_patient->_view}</td></tr>
	          <tr><th>Age :</th><td>{$consult->_ref_patient->_age} ans</td></tr>
	          <tr><th>Taille :</th><td><input name="taille" type="text" size="3" value="{$consult->taille}" /> cm</td></tr>
	          <tr><th>Poid :</th><td><input name="poid" type="text" size="4" value="{$consult->poid}" /> Kg</td></tr>
	          <tr><th>TA :</th><td><input name="ta1" type="text" size="2" value="{$consult->ta1}" /> / <input name="ta2" type="text" size="2" value="{$consult->ta2}" /></td></tr>
	          <tr><td class="button" colspan="2"><button type="submit">Valider</button></td></tr>
	        </table>
	        </form>
	      </td>
	    </tr>
	    <tr>
	      <td>
	        <table class="form">
	          <tr><th {if $mode == "antcMain"}class="category"{/if}>
	            <a href="index.php?m={$m}&amp;mode=antcMain">
	            <img src="modules/{$m}/images/antcMain.png" alt="Antécédents principaux" />
	            </a>
	          </th></tr>
	          <tr><th {if $mode == "antcMed"}class="category"{/if}>
	            <a href="index.php?m={$m}&amp;mode=antcMed">
      	        <img src="modules/{$m}/images/antcMed.png" alt="Antécédents médicaux" />
	            </a>
	          </th></tr>
	          <tr><th {if $mode == "antcChir"}class="category"{/if}>
	            <a href="index.php?m={$m}&amp;mode=antcChir">
	            <img src="modules/{$m}/images/antcChir.png" alt="Antécédents chirurgicaux et anesthésiques" />
	            </a>
	          </th></tr>
	          <tr><th {if $mode == "antcObst"}class="category"{/if}>
	            <a href="index.php?m={$m}&amp;mode=antcObst">
	            <img src="modules/{$m}/images/antcObst.png" alt="Antécédents obstétricaux" />
	            </a>
	          </th></tr>
	          <tr><th {if $mode == "antcTrans"}class="category"{/if}>
	            <a href="index.php?m={$m}&amp;mode=antcTrans">
	            <img src="modules/{$m}/images/antcTrans.png" alt="Antécédents transfusionnels" />
	            </a>
	          </th></tr>
	          <tr><th {if $mode == "traitements"}class="category"{/if}>
	            <a href="index.php?m={$m}&amp;mode=traitements">
	            <img src="modules/{$m}/images/traitements.png" alt="Traitements" />
	            </a>
	          </th></tr>
	        </table>
	      </td>
	      <td width="100%">
	        {if $mode == "antcMain"}
	          {include file="antcMain.tpl"}
	        {elseif $mode == "antcMed"}
	          {include file="antcMed.tpl"}
	        {elseif $mode == "antcChir"}
	          {include file="antcChir.tpl"}
	        {elseif $mode == "antcObst"}
	          {include file="antcObst.tpl"}
	        {elseif $mode == "antcTrans"}
	          {include file="antcTrans.tpl"}
	        {elseif $mode == "traitements"}
	          {include file="traitements.tpl"}
	        {else}
	          Choisissez une catégorie
	        {/if}
	      </td>
	    </tr>
	  </table>
	</td>
	
  </tr>
</table>