<h1>Proc�dure</h1>
<p>Pour chaque fichier de donn�es:</p>
<ol>
  <li>Importer le CSV dans MySQL en cr�ant un table telle quelle</li>
  <li>Ajouter un champ: mb_id, indiquant la cl� de l'objet correspondant dans la base Mediboard</li>
  <li>Cr�er l'objet dans la base mediboard ou r�cup�rer son ID:
    <ul>
      <li>S'il n'existe pas le cr�er avec les champs import�s</li>
      <li>s'il existe proposer une iterventio humaine ou compl�ter les champs NULL</li>
    </ul>

  <li>Inscrire l'ID dans le champ mb_id de la table d'import</li>
</ol>

<p>Bien s�r il faut commencer par la table ma�tresse, celle qui ne poss�de pas de cl� �trang�re vers d'autres table</p>
