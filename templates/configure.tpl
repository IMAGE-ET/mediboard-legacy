<script language="JavaScript" type="text/javascript">
{literal}

function startCCAM() {
  var CCAMUrl = new Url;
  CCAMUrl.setModuleAction("dPccam", "httpreq_do_add_ccam");
  CCAMUrl.requestUpdate("ccam");
}

{/literal}
</script>

<h2>Import de la base de données CCAM V2</h2>
<button id="start_ccam" onclick="startCCAM()">
  Importer la base CCAM
</button>
<div id="ccam">
</div>