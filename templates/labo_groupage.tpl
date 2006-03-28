<table class="main">
  <tr>
    <td class="halfpane">
    <form name="GHMFrm" action="?m={$m}" method="get">
    <input type="hidden" name="m" value="{$m}" />
      <table class="form">
        <tr><th class="title">Patient</th></tr>
        <tr><th class="category">Age (ex : 53a ou 120j)</th></tr>
        <tr><td><input type="text" name="age" value="{$GHM->age}" />
      </table>
      <table class="form">
        <tr><th class="title" colspan="3">Diagnostics</th></tr>
        <tr>
          <th class="category">DP</th>
          <th class="category">DR</th>
          <th class="category">DAS</th>
        </tr>
        <tr>
          <td><input type="text" name="DP" value="{$GHM->DP}" /></td>
          <td>
            {foreach from=$GHM->DRs item=DR key=key}
            <input type="text" name="DRs[{$key}]" value="{$DR}" /><br />
            {/foreach}
            <input type="text" name="DRs[{$GHM->DRs|@count}]" value="" />
          </td>
          <td>
            {foreach from=$GHM->DASs item=DAS key=key}
            <input type="text" name="DASs[{$key}]" value="{$DAS}" /><br />
            {/foreach}
            <input type="text" name="DASs[{$GHM->DASs|@count}]" value="" />
          </td>
        </tr>
      </table>
      <table class="form">
        <tr><th class="title" colspan="3">Actes</th></tr>
        <tr>
          <th class="category">Code</th>
          <th class="category">Phase</th>
          <th class="category">Activite</th>
        </tr>
        {foreach from=$GHM->actes item=acte key=key}
        <tr>
          <td><input type="text" name="actes[{$key}]" value="{$acte}" /></td>
          <td>Phase</td>
          <td>Activite</td>
        </tr>
        {/foreach}
        <tr>
          <td><input type="text" name="actes[{$GHM->actes|@count}]" value="" /></td>
          <td>Phase</td>
          <td>Activite</td>
        </tr>
      </table>
      <table class="form">
        <tr><th class="title">Hospi</th></tr>
        <tr><th class="category">Type</th></tr>
        <tr>
          <td>
            <select name="type_hospi">
              <option value="séance" {if $GHM->type_hospi == "séance"}selected="selected"{/if}>Séance</option>
              <option value="ambu" {if $GHM->type_hospi == "ambu"}selected="selected"{/if}>Ambulatoire ( < 2 jours)</option>
              <option value="comp" {if $GHM->type_hospi == "comp"}selected="selected"{/if}>Hospi. complète( > 2 jours)</option>
              <option value="exte" {if $GHM->type_hospi == "exte"}selected="selected"{/if}>Hospi. externe</option>
            </select>
          </td>
        </tr>
        <tr>
          <td class="button">
            <button type="submit">Calculer</button>
          </td>
        </tr>
      </table>
      </form>
    </td>
    <td class="halfpane">
      <table class="tbl">
        <tr>
          <th class="title">
            Résultat
          </th>
        </tr>
        <tr>
          <td class="text">
            {if $GHM->CM}
            <strong>Catégorie majeure CM{$GHM->CM}</strong> : {$GHM->CM_nom}
            <br />
            {/if}
            {if $GHM->CM}
            <strong>GHM</strong> : {$GHM->GHM}
            <br />
            {$GHM->GHM_nom}
            <br />
            <i>Appartenance aux groupes {$GHM->GHM_groupe}</i>
            <br />
            <strong>Chemin :</strong> <br />
            {$GHM->chemin}
            {/if}
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>