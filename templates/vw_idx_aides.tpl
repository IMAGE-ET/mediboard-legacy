<!--  $Id$ -->
{literal}
<script language="javascript">

var modules = new Array;

{/literal}
{foreach from=$modules key=key_module item=classes}
{foreach from=$classes key=key_class item=fields}
{foreach from=$fields item=field}
addField('{$key_module}', '{$key_class}', '{$field}');
{/foreach}
{/foreach}
{/foreach}
{literal}

function pageMain() {
  {/literal}
  loadClasses('{$aide->class}');
  loadFields('{$aide->field}');
  {literal}
}

function addField(moduleName, className, fieldName) {
  // Warning: arrays don't return references until element is an array itself
  if (modules[moduleName] == null) {
    modules[moduleName] = new Array;
  }
  
  var classes = modules[moduleName];
  if (classes[className] == null) {
    classes[className] = new Array;
  }
  
  var fields = classes[className];
  fields[fieldName] = true;
}

function loadItems(select, options, value) {
  // delete all former options
  while (select.length > 1) {
    select.options[1] = null;
  }

  // insert new ones
  for (var elm in options) {
    select.options[select.length] = new Option(elm, elm, elm == value);
  }
  
}

function loadClasses(value) {
  var form = document.editFrm;
  var select = form.elements['class'];
  var moduleName = form.elements['module'].value;
  var classes = modules[moduleName];

  loadItems(select, classes, value);
  loadFields();
}

function loadFields(value) {
  var form = document.editFrm;
  var select = form.elements['field'];
  var moduleName = form.elements['module'].value;
  var className  = form.elements['class' ].value;
  var fields = modules[moduleName] ? modules[moduleName][className] : null;

  loadItems(select, fields, value);
}


function checkForm() {
  var form = document.editFrm;
  var field = null;
   
  if (field = form.elements['user_id']) {
    if (field.value == 0) {
      alert("Utilisateur indéterminé");
      field.focus();
      return false;
    }
  }
    
  if (field = form.elements['class']) {
    if (field.value == 0) {
      alert("Module indéterminé");
      field.focus();
      return false;
    }
  }
    
  if (field = form.elements['class']) {    
    if (field.value == 0) {
      alert("Type d'objet indéterminé");
      field.focus();
      return false;
    }
  }
    
  if (field = form.elements['field']) {    
    if (field.value == 0) {
      alert("Champ indéterminé");
      field.focus();
      return false;
    }
  }

  if (field = form.elements['key']) {    
    if (field.value == 0) {
      alert("Intitulé indéterminé");
      field.focus();
      return false;
    }
  }
    
  return true;
}
</script>
{/literal}

<table class="main">

