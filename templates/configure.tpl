<script language="JavaScript" type="text/javascript">
{literal}

function startCIM10() {
  var CIM10Url = new Url;
  CIM10Url.setModuleAction("dPcim10", "httpreq_do_add_cim10");
  CIM10Url.requestUpdate("cim10");
}

{/literal}
</script>

<h2>Import de la base de données CIM10</h2>
<button id="start_cim10" onclick="startCIM10()">
  Importer la base CIM10
</button>
<div id="cim10">
</div>