<table class="main">
  <tr>
    <td>

      <table class="form">
        <tr>
          <th>Choisir un chirurgien</th>
          <td>
            <select name="choixChir">
              <option value="0" >-- Tous les chirurgiens</option>
              {foreach item=chir from=$chirs}
              <option value="{$chir.chir_id}">Dr. {$chir.lastname} {$chir.firstname} ({$chir.nb_protocoles})</option>
              {/foreach}
            </select>
          </td>
                
          <th>Choisir un code CCAM</th>
          <td>
            <select name="choixCode">
              <option value="0" >-- Tous les codes</option>
              {foreach item=code from=$codes}
              <option value="{$code.CCAM_code}">{$code.CCAM_code} ({$code.nb_protocoles})</option>
              {/foreach}
            </select>
          </td>
        </tr>
      </table>    
      
    </td>
  </tr>

  <tr>
    <td>

      <table class="tbl">
        <tr>
          <th>Chirurgien</th>
          <th>Code CCAM</th>
        </tr>
        
        {foreach item=protocole from=$protocoles}
        <tr>    
          <td><a href="?m={$m}&amp;tab=5&amp;protocole_id={$protocole.operation_id}">Dr. {$protocole.lastname} {$chir.firstname}</a></td>
          <td>{$protocole.CCAM_code}</td>
        </tr>
        {/foreach}
      </table>
 
     </td>
  </tr>
</table>