<tr>
  <td class="greedyPane">

    <form name="filterFrm" action="?" method="get">
    
    <input type="hidden" name="m" value="{$m}">
        
    <table class="form">
      <tr>
        <th class="category" colspan="10">Filtrer les aides</th>
      </tr>

      <tr>
        <th><label for="filterFrm_filter_user_id" title="Filtrer les aides pour cet utilisateur">Utilisateur:</label></th>
        <td>
          <select name="filter_user_id" onchange="this.form.submit()">
            <option value="0">&mdash; Tous les utilisateurs</option>
            {foreach from=$users item=curr_user}
            <option value="{$curr_user->user_id}" {if $curr_user->user_id == $filter_user_id} selected="selected" {/if}>
              {$curr_user->user_last_name} {$curr_user->user_first_name}
            </option>
            {/foreach}
          </select>
        </td>
        <th><label for="filterFrm_filter_module" title="Filtrer les aides pour ce module">Module:</label></th>
        <td>
          <select name="filter_module" onchange="this.form.submit()">
            <option value="0">&mdash; Tous les modules</option>
              {html_options options=$moduleNames selected=$filter_module}
          </select>
        </td>
      </tr>
    </table>

    </form>
    
    <table class="tbl">
    
    <tr>
      <th colspan="10"><strong>Liste des aides à la saisie</strong></th>
    </tr>
    
    <tr>
      <th>Utilisateur</th>
      <th>Module</th>
      <th>Type d'objet</th>
      <th>Champ de l'objet</th>
      <th>Nom de l'aide</th>
      <th>Texte de remplacement</th>
    </tr>

    {foreach from=$aides item=curr_aide}
    <tr>
      {eval var=$curr_aide->aide_id assign="aide_id"}
      {assign var="href" value="?m=$m&amp;tab=$tab&amp;aide_id=$aide_id"}
      <td><a href="{$href}">{$curr_aide->_ref_user->user_last_name} {$curr_aide->_ref_user->user_first_name}</a></td>
      <td><a href="{$href}">{$curr_aide->_module_name}</a></td>
      <td><a href="{$href}">{$curr_aide->class}</a></td>
      <td><a href="{$href}">{$curr_aide->field}</a></td>
      <td><a href="{$href}">{$curr_aide->name}</a></td>
      <td class="text">{$curr_aide->text|nl2br}</td>
    </tr>
    {/foreach}
      
    </table>

  </td>
  
  <td class="pane">

	<a href="index.php?m={$m}&amp;tab={$tab}&amp;aide_id=0"><strong>Créer une aide à la saisie</strong></a>

    <form name="editFrm" action="?m={$m}" method="post" onsubmit="return checkForm()">

    <input type="hidden" name="dosql" value="do_aide_aed" />
    <input type="hidden" name="aide_id" value="{$aide->aide_id}" />
    <input type="hidden" name="del" value="0" />

    <table class="form">

    <tr>
      <th class="category" colspan="2">
      {if $aide->aide_id}
        Modification d'une aide
      {else}
        Création d'une aide
      {/if}
      </th>
    </tr>

    <tr>
      <th class="mandatory"><label for="editFrm_user_id" title="Utilisateur concerné, obligatoire.">Utilisateur:</label></th>
      <td>
        <select name="user_id">
          <option value="0">&mdash; Choisir un utilisateur</option>
          {foreach from=$users item=curr_user}
          <option value="{$curr_user->user_id}" {if $curr_user->user_id == $aide->user_id} selected="selected" {/if}>
            {$curr_user->user_last_name} {$curr_user->user_first_name}
          </option>
          {/foreach}
        </select>
      </td>
    </tr>

    <tr>
      <th class="mandatory"><label for="editFrm_module" title="Module concerné, obligatoire.">Module:</label></th>
      <td>
        <select name="module" onchange="loadClasses(this.value)">
          <option value="0">&mdash; Choisir un module</option>
          {html_options options=$moduleNames selected=$aide->module}
        </select>
      </td>
    </tr>

    <tr>
      <th class="mandatory"><label for="editFrm_class" title="Type d'objet concerné, obligatoire.">Objet:</label></th>
      <td>
        <select name="class" onchange="loadFields()">
          <option value="0">&mdash; Choisir un objet</option>
        </select>
      </td>
    </tr>

    <tr>
      <th class="mandatory"><label for="editFrm_field" title="Champ de l'objet concerné, obligatoire.">Champ:</label></th>
      <td>
        <select name="field">
          <option value="0">&mdash; Choisir un champ</option>
        </select>
      </td>
    </tr>

    <tr>
      <th class="mandatory"><label for="editFrm_name" title="intitulé de l'aide, obligatoire.">Intitulé:</label></th>
      <td><input type="text" name="name" value="{$aide->name}" /></td>
    </tr>
    
    <tr>
      <th><label for="editFrm_text" title="Texte de remplacement.">Texte:</label></th>
      <td>
        <textarea style="width: 200px" rows="4" name="text">{$aide->text}</textarea>
      </td>
    </tr>

    <tr>
      <td class="button" colspan="2">
        {if $aide->aide_id}
        <input type="reset" value="Réinitialiser" />
        <input type="submit" value="Valider" />
        <input type="button" value="Supprimer" onclick="confirmDeletion(this.form, 'l\'aide', '{$aide->name|escape:javascript}')" />
        {else}
        <input type="submit" value="Créer" />
        {/if}
      </td>
    </tr>

    </table>
    
    </form>

  </td>
</tr>

</table>
