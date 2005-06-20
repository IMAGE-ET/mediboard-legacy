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
      <li><a name="rdv" onclick="popup(400, 400, 'index.php?m=dPinterop&dialog=1&a=orl_rdv', 'rdv')">Import RDV � venir</a></li>
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
      <li><a name="praticiens" onclick="popup(400, 400, 'index.php?m=dPinterop&dialog=1&a=put_praticiens', 'praticiens')">Envoie des praticiens vers Mediboard</a></li>
      <li><a name="medecins" onclick="popup(400, 400, 'index.php?m=dPinterop&dialog=1&a=put_medecins', 'medecins')">Envoie des medecins vers Mediboard</a></li>
      <li><a name="patients" onclick="popup(400, 400, 'index.php?m=dPinterop&dialog=1&a=put_patients', 'patients')">Envoie des patients vers Mediboard</a></li>
      <li><a name="consult" onclick="popup(400, 400, 'index.php?m=dPinterop&dialog=1&a=put_consult', 'consult')">Envoie des consultations vers Mediboard</a></li>
      <li><a name="rdv" onclick="popup(400, 400, 'index.php?m=dPinterop&dialog=1&a=put_rdv', 'rdv')">Envoie des rdv � venir vers Mediboard</a></li>
    </ul>
  </li>

  <li>Inscrire l'ID dans le champ mb_id de la table d'import</li>
</ol>