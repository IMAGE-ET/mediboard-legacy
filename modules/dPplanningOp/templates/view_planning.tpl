<table class="form" id="admission">
  <tr><th class="title" colspan="2">
  <a href="javascript:window.print()">Fiche d'admission</a></th></tr>
  <tr>
    <td class="info" colspan="2">
    (Pri�re de vous munir pour la consultation d'anesth�sie de la photocopie
     de vos cartes de s�curit� sociale, de mutuelle et du r�sultat de votre
     bilan sanguin et la liste des m�dicaments que vous prennez)<br />
     Pour tout renseignement, t�l�phonez au 05 46 00 40 40
    </td>
  </tr>
  
  <tr>
    <th>Date :</th>
    <td>{$today|date_format:"%A %d/%m/%Y"}</td>
  </tr>
  
  <tr>
    <th>Chirurgien :</th>
    <td>Dr. {$operation->_ref_chir->_view}</td>
  </tr>
  
  <tr>
    <th class="category" colspan="2">Renseignements concernant le patient</th>
  </tr>
  
  {assign var="patient" value=$operation->_ref_pat}
  <tr>
    <th>Nom / Pr�nom :</th>
    <td>{$patient->_view}</td>
  </tr>
  
  <tr>
    <th>Date de naissance / Sexe :</th>
    <td>
      n�(e) le {$patient->_naissance}
      de sexe 
      {if $patient->sexe == "m"}masculin{else}f�minin{/if}
    </td>
  </tr>
  
  <tr>
    <th>Incapable majeur :</th>
    <td>{if $patient->incapable_majeur == "o"}oui{else}non{/if}</td>
  </tr>

  <tr>
    <th>T�l�phone :</th>
    <td>{$patient->_tel1} {$patient->_tel2} {$patient->_tel3} {$patient->_tel4} {$patient->_tel5}</td>
  </tr>

  <tr>
    <th>Medecin traitant :</th>
    <td>{$patient->_ref_medecin_traitant->_view}</td>
  </tr>
  
  <tr>
    <th>Adresse :</th>
    <td>
      {$patient->adresse} &mdash;
      {$patient->cp} {$patient->ville}
    </td>
  </tr>
  
  <tr>
    <th class="category" colspan="2">Renseignements relatifs � l'hospitalisation</th>
  </tr>
  
  <tr>
    <th>Admission :</th>
    <td>
      le {$operation->date_adm|date_format:"%A %d/%m/%Y"} 
      � {$operation->time_adm|date_format:"%Hh%M"}
    </td>
  </tr>
  
  <tr>
    <th>Hospitalisation :</th>
    <td>
      {if $operation->type_adm == "comp"}Compl�te{/if}
      {if $operation->type_adm == "ambu"}Ambulatoire{/if}
      {if $operation->type_adm == "exte"}Externe{/if}
    </td>
  </tr>
  
  <tr>
    <th>Chambre particuli�re :</th>
    <td>{if $operation->chambre == "o"}oui{else}non{/if}</td>
  </tr>
 
  <tr>
    <th>Date d'intervention :</th>
    <td>le {$operation->_ref_plageop->date|date_format:"%A %d/%m/%Y"}</td>
  </tr>

  <tr>
    <th>Actes m�dicaux: </th>
    <td class="text">
      {foreach from=$operation->_ext_codes_ccam item=ext_code_ccam}
      {$ext_code_ccam->libelleLong}<br />
      {/foreach}
    </td>
  </tr>
  
  <tr>
    <th>C�t� :</th>
    <td>{$operation->cote}</td>
  </tr>

  <tr>
    <th>Dur�e pr�vue d'hospitalisation :</th>
    <td>{$operation->duree_hospi} jours</td>
  </tr>
  
  <tr><th class="category" colspan="2">Rendez vous d'anesth�sie</th></tr>
  
  <tr>
    <td class="text" colspan="2">
      Veuillez prendre rendez-vous avec le cabinet d'anesth�sistes <strong>imp�rativement</strong>
      avant votre intervention. Pour cela, t�l�phonez au 05 46 00 77 08
    </td>
  <tr>
  
  <tr><td class="info" colspan="2"><b>Pour votre hospitalisation, pri�re de vous munir de :</b>
  <ul>
    <li>
      Carte Vitale ou, � d�faut, attestation de s�curit� sociale, 
      carte de mutuelle accompagn�e de la prise en charge le cas �ch�ant.
    </li>
    <li>Tous examens en votre possession (analyse, radio, carte de groupe sanguin...).</li>
    <li>Pr�voir linge et n�cessaire de toilette.</li>
    <li>Vos m�dicaments �ventuellement</li>
  </ul>
  </td></tr>
</table>