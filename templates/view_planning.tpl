<table class="form">
  <tr><th class="category" colspan="3"><a href="javascript:window.print()">Fiche d'admission</a></th></tr>
  <tr>
    <th class="category" style="width: 33%">Patient</th>
    <th class="category" style="width: 33%">Type d'intervention</th>
    <th class="category" style="width: 33%">Chirurgien</th>
  </tr>

  <tr>
    <td>{$patient.nom} {$patient.prenom}</td>
    <td class="text" rowspan="2">{$CCAM}</td>
    <td>Dr. {$chirurgien.firstname} {$chirurgien.lastname}</td>
  </tr>

  <tr>
    <td>{$patient.adresse}<br />{$patient.CP} {$patient.ville}</td>
    <td>{$chirurgien.specialite}</td>
  </tr>

  <tr>
    <th class="category">RDV d'anesthésie</th>
    <th class="category">Admission</th>
    <th class="category">Intervention</th>
  </tr>
  
  <tr>
    <td>Rendez-vous le {$anesthesie.date}</td>
    <td>Rendez-vous le {$admission.date}</td>
    <td rowspan=3>Planifié le {$operation.date}</td>
  </tr>

  <tr>
    <td rowspan="2">à {$anesthesie.heure}</td>
    <td>à {$admission.heure}</td>
  </tr>

  <tr>
    <td>hospitalisation de {$admission.duree} jours en {$admission.type}</td>
  </tr>
</table>