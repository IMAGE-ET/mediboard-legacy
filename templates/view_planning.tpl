<table class="form" id="admission">
  <tr><th class="title" colspan="2"><a href="javascript:window.print()">Fiche d'admission</a></th></tr>
  <tr><td class="info" colspan="2">(Pri�re de vous munir pour la consultation d'anesth�sie de la photocopie
                       de vos cartes de s�curit� sociale, de mutuelle et du r�sultat de votre
                       bilan sanguin et la liste des m�dicaments que vous prennez)<br />
                       Pour tout renseignement, t�l�phonez au 05 46 00 40 40</td></tr>
  
  <tr><th>Date: </th><td>{$adm.today}</td></tr>
  <tr><th>Chirurgien: </th><td>Dr. {$adm.chirName}</td></tr>
  
  <tr><th class="category" colspan="2">Renseignements concernant le patient</th></tr>
  
  <tr><th>Nom / Prenom: </th><td>{$adm.patName} {$adm.patFirst}</td></tr>
  <tr><th>Date de naissance / Sexe: </th><td>n�(e) le {$adm.naissance} de sexe {$adm.sexe}</td></tr>
  <tr><th>Incapable majeur: </th><td>{$adm.inc}</td></tr>
  <tr><th>Telephone: </th><td>{$adm.tel}</td></tr>
  <tr><th>Medecin traitant: </th><td>{$adm.medTrait}</td></tr>
  <tr><th>Adresse: </th><td>{$adm.adresse} - {$adm.CP} {$adm.ville}</td></tr>
  
  <tr><th class="category" colspan="2">Renseignements relatifs � l'hospitalisation</th></tr>
  
  <tr><th>Admission: </th><td>le {$adm.dateAdm} � {$adm.hourAdm}</td></tr>
  <tr><th>Hospitalisation: </th><td>{$adm.hospi}</td></tr>
  <tr><th>Chambre seule: </th><td>{$adm.chambre}</td></tr>
  <tr><th>Date d'intervention: </th><td>{$adm.dateOp}</td></tr>
  <tr><th>Diagnostic: </th><td class="text">{$adm.CCAM}</td></tr>
  <tr><th>Cot�: </th><td>{$adm.cote}</td></tr>
  <tr><th>Dur�e pr�vue d'hospitalisation: </th><td>{$adm.dureeHospi} jours</td></tr>
  
  <tr><th class="category" colspan="2">Rendez vous d'anesth�sie</th></tr>
  
  <tr><th>Date: </th><td class="text"><!--le {$adm.dateAnesth} � {$adm.hourAnesth}-->
                                      pour prendre rendez-vous, veuillez t�l�phoner au<br />05 46 00 77 08</td><tr>
  
  <tr><td class="info" colspan="2"><b>Pour votre hospitalisation, pri�re de vous munir de :</b>
                                   <ul>
                                     <li>Carte Vitale ou, � d�faut, attestation de s�curit�
                                         sociale, carte de mutuelle accompagn�e de la prise
                                         en charge le cas �ch�ant.</li>
                                     <li>Tous examens en votre possession (analyse, radio,
                                         carte de groupe sanguin...).</li>
                                      <li>Pr�voir linge et n�cessaire de toilette.</li>
                                      <li>Vos m�dicaments �ventuellement</li>
                                    </ul>
  </td></tr>
</table>