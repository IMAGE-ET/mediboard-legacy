{literal}
<script language="javascript">
function checkMediuser() {
  var form = document.mediuser;
    
  if (form._user_username.value.length < 3) {
    alert("Nom utilisateur trop court");
    form._user_username.focus();
    return false;
  }
  
  if (form._user_password.value.length < 4) {
    alert("Mot de passe trop court");
    form._user_password.focus();
    return false;
  } 
  
  if (form._user_password.value !=  form._user_password2.value) {
    alert("Les deux mots de passe diffèrent");
    form._user_password.focus();
    return false;
  } 
  
  if (form._user_last_name.value.length == 0) {
    alert("Nom manquant");
    form._user_last_name.focus();
    return false;
  }
  
  return true;
}
</script>
{/literal}

<table class="main">

<tr>
  <td class="greedyPane">

		<a href="index.php?m={$m}&tab=0&mediuser=0"><strong>Créer un utilisateur</strong></a>

    <table class="color">
      
    <tr>
      <th>login</th>
      <th>nom</th>
      <th>prenom</th>
      <th>fonction</th>
    </tr>
    
    {foreach from=$users item=curr_user}
    <tr style="background: #{$curr_user.color}">
      <td><a href="index.php?m={$m}&tab=0&mediuser={$curr_user.id}">{$curr_user.username}</a></td>
      <td><a href="index.php?m={$m}&tab=0&mediuser={$curr_user.id}">{$curr_user.lastname}</a></td>
      <td><a href="index.php?m={$m}&tab=0&mediuser={$curr_user.id}">{$curr_user.firstname}</a></td>
      <td><a href="index.php?m={$m}&tab=0&mediuser={$curr_user.id}">{$curr_user.functionname}</a></td>
    </tr>
    {/foreach}
      
    </table>

  </td>
  
  <td class="pane">

    <form name="mediuser" action="./index.php?m={$m}" method="post" onSubmit="return checkMediuser()"/>
    <input type="hidden" name="dosql" value="do_mediusers_aed" />
    <input type="hidden" name="user_id" value="{$usersel.id}" />
    <input type="hidden" name="del" value="0" />

    <table class="form">

    <tr>
      <th class="category" colspan="2">
        {if $usersel.exist}
        Modification de l'utilisateur &lsquo;{$usersel.username}&rsquo;
        {else}
        Création d'un nouvel utilisateur
        {/if}
      </th>
    </tr>

    <tr>
      <th class="mandatory">Login:</th>
      <td><input type="text" name="_user_username" value="{$usersel.username}" /></td>
    </tr>
    
    <tr>
      <th class="mandatory">Mot de passe:</th>
      <td><input type="password" name="_user_password" /></td>
    </tr>
    
    <tr>
      <th class="mandatory">Mot de passe (vérif.):</th>
      <td><input type="password" name="_user_password2" /></td>
    </tr>
    
    <tr>
      <th class="mandatory">Fonction:</th>
      <td>
        <select name="function_id">
        {foreach from=$functions item=curr_function}
          <option value="{$curr_function.function_id}" {if $curr_function.function_id == $usersel.function} selected="selected" {/if}>
            {$curr_function.text}
          </option>
        {/foreach}
        </select>
      </td>
    </tr>

    <tr>
      <th class="mandatory">Nom:</th>
      <td><input type="text" name="_user_last_name" value="{$usersel.lastname}" /></td>
    </tr>
    
    <tr>
      <th>Prénom:</th>
      <td><input type="text" name="_user_first_name" value="{$usersel.firstname}" /></td>
    </tr>
    
    <tr>
      <th>Email:</th>
      <td><input type="text" name="_user_email" value="{$usersel.email}" /></td>
    </tr>

    <tr>
      <th>Tel:</th>
      <td><input type="text" name="_user_phone" value="{$usersel.phone}" /></td>
    </tr>
    
    <tr>
      <td class="button" colspan="2">
        {if $usersel.exist}
        <input type="reset" value="Réinitialiser" />
        <input type="submit" value="Valider" />
        <input type="button" value="Supprimer" onclick="{literal}if (confirm('Veuillez confirmer la suppression')) {this.form.del.value = 1; this.form.submit();}{/literal}"/>
        {else}
        <input type="submit" name="btnFuseAction" value="Créer">
        {/if}
      </td>
    </tr>

    </table>

    </form>
  </td>
</tr>

</table>