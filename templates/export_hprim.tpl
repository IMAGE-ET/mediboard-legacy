<h1>Essai de validation d'un document XML</h1>

<h2>Utilisation de DOM</h2>

<h3>XML Dump</h3>

<pre>{$dom_export|escape:html}</pre>

<h3>XML Schema Validation</h3>

{if $dom_valid}
Le document est valide!
{else}
Le document n'est pas valide...
{/if}