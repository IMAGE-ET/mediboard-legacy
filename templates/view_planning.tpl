<table class="main">
  <tr><th colspan=3><a href="javascript:window.print()">Fiche d'admission</a></th></tr>
  <tr>
    <th width="33%">Patient</th>
    <th width="34%">Type d'intervention</th>
    <th width="33%">Chirurgien</th>
  </tr>
  <tr>
    <td>{$patient.nom} {$patient.prenom}</td>
	<td rowspan=2>{$CCAM}</td>
	<td>Dr. {$chirurgien.firstname} {$chirurgien.lastname}</td>
  </tr>
  <tr>
    <td>
	  {$patient.adresse}
	  <br />
	  {$patient.CP}
	  {$patient.ville}
	</td>
	<td>{$chirurgien.specialite}</td>
  </tr>
  <tr>
    <th>RDV d'anesth�sie</th>
	<th>Admission</th>
	<th>Intervention</th>
  </tr>
  <tr>
    <td>Rendez-vous le {$anesthesie.date}</td>
	<td>Rendez-vous le {$admission.date}</td>
	<td rowspan=3>Planifi� le {$operation.date}</td>
  </tr>
  <tr>
    <td rowspan=2>� {$anesthesie.heure}</td>
	<td>� {$admission.heure}</td>
  </tr>
  <tr>
	<td>hospitalisation de {$admission.duree} jours en {$admission.type}</td>
  </tr>
</table>