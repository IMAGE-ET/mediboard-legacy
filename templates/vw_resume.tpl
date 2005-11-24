<!-- $Id$ -->

{literal}
<script type="text/javascript">

function printDocument(doc_id) {
  var url = new Url;
  url.setModuleAction("dPcompteRendu", "print_cr");
  url.addParam("compte_rendu_id", doc_id);
  url.popup(700, 600, 'Compte-rendu');
}

</script>
{/literal}

<table class="tbl">
  <tr>
    <th colspan="3" class="title">Consultations</th>
  </tr>
  <tr>
    <th>Résumé</th>
    <th>Documents</th>
    <th>Fichiers</th>
  </tr>
  <tr>
    <td class="text">
    {foreach from=$consultations item=curr_consult}
      Dr. {$curr_consult->_ref_plageconsult->_ref_chir->_view}
      &mdash; {$curr_consult->_ref_plageconsult->date|date_format:"%d/%m/%Y"}
      <br />
    {/foreach}
    </td>
    <td class="text">
      <ul>
      {foreach from=$docsCons item=curr_doc}
        <li>
          {$curr_doc->nom}
          <button onclick="printDocument({$curr_doc->compte_rendu_id})">
            <img src="modules/dPcabinet/images/print.png" />
          </button>
        </li>
      {/foreach}
      </ul>
    </td>
    <td class="text">
      <ul>
      {foreach from=$filesCons item=curr_file}
        <li>
          <a href="mbfileviewer.php?file_id={$curr_file->file_id}" target="_blank">{$curr_file->file_name}</a>
          ({$curr_file->_file_size})
        </li>
      {/foreach}
      </ul>
    </td>
  </tr>
  <tr>
    <th colspan="3" class="title">Interventions</th>
  </tr>
  <tr>
    <th>Résumé</th>
    <th>Documents</th>
    <th>Fichiers</th>
  </tr>
  <tr>
    <td class="text">
    {foreach from=$operations item=curr_op}
      Dr. {$curr_op->_ref_chir->_view}
      &mdash; {$curr_op->_ref_plageop->date|date_format:"%d/%m/%Y"}
      <br />
    {/foreach}
    </td>
    <td class="text">
      <ul>
      {foreach from=$docsOp item=curr_doc}
        <li>
          {$curr_doc->nom}
          <button onclick="printDocument({$curr_doc->compte_rendu_id})">
            <img src="modules/dPcabinet/images/print.png" />
          </button>
        </li>
      {/foreach}
      </ul>
    </td>
    <td class="text">
      <ul>
      {foreach from=$filesOp item=curr_file}
        <li>
          <a href="mbfileviewer.php?file_id={$curr_file->file_id}" target="_blank">{$curr_file->file_name}</a>
          ({$curr_file->_file_size})
        </li>
      {/foreach}
      </ul>
    </td>
  </tr>
</table>