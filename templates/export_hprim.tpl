<h1>Essai de validation d'un document XML</h1>

<h2>Utilisation de DOM</h2>

<h3>XML Dump</h3>

<p>Click to view the <a href="{$documentpath}">XMI File</a>.</p>

<h3>XML Schema Validation</h3>

<p>Click to view the <a href="{$schemapath}">Schema File</a>.</p>

{if $doc_valid}
Le document est valide!
{else}
Le document n'est pas valide...
{/if}