<h1>Proc�dure</h1>
<ol>
  <li>Cr�er les tables qui accueilleront les donn�es : fichier tables_import.sql dans le zip</li>
  <li>Placer les fichiers TXT au format CSV contenus dans le zip dans la racine du module dPinterop.</li>
  <li>
    Importer le CSV dans MySQL en cr�ant un table telle quelle en suivant chaque lien :
    <ul>
      <li><a name="patients" onclick="popup(400, 400, 'index.php?m=dPinterop&dialog=1&a=orl_patients', 'patients')">Import des patients</a></li>
      <li><a name="praticiens" onclick="popup(400, 400, 'index.php?m=dPinterop&dialog=1&a=orl_praticiens', 'praticiens')">Import des praticiens</a></li>
      <li><a name="medecins" onclick="popup(400, 400, 'index.php?m=dPinterop&dialog=1&a=orl_medecins', 'medecins')">Import des m�decins traitants</a></li>
      <li><a name="consult1" onclick="popup(400, 400, 'index.php?m=dPinterop&dialog=1&a=orl_consult1', 'consult1')">Import des consultations (horaire)</a></li>
      <li><a name="consult2" onclick="popup(400, 400, 'index.php?m=dPinterop&dialog=1&a=orl_consult2', 'consult2')">Import des consultations (examen)</a></li>
    </ul>
  </li>
  <li>Ajouter un champ: mb_id, indiquant la cl� de l'objet correspondant dans la base Mediboard</li>
  <li>Cr�er l'objet dans la base mediboard ou r�cup�rer son ID:
    <ul>
      <li>S'il n'existe pas le cr�er avec les champs import�s</li>
      <li>s'il existe proposer une iterventio humaine ou compl�ter les champs NULL</li>
    </ul>

  <li>Inscrire l'ID dans le champ mb_id de la table d'import</li>
</ol>