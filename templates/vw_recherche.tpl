<script language="JavaScript" type="text/javascript">
{literal}

function popPlanning() {
  var url = "?m=dPhospi&a=vw_affectations&dialog=1";
  url += "&date={/literal}{$date_recherche}{literal}";  
  popup(700, 550, url, 'Planning');
}

function pageMain() {
  {/literal}
  regFlatCalendar("calendar-container", "{$date_recherche}", "index.php?m={$m}&tab={$tab}&date_recherche=", true);
  {literal}
}

{/literal}
</script>

<table class="main">
  <tr>
    {if $typeVue}
    <td>
      <form name="chossePrat" action="?m={$m}" method="get">
      <input type="hidden" name="m" value="{$m}" />
      <select name="selPrat" onchange="submit()">
      <option value="0" {if $selPrat == 0}selected="selected"{/if}>&mdash; Selectionner un praticien &mdash;</option>
      {foreach from=$listPrat item=curr_prat}
        <option value="{$curr_prat->user_id}" {if $selPrat == $curr_prat->user_id}selected="selected"{/if}>
          {$curr_prat->_view}
        </option>
      {/foreach}
      </select>
      </form>
    </td>
    {else}
    <td class="Pane">
      <strong><a href="javascript:popPlanning()">Etat des services</a></strong>
    </td>
    {/if}
    <td style="text-align: right;">
      <form name="typeVue" action="?m={$m}" method="get">
      <input type="hidden" name="m" value="{$m}" />
      <select name="typeVue" onchange="submit()">
        <option value="0" {if $typeVue == 0}selected="selected"{/if}>Afficher les lits disponible</option>
        <option value="1" {if $typeVue == 1}selected="selected"{/if}>Afficher les patients d'un chirurgien</option>
      </select>
      </form>
    </td>
  </tr>
  <tr>
    <td><div id="calendar-container"></div></td>
    <td class="greedyPane">
      <table class="tbl">
        {if $typeVue == 0}
        <tr>
          <th class="title" colspan="4">
            {$date_recherche|date_format:"%A %d %B %Y à %H h %M"} : {$libre|@count} lit(s) disponible(s)
          </th>
        </tr>
        <tr>
          <th>Service</th>
          <th>Chambre</th>
          <th>Lit</th>
          <th>Fin de disponibilité</th>
        </tr>
        {foreach from=$libre item=curr_lit}
        <tr>
          <td>{$curr_lit.service}</td>
          <td>{$curr_lit.chambre}</td>
          <td>{$curr_lit.lit}</td>
          <td>{$curr_lit.limite|date_format:"%A %d %B %Y à %H h %M"}
        </tr>
        {/foreach}
        {else}
        <tr>
          <th class="title" colspan="7">
            {$date_recherche|date_format:"%A %d %B %Y"}
          </th>
        </tr>
        <tr>
          <th>Patient</th>
          <th>CCAM</th>
          <th>Service</th>
          <th>Chambre</th>
          <th>Lit</th>
          <th>Entrée</th>
          <th>Sortie prévue</th>
        </tr>
        {foreach from=$listAff item=curr_aff}
        <tr>
          <td>{$curr_aff->_ref_operation->_ref_pat->_view}</td>
          <td class="text">
          <strong>{$curr_aff->_ref_operation->_ext_code_ccam->code}</strong> :
          {$curr_aff->_ref_operation->_ext_code_ccam->libelleLong}
          {if $curr_aff->_ref_operation->CCAM_code2}
          <br />
          <strong>{$curr_aff->_ref_operation->_ext_code_ccam2->code}</strong> :
          {$curr_aff->_ref_operation->_ext_code_ccam2->libelleLong}
          {/if}
          </td>
          <td>{$curr_aff->_ref_lit->_ref_chambre->_ref_service->nom}</td>
          <td>{$curr_aff->_ref_lit->_ref_chambre->nom}</td>
          <td>{$curr_aff->_ref_lit->nom}</td>
          <td>{$curr_aff->entree|date_format:"%A %d %B %Y à %H h %M"}
          <td>{$curr_aff->sortie|date_format:"%A %d %B %Y à %H h %M"}
        </tr>
        {/foreach}
        {/if}
      </table>
    </td>
  </tr>
</table>