<!-- $Id$ -->

<script type="text/javascript">
{literal}
function printAdmission(id) {
  var url = './index.php?m=dPadmissions&a=print_admission&dialog=1';
  url = url + '&id=' + id;
  popup(700, 550, url, 'Patient');
}

function printDepassement(id) {
  var url = './index.php?m=dPadmissions&a=print_depassement&dialog=1';
  url = url + '&id=' + id;
  popup(700, 550, url, 'Depassement');
}

function pageMain() {
  Calendar.setup( {
      button      : "changeDate",
      align       : "Bc",
      date        : makeDateFromDATE("{/literal}{$date}{literal}"),
      onUpdate    : function(calendar) { 
        if (calendar.dateClicked) {
          {/literal}
          window.location = "index.php?m={$m}&tab={$tab}&date=" + makeDATEFromDate(calendar.date);
          {literal}
        }
      }
    } 
  );
}

{/literal}
</script>

<table class="main">
  <tr>
    <th>
      {$date|date_format:"%B %Y"}
      <img id="changeDate" src="./images/calendar.gif" title="Choisir la date" alt="calendar" />
    </th>
    <th>
      {$date|date_format:"%A %d %B %Y"} - 
      <i>{if $selAdmis == "n"}Admissions non effectu�es
      {elseif $selSaisis == "n"}Dossiers non pr�par�s
      {else}Toutes les admissions
      {/if}
      {if $selTri == "nom"}tri�es par nom
      {elseif $selTri == "heure"}tri�es par heure
      {/if}</i>
    </th>
  </tr>
  <tr>
    <td>
      <table class="tbl">
        <tr>
          <th class="text">Date</th>
          <th class="text"><a href="index.php?m={$m}&amp;tab={$tab}&amp;selAdmis=0&amp;selSaisis=0">Toutes les admissions</a></th>
          <th class="text"><a href="index.php?m={$m}&amp;tab={$tab}&amp;selAdmis=0&amp;selSaisis=n">Dossiers non pr�par�s</a></th>
          <th class="text"><a href="index.php?m={$m}&amp;tab={$tab}&amp;selAdmis=n&amp;selSaisis=0">Admissions non effectu�es</a></th>
        </tr>
        {foreach from=$list1 item=curr_list}
        <tr>
          <td align="right">
            <a href="index.php?m={$m}&amp;tab={$tab}&amp;date={$curr_list.date|date_format:"%Y-%m-%d"}">
            {$curr_list.date|date_format:"%A %d"}
            </a>
          </td>
          <td align="center">
            {$curr_list.num}
          </td>
          <td align="center">
            {$curr_list.num3}
          </td>
          <td align="center">
            {$curr_list.num2}
          </td>
        </tr>
        {/foreach}
      </table>
    </td>
    <td>
      <table class="tbl">
        <tr>
          <th><a href="index.php?m={$m}&amp;tab={$tab}&amp;selAdmis=0&amp;selTri=nom">Nom</a></th>
          <th>Chirurgien</th>
          <th><a href="index.php?m={$m}&amp;tab={$tab}&amp;selAdmis=0&amp;selTri=heure">Heure</a></th>
          <th>Chambre</th>
          <th>Admis</th>
          <th>
            <form name="editAllAdmFrm" action="index.php" method="get">
            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="a" value="do_edit_admis" />
            <input type="hidden" name="id" value="{$year}-{$month}-{$day}" />
            <input type="hidden" name="mode" value="allsaisie" />
            <input type="hidden" name="value" value="o" />
            Saisis
            <button type="submit">
              <img src="modules/{$m}/images/tick.png" alt="Admis">
            </button>
            </form>
          </th>
          <th>DH</th>
        </tr>
        {foreach from=$today item=curr_adm}
        <tr>
          <td class="text" style="background: {if $curr_adm->annulee == 1}#f33{elseif $curr_adm->type_adm == 'ambu'}#faa{elseif $curr_adm->type_adm == 'comp'}#fff{else}#afa{/if}">
            <a name="adm{$curr_adm->operation_id}" onclick="printAdmission({$curr_adm->operation_id})">
            {$curr_adm->_ref_pat->_view}
            </a>
          </td>
          <td class="text" style="background: {if $curr_adm->annulee == 1}#f33{elseif $curr_adm->type_adm == 'ambu'}#faa{elseif $curr_adm->type_adm == 'comp'}#fff{else}#afa{/if}">
            <a href="#" onclick="printAdmission({$curr_adm->operation_id})">
            Dr. {$curr_adm->_ref_chir->_view}
            </a>
          </td>
          <td style="background: {if $curr_adm->annulee == 1}#f33{elseif $curr_adm->type_adm == 'ambu'}#faa{elseif $curr_adm->type_adm == 'comp'}#fff{else}#afa{/if}">
            <a href="#" onclick="printAdmission({$curr_adm->operation_id})">
            {$curr_adm->time_adm|date_format:"%Hh%M"}
            </a>
          </td>
          <td class="text" style="background: {if $curr_adm->annulee == 1}#f33{elseif $curr_adm->type_adm == 'ambu'}#faa{elseif $curr_adm->type_adm == 'comp'}#fff{else}#afa{/if}">
            {if $curr_adm->_first_aff->affectation_id}
            {$curr_adm->_first_aff->_ref_lit->_ref_chambre->_ref_service->nom}
            - {$curr_adm->_first_aff->_ref_lit->_ref_chambre->nom}
            - {$curr_adm->_first_aff->_ref_lit->nom}
            {else}
            Pas de chambre
            {/if}
            <!-- Pas de modif des chambres pour l'instant
            <br />
            <form name="editChFrm{$curr_adm->operation_id}" action="index.php" method="get">
            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="a" value="do_edit_chambre" />
            <input type="hidden" name="id" value="{$curr_adm->operation_id}" />
            {if $curr_adm->chambre == 'o'}
            <input type="hidden" name="value" value="n" />
            <button type="submit">
              <img src="modules/{$m}/images/refresh.png" alt="changer"> chambre simple
            </button>
            {else}
            <input type="hidden" name="value" value="o" />
            <button type="submit">
              <img src="modules/{$m}/images/refresh.png" alt="changer"> chambre double
            </button>
            {/if}
            </form>
            -->
          </td>
          {if $curr_adm->annulee == 1}
          <td style="background: {if $curr_adm->annulee == 1}#f33{elseif $curr_adm->type_adm == 'ambu'}#faa{elseif $curr_adm->type_adm == 'comp'}#fff{else}#afa{/if}" align="center" colspan=2>
            <b>ANNULE</b></td>
          {else}
          <td style="background: {if $curr_adm->annulee == 1}#f33{elseif $curr_adm->type_adm == 'ambu'}#faa{elseif $curr_adm->type_adm == 'comp'}#fff{else}#afa{/if}">
            <form name="editAdmFrm{$curr_adm->operation_id}" action="index.php" method="get">
            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="a" value="do_edit_admis" />
            <input type="hidden" name="id" value="{$curr_adm->operation_id}" />
            <input type="hidden" name="mode" value="admis" />
            {if $curr_adm->admis == "n"}
            <input type="hidden" name="value" value="o" />
            <button type="submit">
              <img src="modules/{$m}/images/tick.png" alt="Admis"> Admis
            </button>
            {else}
            <input type="hidden" name="value" value="n" />
            <button type="submit">
              <img src="modules/{$m}/images/cross.png" alt="Annuler"> Annuler
            </button>
            {/if}
            </form>
          </td>
          <td style="background: {if $curr_adm->annulee == 1}#f33{elseif $curr_adm->type_adm == 'ambu'}#faa{elseif $curr_adm->type_adm == 'comp'}#fff{else}#afa{/if}">
            <form name="editSaisFrm{$curr_adm->operation_id}" action="index.php" method="get">
            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="a" value="do_edit_admis" />
            <input type="hidden" name="id" value="{$curr_adm->operation_id}" />
            <input type="hidden" name="mode" value="saisie" />
            {if $curr_adm->saisie == "n"}
            <input type="hidden" name="value" value="o" />
            <button type="submit">
              <img src="modules/{$m}/images/tick.png" alt="Saisie"> Saisie
            </button>
            {else}
            <input type="hidden" name="value" value="n" />
            <button type="submit">
              <img src="modules/{$m}/images/cross.png" alt="Annuler"> Annuler
            </button>
            {/if}
            {if $curr_adm.modifiee == 1}
            <img src="images/icons/rc-gui-status-downgr.png" alt="modifi�">
            {/if}
            </form>
          </td>
          {/if}
          <td style="background: {if $curr_adm->annulee == 1}#f33{elseif $curr_adm->type_adm == 'ambu'}#faa{elseif $curr_adm->type_adm == 'comp'}#fff{else}#afa{/if}">
          {if $curr_adm.depassement}
          <!-- Pas de possibilit� d'imprimer les d�passements pour l'instant -->
          <!-- <a href="#" onclick="printDepassement({$curr_adm->operation_id})"></a> -->
          {$curr_adm->depassement} �
          {else}-{/if}</td>
        </tr>
        {/foreach}
      </table>
    </td>
  </tr>
</table>