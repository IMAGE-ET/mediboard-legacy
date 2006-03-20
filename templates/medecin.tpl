{if $end_of_process}
<script language="JavaScript" type="text/javascript">
{literal}
function pageMain() {
  window.opener.endProcess();
//  window.close();
}

{/literal}
</script>

{else}

<script language="JavaScript" type="text/javascript">
{literal}
function pageMain() {
  {/literal}
  window.opener.endStep(
    "{$step-1|string_format:"%03d.htm"}", 
    {$medecins|@count}, 
    {$chrono->total|string_format:"%.3f"}, 
    {$parse_errors}, 
    {$sibling_errors}, 
    {$stores}
  );
  {literal}
}

{/literal}
</script>

<table class="tbl">
  <tr>
   	<th class="title" colspan="10">R�sultat de l'�tape #{$step}</th>
  </tr>

  <tr>
   	<th colspan="10">{$medecins|@count} m�decins trouv�s</th>
  </tr>

{if $long_display}
  <tr>
  	<th>Nom</th>
  	<th>Pr�nom</th>
  	<th>Adresse</th>
  	<th>Ville</th>
  	<th>CP</th>
  	<th>T�l</th>
  	<th>Fax</th>
  	<th>M�l</th>
  	<th>Disciplines</th>
  </tr>
  
{foreach from=$medecins item=curr_medecin}
  <tr>
  	<td>{$curr_medecin->nom}</td>
  	<td>{$curr_medecin->prenom}</td>
  	<td>{$curr_medecin->adresse|nl2br}</td>
  	<td>{$curr_medecin->ville}</td>
  	<td>{$curr_medecin->cp}</td>
  	<td>{$curr_medecin->tel}</td>
  	<td>{$curr_medecin->fax}</td>
  	<td>{$curr_medecin->email}</td>
  	<td>{$curr_medecin->disciplines|nl2br}</td>
  </tr>
{/foreach}
{/if}

</table>

{/if}
