<?php /* Smarty version 2.6.3, created on 2004-12-13 18:50:03
         compiled from vw_idx_mediusers.tpl */ ?>
<?php echo '
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
'; ?>


<table class="main">

<tr>
  <td class="greedyPane">

		<a href="index.php?m=<?php echo $this->_tpl_vars['m']; ?>
&tab=0&mediuser=0"><strong>Créer un utilisateur</strong></a>

    <table class="color">
      
    <tr>
      <th>login</th>
      <th>nom</th>
      <th>prenom</th>
      <th>fonction</th>
    </tr>
    
    <?php if (count($_from = (array)$this->_tpl_vars['users'])):
    foreach ($_from as $this->_tpl_vars['curr_user']):
?>
    <tr style="background: #<?php echo $this->_tpl_vars['curr_user']['color']; ?>
">
      <td><a href="index.php?m=<?php echo $this->_tpl_vars['m']; ?>
&tab=0&mediuser=<?php echo $this->_tpl_vars['curr_user']['id']; ?>
"><?php echo $this->_tpl_vars['curr_user']['username']; ?>
</a></td>
      <td><a href="index.php?m=<?php echo $this->_tpl_vars['m']; ?>
&tab=0&mediuser=<?php echo $this->_tpl_vars['curr_user']['id']; ?>
"><?php echo $this->_tpl_vars['curr_user']['lastname']; ?>
</a></td>
      <td><a href="index.php?m=<?php echo $this->_tpl_vars['m']; ?>
&tab=0&mediuser=<?php echo $this->_tpl_vars['curr_user']['id']; ?>
"><?php echo $this->_tpl_vars['curr_user']['firstname']; ?>
</a></td>
      <td><a href="index.php?m=<?php echo $this->_tpl_vars['m']; ?>
&tab=0&mediuser=<?php echo $this->_tpl_vars['curr_user']['id']; ?>
"><?php echo $this->_tpl_vars['curr_user']['functionname']; ?>
</a></td>
    </tr>
    <?php endforeach; unset($_from); endif; ?>
      
    </table>

  </td>
  
  <td class="pane">

    <form name="mediuser" action="./index.php?m=<?php echo $this->_tpl_vars['m']; ?>
" method="post" onSubmit="return checkMediuser()"/>
    <input type="hidden" name="dosql" value="do_mediusers_aed" />
    <input type="hidden" name="user_id" value="<?php echo $this->_tpl_vars['usersel']['id']; ?>
" />
    <input type="hidden" name="del" value="0" />

    <table class="form">

    <tr>
      <th class="category" colspan="2">
        <?php if ($this->_tpl_vars['usersel']['exist']): ?>
        Modification de l'utilisateur &lsquo;<?php echo $this->_tpl_vars['usersel']['username']; ?>
&rsquo;
        <?php else: ?>
        Création d'un nouvel utilisateur
        <?php endif; ?>
      </th>
    </tr>

    <tr>
      <th class="mandatory"><label for="mediuser__user_username" title="Nom du compte pour se connecter à Mediboard. Obligatoire">Login:</label></th>
      <td><input type="text" name="_user_username" value="<?php echo $this->_tpl_vars['usersel']['username']; ?>
" /></td>
    </tr>
    
    <tr>
      <th class="mandatory"><label for="mediuser__user_password" title="Mot de passe pour se connecter à Mediboard. Obligatoire">Mot de passe:</label></th>
      <td><input type="password" name="_user_password" value="<?php echo $this->_tpl_vars['usersel']['password']; ?>
" /></td>
    </tr>
    
    <tr>
      <th class="mandatory"><label for="mediuser__user_password2" title="Re-saisir le mot de passe pour confimer. Obligatoire">Mot de passe (vérif.):</label></th>
      <td><input type="password" name="_user_password2" value="<?php echo $this->_tpl_vars['usersel']['password']; ?>
" /></td>
    </tr>
    
    <tr>
      <th class="mandatory"><label for="mediuser_fonction_id" title="Fonction de l'utilisateur au sein de l'établissement. Obligatoire">Fonction:</th>
      <td>
        <select name="function_id">
        <?php if (count($_from = (array)$this->_tpl_vars['functions'])):
    foreach ($_from as $this->_tpl_vars['curr_function']):
?>
          <option value="<?php echo $this->_tpl_vars['curr_function']['function_id']; ?>
" <?php if ($this->_tpl_vars['curr_function']['function_id'] == $this->_tpl_vars['usersel']['function']): ?> selected="selected" <?php endif; ?>>
            <?php echo $this->_tpl_vars['curr_function']['text']; ?>

          </option>
        <?php endforeach; unset($_from); endif; ?>
        </select>
      </td>
    </tr>

    <tr>
      <th class="mandatory"><label for="mediuser__user_last_name" title="Nom de famille de l'utilisateur. Obligatoire">Nom:</label></th>
      <td><input type="text" name="_user_last_name" value="<?php echo $this->_tpl_vars['usersel']['lastname']; ?>
" /></td>
    </tr>
    
    <tr>
      <th><label for="mediuser__user_first_name" title="Prénom de l'utilisateur">Prénom:</label></th>
      <td><input type="text" name="_user_first_name" value="<?php echo $this->_tpl_vars['usersel']['firstname']; ?>
" /></td>
    </tr>
    
    <tr>
      <th><label for="mediuser__user_email" title="Email de l'utilisateur">Email:</label></th>
      <td><input type="text" name="_user_email" value="<?php echo $this->_tpl_vars['usersel']['email']; ?>
" /></td>
    </tr>

    <tr>
      <th><label for="mediuser__user_phone" title="Numéro de téléphone de l'utilisateur">Tél:</label></th>
      <td><input type="text" name="_user_phone" value="<?php echo $this->_tpl_vars['usersel']['phone']; ?>
" /></td>
    </tr>
    
    <tr>
      <td class="button" colspan="2">
        <?php if ($this->_tpl_vars['usersel']['exist']): ?>
        <input type="reset" value="Réinitialiser" />
        <input type="submit" value="Valider" />
        <input type="button" value="Supprimer" onclick="<?php echo 'if (confirm(\'Veuillez confirmer la suppression\')) {this.form.del.value = 1; this.form.submit();}'; ?>
"/>
        <?php else: ?>
        <input type="submit" name="btnFuseAction" value="Créer">
        <?php endif; ?>
      </td>
    </tr>

    </table>

    </form>
  </td>
</tr>

</table>