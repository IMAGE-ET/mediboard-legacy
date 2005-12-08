<!-- $Id$ -->

<table class="main">
  <tr>
    <td class="greedyPane">
      <form name="find" action="./index.php" method="get">

      <input type="hidden" name="m" value="{$m}" />
      <input type="hidden" name="tab" value="{$tab}" />
      <input type="hidden" name="new" value="1" />
      
      <table class="form">
        <tr>
          <th class="category" colspan="2">Recherche d'un dossier patient</th>
        </tr>
  
        <tr>
          <th><label for="nom" title="Nom du patient à rechercher, au moins les premières lettres">Nom:</label></th>
          <td><input tabindex="1" type="text" name="nom" value="{$nom}" /></td>
        </tr>
        
        <tr>
          <th><label for="prenom" title="Prénom du patient à rechercher, au moins les premières lettres">Prénom:</label></th>
          <td><input tabindex="2" type="text" name="prenom" value="{$prenom}" /></td>
        </tr>
        
        <tr>
          <td class="button" colspan="2"><input type="submit" value="rechercher" /></td>
        </tr>
      </table>
      </form>

      <form name="fusion" action="index.php" method="get">
      <input type="hidden" name="m" value="dPpatients" />
      <input type="hidden" name="a" value="fusion_pat" />
      <table class="tbl">
        <tr>
          <th><button type="submit">Fusion</button></th>
          <th>Nom - Prénom</th>
          <th>Date de naissance</th>
          <th>Adresse</th>
          <th>Ville</th>
        </tr>

        {foreach from=$patients item=curr_patient}
        <tr>
          <td><input type="checkbox" name="fusion_{$curr_patient->patient_id}" /></td>
          <td class="text"><a href="index.php?m={$m}&amp;tab={$tab}&amp;id={$curr_patient->patient_id}">{$curr_patient->_view}</a></td>
          <td class="text"><a href="index.php?m={$m}&amp;tab={$tab}&amp;id={$curr_patient->patient_id}">{$curr_patient->_naissance}</a></td>
          <td class="text"><a href="index.php?m={$m}&amp;tab={$tab}&amp;id={$curr_patient->patient_id}">{$curr_patient->adresse}</a></td>
          <td class="text"><a href="index.php?m={$m}&amp;tab={$tab}&amp;id={$curr_patient->patient_id}">{$curr_patient->ville}</a></td>
        </tr>
        {/foreach}
        
      </table>
      </form>

    </td>
 
    {if $patient->patient_id}
    <td class="pane">
    {include file="inc_vw_patient.tpl"}
    </td>
    {/if}
  </tr>
</table>