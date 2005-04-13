<table class="fullCode">
  <tr>
  	<td class="pane">

  	  <table>
        <tr>
           <td colspan="2">
            <form action="index.php?m={$m}&amp;tab={$tab}" target="_self" name="selection" method="get" encoding="">
            <input type="hidden" name="m" value="{$m}">
            <input type="hidden" name="tab" value="{$tab}">

            <table class="form">
              <tr>
                <th class="mandatory">Code de l'acte:</th>
                <td>
                  <input tabindex="1" type="text" name="codeacte" value="{$codeacte}">
                  <input tabindex="2" type="submit" value="afficher">
                </td>
              </tr>
            </table>

            </form>
          </td>
        </tr>
        
        {if $canEdit}
        <tr>
          <td colspan="2">
            <form name="addFavoris" action="./index.php?m={$m}" method="post">
            <input type="hidden" name="dosql" value="do_favoris_aed">
            <input type="hidden" name="del" value="0">
            <input type="hidden" name="favoris_code" value="{$codeacte}">
            <input type="hidden" name="favoris_user" value="{$user}">

            <table class="form">
              <tr>
                <td class="button"><input class="button" type="submit" name="btnFuseAction" value="Ajouter à mes favoris"></td>
              </tr>
            </table>

            </form>
          </td>
        </tr>
        {/if}
        
        <tr>
          <td colspan="2"><strong>Description</strong><br />{$libelle}</td>
        </tr>

        {foreach from=$rq item=curr_rq}
        <tr>
          <td colspan="2"><em>{$curr_rq}</em></td>
        </tr>
        {/foreach}
 
        <tr>
          <td colspan="2"><strong>Activités associées</strong></td>
        </tr>
 
        {foreach from=$act item=curr_act}
        <tr>
          <td valign="top"><strong>{$curr_act.code}:</strong></td>
          <td valign="top" width="100%">{$curr_act.nom}
            <ul>
              <li>{$curr_act.phases} phase(s) : {$curr_act.tarif} €</li>
              <li>modificateurs: {$curr_act.modificateurs}</li>
            </ul>
          </td>
        </tr>
        {/foreach}
        
        <tr>
          <td colspan="2"><strong>Procédure associée:</strong></td>
        </tr>
        
        <tr>
          <td><a href="index.php?m={$m}&amp;tab={$tab}&amp;codeacte={$codeproc}"><strong>{$codeproc}</strong></a></td>
          <td>{$textproc}</td>
        </tr>
      </table>

    </td>
    <td class="pane">

      <table>
        <tr>
          <th class"category" colspan="2">Place dans la CCAM: {$place}</th>
        </tr>
        
        {foreach from=$chap item=curr_chap}
        <tr>
          <th>{$curr_chap.rang}</th>
          <td>{$curr_chap.nom}<br /><em>{$curr_chap.rq|nl2br}</em></td>
        </tr>
        {/foreach}
        
      </table>

    </td>
  </tr>
  <tr>
    <td class="pane">

      <table>
        <tr>
          <th class="category" colspan="2">Actes associés ({$asso|@count})</th>
        </tr>
        
        {foreach name=associations from=$asso item=curr_asso}
        <tr>
          <th><a href="index.php?m={$m}&amp;tab=2&amp;codeacte={$curr_asso.code}">{$curr_asso.code}</a></th>
          <td>{$curr_asso.texte}</td>
        </tr>
        {/foreach}
      </table>

    </td>
    <td class="pane">

      <table>
        <tr>
          <th class="category" colspan="2">Actes incompatibles ({$incomp|@count})</th>
        </tr>
        
        {foreach name=incompatibilites from=$incomp item=curr_incomp}
        <tr>
          <th><a href="index.php?m={$m}&amp;tab=vw_full_code&amp;codeacte={$curr_incomp.code}">{$curr_incomp.code}</a></th>
          <td>{$curr_incomp.texte}</td>
        </tr>
        {/foreach}
      </table>

    </td>
  </tr>
</table>
