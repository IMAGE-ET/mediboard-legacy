<?php /* Smarty version 2.6.3, created on 2004-12-13 18:49:41
         compiled from vw_dtl_admission.tpl */ ?>
<table class="main">
  <tr>
  <td>
  
    <table class="form">
      <tr>
        <th class="category" colspan="2">Identité</th>
        <th class="category" colspan="2">Information médicales</th>
      </tr>

      <tr>
        <th>Nom:</th>
        <td><?php echo $this->_tpl_vars['op']['pat_lastname']; ?>
</td>
        <th>Incapable majeur:</th>
        <td>
          <?php if ($this->_tpl_vars['op']['incapable_majeur'] == 'o'): ?> oui <?php endif; ?>
          <?php if ($this->_tpl_vars['op']['incapable_majeur'] == 'n'): ?> non <?php endif; ?>
        </td>
      </tr>
      
      <tr>
        <th>Prénom:</th>
        <td><?php echo $this->_tpl_vars['op']['pat_firstname']; ?>
</td>
        <th>ATNC:</th>
        <td>
          <?php if ($this->_tpl_vars['op']['ATNC'] == 'o'): ?> oui <?php endif; ?>
          <?php if ($this->_tpl_vars['op']['ATNC'] == 'n'): ?> non <?php endif; ?>
      </tr>
      
      <tr>
        <th>Date de naissance:</th>
        <td><?php echo $this->_tpl_vars['op']['dateFormed']; ?>
</td>
      </tr>
      
      <tr>
        <th>Sexe:</th>
        <td>
          <?php if ($this->_tpl_vars['op']['sexe'] == 'm'): ?> masculin <?php endif; ?>
          <?php if ($this->_tpl_vars['op']['sexe'] == 'f'): ?> féminin  <?php endif; ?> 
        </td>
      </tr>
      
      <tr>
        <th class="category" colspan="2">Coordonnées</th>
        <th class="category" colspan="2">Information administratives</th>
      </tr>
      
      <tr>
        <th>Adresse:</th>
        <td><?php echo $this->_tpl_vars['op']['adresse']; ?>
</td>
        <th>Numéro d'assuré social:</th>
        <td><?php echo $this->_tpl_vars['op']['matricule']; ?>
</td>
      </tr>
      
      <tr>
        <th>Ville:</th>
        <td><?php echo $this->_tpl_vars['op']['ville']; ?>
</td>
        <th>Code administratif:</th>
        <td><?php echo $this->_tpl_vars['op']['SHS']; ?>
</td>
      </tr>
      
      <tr>
        <th>Code Postal:</th>
        <td><?php echo $this->_tpl_vars['op']['cp']; ?>
</td>
      </tr>
      
      <tr>
        <th>Téléphone:</th>
        <td><?php echo $this->_tpl_vars['op']['tel']; ?>
</td>
      </tr>

    </table>
  
  </td>
    <td>
	
      <table class="form">
        <tr><th class="category" colspan="2">Informations concernant l'opération</th></tr>
        <tr>
		      <th>Chirurgien:</th>
          <td><?php echo $this->_tpl_vars['op']['chir_name']; ?>
</td>
        </tr>
        <tr>
          <th>Diagnostic (CIM10):</th>
          <td><?php echo $this->_tpl_vars['op']['CIM10_code']; ?>
</td>
        </tr>
        <tr>
          <th>Code CCAM:</th>
          <td><?php echo $this->_tpl_vars['op']['CCAM_code']; ?>
</td>
        </tr>
        <tr>
          <th>Temps opératoire:</th>
          <td colspan="2"><?php echo $this->_tpl_vars['op']['hour_op']; ?>
:<?php echo $this->_tpl_vars['op']['min_op']; ?>
</td>
        </tr>
        <tr>
          <th>Date de l'intervention:</th>
          <td><?php echo $this->_tpl_vars['op']['date_op']; ?>
</td>
        </tr>
        <tr>
          <th class="text" colspan="2">Examens complémentaires:</th>
        </tr>
        <tr>
          <td class="text" colspan="2"><?php echo $this->_tpl_vars['op']['examen']; ?>
</td>
        </tr>
        <tr>
          <th class="text" colspan="2">Matériel à prévoir:</th>
        </tr>
        <tr>
          <td class="text" colspan="2"><?php echo $this->_tpl_vars['op']['materiel']; ?>
</td>
        </tr>
        <tr>
          <th>Information du patient:</th>
          <td  colspan="2">
            <?php if ($this->_tpl_vars['op']['info'] == 'o'): ?> Oui <?php endif; ?>
            <?php if ($this->_tpl_vars['op']['info'] == 'n'): ?> Non <?php endif; ?>
          </td>
        </tr>
      </table>

    </td>
    <td>

      <table class="form">
        <tr><th class="category" colspan="3">RDV d'anesthésie</th></tr>
        <tr>
          <th>Date:</th>
          <td><?php echo $this->_tpl_vars['op']['rdv_anesth']; ?>
</td>
        </tr>
        <tr>
          <th>Heure:</th>
          <td><?php echo $this->_tpl_vars['op']['hour_anesth']; ?>
:<?php echo $this->_tpl_vars['op']['min_anesth']; ?>
</td>
        </tr>
        <tr><th class="category" colspan="3">Admission</th></tr>
        <tr>
          <th>Date:</th>
          <td><?php echo $this->_tpl_vars['op']['rdv_adm']; ?>
</td>
        </tr>
        <tr>
          <th>Heure:</th>
          <td><?php echo $this->_tpl_vars['op']['hour_adm']; ?>
:<?php echo $this->_tpl_vars['op']['min_adm']; ?>
</td>
        </tr>
        <tr>
          <th>Durée d'hospitalisation:</th>
          <td><?php echo $this->_tpl_vars['op']['duree_hospi']; ?>
 jours</td>
        </tr>
        <tr>
          <th>Admission en:</th>
          <td>
            <?php if ($this->_tpl_vars['op']['type_adm'] == 'comp'): ?> Hospitalisation complète <?php endif; ?>
            <?php if ($this->_tpl_vars['op']['type_adm'] == 'ambu'): ?> Ambulatoire<?php endif; ?>
            <?php if ($this->_tpl_vars['op']['type_adm'] == 'exte'): ?> Externe<?php endif; ?>
          </td>
        </tr>
        <tr>
          <th>Chambre particulière:</th>
          <td>
            <?php if ($this->_tpl_vars['op']['chambre'] == 'o'): ?> Oui <?php endif; ?>
            <?php if ($this->_tpl_vars['op']['chambre'] == 'n'): ?> Non <?php endif; ?>
          </td>
        </tr>
        <tr><th class="category" colspan="3">Autre</th></tr>
        <tr>
          <th>Risque ATNC:</th>
          <td>
            <?php if ($this->_tpl_vars['op']['ATNC'] == 'o'): ?> Oui <?php endif; ?>
            <?php if ($this->_tpl_vars['op']['ATNC'] == 'n'): ?> Non <?php endif; ?>
          </td>
        </tr>

        <tr>
          <th class="text" colspan="2">Remarques:</th>
        </tr>
        
        <tr>
          <td class="text" colspan="2"><?php echo $this->_tpl_vars['op']['rques']; ?>
</td>
        </tr>

      </table>
    
    </td>
  </tr>

</table>

</form>