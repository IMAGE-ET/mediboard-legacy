<table class="main">
  <tr>
    <td>

      <form name="selectFrm" action="" method="get">
      <input type="hidden" name="m" value="{$m}" />
      <input type="hidden" name="tab" value="3" />
      <table class="form">
        <tr>
          <th>Choisir un chirurgien</th>
          <td>
            <select name="chir_id" onchange="this.form.submit()">
              <option value="0" >-- Tous les chirurgiens</option>
              {foreach item=curr_chir from=$chirs}
              <option value="{$curr_chir.chir_id}" {if $chirSel == $curr_chir.chir_id} selected="selected" {/if}>
                Dr. {$curr_chir.lastname} {$curr_chir.firstname} ({$curr_chir.nb_protocoles})
              </option>
              {/foreach}
            </select>
          </td>
                
          <th>Choisir un code CCAM</th>
          <td>
            <select name="CCAM_code" onchange="this.form.submit()">
              <option value="" >-- Tous les codes</option>
              {foreach item=curr_code from=$codes}
              <option value="{$curr_code.CCAM_code}" {if $codeSel == $curr_code.CCAM_code} selected="selected" {/if}>
                {$curr_code.CCAM_code} ({$curr_code.nb_protocoles})
              </option>
              {/foreach}
            </select>
          </td>
        </tr>
      </table>
      </form>    
      
    </td>
  </tr>

  <tr>
    <td>

      <table class="tbl">
        <tr>
          <th width="50%">Chirurgien</th>
          <th width="50%">Acte CCAM</th>
        </tr>
        
        {foreach item=curr_protocole from=$protocoles}
        <tr>    
          <td><a href="?m={$m}&amp;tab=5&amp;protocole_id={$curr_protocole.operation_id}">Dr. {$curr_protocole.lastname} {$curr_protocole.firstname}</a></td>
          <td><strong>{$curr_protocole.CCAM_code}</strong><br />{$curr_protocole.CCAM_libelle}</td>
        </tr>
        {/foreach}
      </table>
 
     </td>
  </tr>
</table>

