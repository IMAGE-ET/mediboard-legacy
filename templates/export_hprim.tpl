<script language="JavaScript" type="text/javascript">
{literal}

function doAction(sAction) {
  url = "index.php?m=dPinterop&dialog=1";
  url += "&a=" + sAction;
  
  popup(400, 400, url, sAction);
}  

{/literal}
</script>

<p>Essai de validation d'un document XML.</p>

<h1>Utilisation de SimpleXML</h1>

<h2>XML Dump</h2>

<pre>  {$simpleXML_export}</pre>

<h1>Utilisation de DOM</h1>

<h2>XML Dump</h2>

{$dom_export}

<h2>XML Validation</h2>

{if $dom_valid}
Le document est valide
{else}
Le document n'est pas valide
{/if}