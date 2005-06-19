<h1>Procédure</h1>
<p>Pour chaque fichier de données:</p>
<ol>
  <li>Importer le CSV dans MySQL en créant un table telle quelle</li>
  <li>Ajouter un champ: mb_id, indiquant la clé de l'objet correspondant dans la base Mediboard</li>
  <li>Créer l'objet dans la base mediboard ou récupérer son ID:
    <ul>
      <li>S'il n'existe pas le créer avec les champs importés</li>
      <li>s'il existe proposer une iterventio humaine ou compléter les champs NULL</li>
    </ul>

  <li>Inscrire l'ID dans le champ mb_id de la table d'import</li>
</ol>

<p>Bien sûr il faut commencer par la table maîtresse, celle qui ne possède pas de clé étrangère vers d'autres table</p>
