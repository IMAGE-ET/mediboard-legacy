<!-- $Id$ -->

{literal}
<script language="javascript">
function popCalendar( field ){
  calendarField = field;
  idate = eval( 'document.paramFrm.date_' + field + '.value' );
  var url = "index.php?m=public&a=calendar&dialog=1&callback=setCalendar";
  url += "&date=" + idate;
  popup(280, 250, url, 'calwin');
}

function setCalendar( idate, fdate ) {
  fld_date = eval( 'document.paramFrm.date_' + calendarField );
  fld_fdate = eval( 'document.paramFrm.' + calendarField );
  fld_date.value = idate;
  fld_fdate.value = fdate;
}
</script>
{/literal}

<table class="main"><tr>

  <td class="halfPane"><table class="form">
    <tr><th class="title" colspan="2">Edition de rapports</th></tr>
    <tr><th class="category" colspan="2">Choix de la periode</th></tr>
    <tr>
      <th><label for="paramFrm_debut">Début:</label></th>
      <td class="readonly" colspan="2">
        <input type="hidden" name="date_debut" value="{$todayi}" />
        <input type="text" name="debut" value="{$todayf}" readonly="readonly" />
        <a href="#" onClick="popCalendar( 'debut', 'debut');">
          <img src="./images/calendar.gif" width="24" height="12" alt="Choisir une date" />
        </a>
      </td>
    </tr>
    <tr>
      <th><label for="paramFrm_fin">Fin:</label></th>
      <td class="readonly" colspan="2">
        <input type="hidden" name="date_fin" value="{$todayi}" />
        <input type="text" name="fin" value="{$todayf}" readonly="readonly" />
        <a href="#" onClick="popCalendar( 'fin', 'fin');">
          <img src="./images/calendar.gif" width="24" height="12" alt="Choisir une date" />
        </a>
      </td>
    </tr>
    <tr><th class="category" colspan="2">Options sur le rapport</th></tr>
    <tr><th>Etat des paiements :</th>
      <td><select name="etat">
        <option value="1">Payés</option>
        <option value="0">Impayés</option>
      </select></td>
    </tr>
    <tr><th>Type de paiement :</th>
      <td><select name="type">
        <option value="0">Tout type</option>
        <option value="cheque">Chèques</option>
        <option value="CB">CB</option>
        <option value="especes">Espèces</option>
        <option value="tiers">Tiers-payant</option>
        <option value="autre">Autre</option>
      </select></td>
    </tr>
    <tr><th>Type d'affichage :</th>
      <td><select name="etat">
        <option value="1">Liste complète</option>
        <option value="0">Totaux</option>
      </select></td>
    </tr>
  </table></td>

  <td class="halfPane"><table align="center">

    {if $tarif->tarif_id}
    <tr><td colspan="3"><a href="index.php?m={$m}&amp;tarif_id=null"><b>Créer un nouveau tarif</b></a></td</tr>
    {/if}

    <tr><td><table class="tbl">
      <tr><th colspan="2">Tarifs du praticien</th></tr>
      {foreach from=$listeTarifsChir item=curr_tarif}
      <tr>
        <td><a href="index.php?m={$m}&amp;tarif_id={$curr_tarif->tarif_id}">{$curr_tarif->description}</a></td>
        <td><a href="index.php?m={$m}&amp;tarif_id={$curr_tarif->tarif_id}">{$curr_tarif->valeur} €</a></td>
      </tr>
      {/foreach}
    </table></td>

    <td><table class="tbl">
      <tr><th colspan="2">Tarifs du cabinet</th></tr>
      {foreach from=$listeTarifsSpe item=curr_tarif}
      <tr>
        <td><a href="index.php?m={$m}&amp;tarif_id={$curr_tarif->tarif_id}">{$curr_tarif->description}</a></td>
        <td><a href="index.php?m={$m}&amp;tarif_id={$curr_tarif->tarif_id}">{$curr_tarif->valeur} €</a></td>
      </tr>
      {/foreach}
    </table></td>

    <td>
      <form name="editFrm" action="./index.php?m={$m}" method="post" onSubmit="return checkMediuser()"/>
      <input type="hidden" name="dosql" value="do_tarif_aed" />
      <input type="hidden" name="tarif_id" value="{$tarif->tarif_id}" />
      <input type="hidden" name="del" value="0" />
      <input type="hidden" name="chir_id" value="{if $tarif->tarif_id}{$tarif->chir_id}{else}{$mediuser->user_id}{/if}" />
      <input type="hidden" name="function_id" value="{if $tarif->tarif_id}{$tarif->function_id}{else}{$mediuser->function_id}{/if}" />
      <table class="form">
        {if $tarif->tarif_id}
        <tr><th class="category" colspan="2">Editer ce tarif</th></tr>
        {else}
        <tr><th class="category" colspan="2">Créer un nouveau tarif</th></tr>
        {/if}
        <tr>
          <th>Type :</th>
          <td><select name="_type">
            <option value="chir" {if $tarif->chir_id} selected="selected" {/if}>Tarif personnel</option>
            <option value="function" {if $tarif->function_id} selected="selected" {/if}>Tarif de cabinet</option>
          </select></td>
        </tr>
        <tr><th>Nom :</th>
          <td><input type="text" name="description" value="{$tarif->description}" /></td></tr>
        <tr><th>Valeur :</th>
          <td><input type="text" name="valeur" value="{$tarif->valeur}" size="4" /> €</td></tr>
        <tr><td class="button" colspan="2">
          {if $tarif->tarif_id}
          <input type="submit" value="Modifier" />
          <input type="button" value="Supprimer" onclick="{literal}if (confirm('Veuillez confirmer la suppression')) {this.form.del.value = 1; this.form.submit();}{/literal}" />
          {else}
          <input type="submit" name="btnFuseAction" value="Créer">
          {/if}
        </td></tr>
      </table>
      </form>
    </td></tr>

  </table></td>

</tr></table>