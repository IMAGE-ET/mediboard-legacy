<!-- $Id$ -->
<table class="main">
  <tr>
    <td class="halfPane">
      <table>
        <tr><th><a href="javascript:window.print()">{$titre}</a></th></tr>
        {if $chirSel->user_id}<tr><th>Dr. {$chirSel->_user_first_name} {$chirSel->_user_last_name}</th></tr>{/if}
        <tr><td>affichage des {if $etat}montants réglés{else}impayés{/if}</td></tr>
        <tr><td>Paiments pris en compte : {if $type}{$type}{else}tous{/if}</td></tr>
      </table>
    </td>
    <td class="halfPane">
      <table class="form">
        <tr><th class="category" colspan="2">Récapitulatif</th></tr>
        <tr><th>Nombre de consultations :</th><td>{$total.nombre}</td></tr>
        <tr><th>Valeur totale :</th><td>{$total.tarif} €</td></tr>
      </table>
    </td>
  </tr>
  {if $aff}
  {foreach from=$listPlage item=curr_plage}
  <tr>
    <td coslpan="2"><b>{$curr_plage->date|date_format:"%a %d %b %Y"} - Dr. {$curr_plage->_ref_chir->user_first_name} {$curr_plage->_ref_chir->user_last_name}</b></td>
  </tr>
  <tr>
    <td colspan="2">
      <table class="tbl">
        <tr>
          <th>Nom</th>
          <th>Prenom</th>
          <th>Telephone</th>
          <th>Mobile</th>
          <th>Type</th>
          <th>Valeur</th>
        </tr>
        {foreach from=$curr_plage->_ref_consultations item=curr_consult}
        <tr>
          <td>{$curr_consult->_ref_patient->nom}</td>
          <td>{$curr_consult->_ref_patient->prenom}</td>
          <td>{$curr_consult->_ref_patient->tel}</td>
          <td>{$curr_consult->_ref_patient->tel2}</td>
          <td>{$curr_consult->type_tarif}</td>
          <td>{$curr_consult->tarif} €</td>
        </tr>
        {/foreach}
        <tr>
          <td colspan="4"></td>
          <th>Total</th>
          <td>{$curr_plage->total} €</td>
        </tr>
      </table>
    </td>
  </tr>
  {/foreach}
  {/if}
</table>
      