<script language="JavaScript" type="text/javascript">
{literal}

function doAction(sAction) {
  url = "index.php?m=dPinterop&dialog=1";
  url += "&a=" + sAction;
  
  popup(400, 400, url, sAction);
}  

{/literal}
</script>

<h1>Proc�dure</h1>
<ol>
  <li>Cr�er les tables qui accueilleront les donn�es : fichier tables_import.sql dans le zip</li>
  <li>Placer les fichiers TXT au format CSV contenus dans le zip dans la racine du module dPinterop.</li>
  <li>
    Importer le CSV dans MySQL en cr�ant un table telle quelle en suivant chaque lien :
    <ul>
      <li><a href="javascript:doAction('orl_patients')">Import des patients</a></li>
      <li><a href="javascript:doAction('orl_praticiens')">Import des praticiens</a></li>
      <li><a href="javascript:doAction('orl_medecins')">Import des m�decins traitants</a></li>
      <li><a href="javascript:doAction('orl_consult1')">Import des consultations (horaire)</a></li>
      <li><a href="javascript:doAction('orl_consult2')">Import des consultations (examen)</a></li>
      <li><a href="javascript:doAction('orl_rdv');">Import RDV � venir</a></li>
    </ul>
  </li>
  <li>Ajouter un champ mb_id, indiquant la cl� de l'objet correspondant dans la base Mediboard : utiliser le fichier mb_id.sql dans le zip</li>
  <li>Cr�er l'objet dans la base mediboard ou r�cup�rer son ID:
    <ul>
      <li>S'il n'existe pas le cr�er avec les champs import�s</li>
      <li>s'il existe proposer une itervention humaine ou compl�ter les champs NULL</li>
    </ul>
  <li>
    Voici les scripts � lancer :
    <ul>
      <li><a href="javascript:doAction('put_praticiens')">Envoie des praticiens vers Mediboard</a></li>
      <li><a href="javascript:doAction('put_praticiens')">Envoie des medecins vers Mediboard</a></li>
      <li><a href="javascript:doAction('put_patients')">Envoie des patients vers Mediboard</a></li>
      <li><a href="javascript:doAction('put_consult')">Envoie des consultations vers Mediboard</a></li>
      <li><a href="javascript:doAction('put_rdv')">Envoie des rdv � venir vers Mediboard</a></li>
    </ul>
  </li>

  <li>Inscrire l'ID dans le champ mb_id de la table d'import</li>
  <li>
    Ajout des fichiers li�s :
    <ul>
      <li>Placer le dossier doc_recus et le fichier doc_recus.txt dans le module dPinterop</li>
      <li><a href="javascript:doAction('orl_files')">Import des fichiers</a></li>
      <li><a href="javascript:doAction('put_files')">Envoie des fichiers vers Mediboard</a></li>
      <li>Les fichiers ainsi envoy�s iront se placer (le cas �ch�ant) dans la derni�re consultation du patient li�</li>
      <li><a href="javascript:doAction('put_correct_files')">Petite correction</a> (les fichiers allaient dans n'importe quelle consultation et pas eulement chez les ORL)</li>
    </ul>
  </li>
  
</ol>