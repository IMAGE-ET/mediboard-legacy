<?php /* Smarty version 2.6.3, created on 2004-12-13 15:46:27
         compiled from vw_addedit_planning.tpl */ ?>
<!-- $Id$ -->

<?php echo '
<script language="javascript">
function checkForm() {
  var form = document.editFrm;
  var field = null;
  
  if (field = form.chir_id)
    if (field.value == 0) {
      alert("Chirurgien manquant");
      popChir();
      return false;
    }

  if (field = form.pat_id)
    if (field.value == 0) {
      alert("Patient manquant");
      popPat();
      return false;
    }

  if (field = form.CIM10_code)
    if (field.value.length == 0) {
      alert("Code CIM10 Manquant");
      popCode(\'cim10\');
      return false;
    }

  if (field = form.CCAM_code)
    if (field.value.length == 0) {
      alert("Code CCAM Manquant");
      popCode(\'ccam\');
      return false;
    }

/* Bug in IE
  if (form._hour_op.value == 0 && form._min_op.value == 0) {
    alert("Temps opératoire invalide");
    form.hour_op.focus();
    return false;
  }
*/

  if (field = form.plageop_id)
    if (field.value == 0) {
      alert("Intervention non planifiée");
      popPlage();
      return false;
    }

  if (field = form._date_rdv_adm)
    if (field.value.length == 0) {
      alert("Admission: date manquante");
      popCalendar(\'_rdv_adm\', \'_rdv_adm\');
      return false;
    }

  if (field = form._hour_adm)
    if (field.value.length == 0) {
      alert("Admission: heure manquante");
      field.focus();
      return false;
    }

  return true;
}

function popChir() {
  var url = \'./index.php?m=dPplanningOp\';
  url += \'&a=chir_selector\';
  url += \'&dialog=1\';
  
  window.open(url, \'Chirurgien\', \'left=50, top=50, height=250, width=400, resizable=yes\');
}

function setChir( key, val ){
  var f = document.editFrm;
   if (val != \'\') {
      f.chir_id.value = key;
      f._chir_name.value = val;
      window.chir_id = key;
      window._chir_name = val;
    }
}

function popPat() {
  var url = \'./index.php?m=dPplanningOp\';
  url += \'&a=pat_selector\';
  url += \'&dialog=1\';

  window.open(url, \'Patient\', \'left=50, top=50, width=400, height=250, resizable=yes\');
}

function setPat( key, val ) {
  var f = document.editFrm;

  if (val != \'\') {
    f.pat_id.value = key;
    f._pat_name.value = val;
    window.pat_id = key;
    window._pat_name = val;
  }
}

function popCode(type) {
  var url = \'./index.php?m=dPplanningOp\';
  url += \'&a=code_selector\';
  url += \'&dialog=1\';
  url += \'&chir=\'+ document.editFrm.chir_id.value;
  url += \'&type=\'+ type;

  window.open(url, \'CIM10\', \'left=50, top=50, width=600, height=500, resizable=yes\');
}

function setCode( key, type ){
  var f = document.editFrm;

  if (key != \'\') {
    if(type == \'ccam\') {
      f.CCAM_code.value = key;
      window.CCAM_code = key;
    }
    else {
      f.CIM10_code.value = key;
      window.CIM10_code = key;
    }
  }
}

function popPlage() {
  var url = \'./index.php?m=dPplanningOp\';
  url += \'&a=plage_selector\';
  url += \'&dialog=1\';
  url += \'&chir=\' + document.editFrm.chir_id.value;
  url += \'&hour=\' + document.editFrm._hour_op.value;
  url += \'&min=\' + document.editFrm._min_op.value;

  window.open(url, \'Plage\', \'left=50, top=50, width=400, height=250, resizable=yes\');
}

function setPlage( key, val ){
  var f = document.editFrm;

  if (key != \'\') {
    f.plageop_id.value = key
    f.date.value = val;
    window.plageop_id = key;
    window.date = val;
  }
}

function popProtocole() {
  var url = \'./index.php?m=dPplanningOp\';
  url += \'&a=vw_protocoles\';
  url += \'&dialog=1\';
  url += \'&chir_id=\'   + document.editFrm.chir_id.value;
  url += \'&CCAM_code=\' + document.editFrm.CCAM_code.value;

  window.open(url, \'Protocole\', \'top=200, left=250, width=600, height=400, scrollbars=yes, resizable=yes\' );
}

function setProtocole(
    chir_id,
    chir_last_name,
    chir_first_name,
    prot_CCAM_code,
    prot_hour_op,
    prot_min_op,
    prot_examen,
    prot_type_adm,
    prot_duree_hospi) {

  var f = document.editFrm;
  
  f.chir_id.value = chir_id;
  f._chir_name.value = "Dr " + chir_last_name + " " + chir_first_name;
  f.CCAM_code.value = prot_CCAM_code;
  f._hour_op.value = prot_hour_op;
  f._min_op.value = prot_min_op;
  f.examen.value = prot_examen;
  f.type_adm.value = prot_type_adm;
  f.duree_hospi.value = prot_duree_hospi;
}

var calendarField = \'\';
var calWin = null;
 
function popCalendar( field ) {
  calendarField = field;
  idate = eval( \'document.editFrm._date\' + field + \'.value\' );
  
  var url =  \'index.php?m=public\';
  url += \'&a=calendar\';
  url += \'&dialog=1\';
  url += \'&callback=setCalendar\';
  url += \'&date=\' + idate;
  
  window.open(url, \'calwin\', \'left=250, top=250, width=280, height=250, scrollbars=yes\' );
}

function setCalendar( idate, fdate ) {
  fld_date = eval( \'document.editFrm._date\' + calendarField );
  fld_fdate = eval( \'document.editFrm.\' + calendarField );
  fld_date.value = idate;
  fld_fdate.value = fdate;
}
  
function printForm() {
  // @todo Pourquoi ne pas seulement passer le operation_id? ca parait bcp moins régressif
  if (checkForm()) {
    url = \'./index.php?m=dPplanningOp\';
    url += \'&a=view_planning\';
    url += \'&dialog=1\';
    url += \'&chir_id=\'     + eval(\'document.editFrm.chir_id.value\'    );
    url += \'&pat_id=\'      + eval(\'document.editFrm.pat_id.value\'     );
    url += \'&CCAM_code=\'   + eval(\'document.editFrm.CCAM_code.value\'  );
    url += \'&cote=\'        + eval(\'document.editFrm.cote.value\'       );
    url += \'&hour_op=\'     + eval(\'document.editFrm._hour_op.value\'    );
    url += \'&min_op=\'      + eval(\'document.editFrm._min_op.value\'     );
    url += \'&date=\'        + eval(\'document.editFrm.date.value\'       );
    url += \'&info=\'        + eval(\'document.editFrm.info.value\'       );
    url += \'&rdv_anesth=\'  + eval(\'document.editFrm._rdv_anesth.value\' );
    url += \'&hour_anesth=\' + eval(\'document.editFrm._hour_anesth.value\');
    url += \'&min_anesth=\'  + eval(\'document.editFrm._min_anesth.value\' );
    url += \'&rdv_adm=\'     + eval(\'document.editFrm._rdv_adm.value\'    );
    url += \'&hour_adm=\'    + eval(\'document.editFrm._hour_adm.value\'   );
    url += \'&min_adm=\'     + eval(\'document.editFrm._min_adm.value\'    );
    url += \'&duree_hospi=\' + eval(\'document.editFrm.duree_hospi.value\');
    url += \'&type_adm=\'    + eval(\'document.editFrm.type_adm.value\'   );
    url += \'&chambre=\'     + eval(\'document.editFrm.chambre.value\'    ); 
 
    window.open( url, \'printAdm\', \'top=50,left=50, width=700, height=500, scrollbars=yes\' );
  }
}
</script>
'; ?>


<form name="editFrm" action="?m=<?php echo $this->_tpl_vars['m']; ?>
" method="post" onsubmit="return checkForm()">

<input type="hidden" name="dosql" value="do_planning_aed" />
<input type="hidden" name="del" value="0" />
<input type="hidden" name="operation_id" value="<?php echo $this->_tpl_vars['op']->operation_id; ?>
" />
<input type="hidden" name="rank" value="<?php echo $this->_tpl_vars['op']->rank; ?>
" />

<table class="main">
  <tr>
    <td>
  
      <table class="form">
        <tr><th class="category" colspan="3">Informations concernant l'opération</th></tr>

        <?php if (! $this->_tpl_vars['protocole']): ?>
        <tr>
          <td class="button" colspan="3"><input type="button" value="Choisir un protocole" onclick="popProtocole()" /></td>
        </tr>
        <?php endif; ?>
        
        <tr>
          <th class="mandatory"><input type="hidden" name="chir_id" value="<?php echo $this->_tpl_vars['chir']->user_id; ?>
" /><label for="editFrm_chir_id">Chirurgien:</label></th>
          <td class="readonly"><input type="text" name="_chir_name" size="30" value="<?php if (( $this->_tpl_vars['chir'] )): ?>Dr. <?php echo $this->_tpl_vars['chir']->user_last_name; ?>
 <?php echo $this->_tpl_vars['chir']->user_first_name;  endif; ?>" readonly="readonly" /></td>
          <td class="button"><input type="button" value="choisir un chirurgien" onclick="popChir()"></td>
        </tr>

        <?php if (! $this->_tpl_vars['protocole']): ?>
        <tr>
          <th class="mandatory"><input type="hidden" name="pat_id" value="<?php echo $this->_tpl_vars['pat']->patient_id; ?>
" /><label for="editFrm_chir_id">Patient:</label></th>
          <td class="readonly"><input type="text" name="_pat_name" size="30" value="<?php echo $this->_tpl_vars['pat']->nom; ?>
 <?php echo $this->_tpl_vars['pat']->prenom; ?>
" readonly="readonly" /></td>
          <td class="button"><input type="button" value="rechercher un patient" onclick="popPat()" /></td>
        </tr>
        
        <tr>
          <th class="mandatory"><label for="editFrm_CIM10_code">Diagnostic (CIM10):</label></th>
          <td><input type="text" name="CIM10_code" size="10" value="<?php echo $this->_tpl_vars['op']->CIM10_code; ?>
" /></td>
          <td class="button"><input type="button" value="selectionner un code" onclick="popCode('cim10')" /></td>
        </tr>
        <?php endif; ?>

        <tr>
          <th class="mandatory"><label for="editFrm_CCAM_code">Acte médical (CCAM):</label></th>
          <td><input type="text" name="CCAM_code" size="10" value="<?php echo $this->_tpl_vars['op']->CCAM_code; ?>
" /></td>
          <td class="button"><input type="button" value="selectionner un code" onclick="popCode('ccam')"/></td>
        </tr>

        <?php if (! $this->_tpl_vars['protocole']): ?>
        <tr>
          <th>Coté:</th>
          <td colspan="2">
            <select name="cote">
              <option <?php if (! $this->_tpl_vars['op'] && $this->_tpl_vars['op']->cote == 'total'): ?> selected="selected" <?php endif; ?> >total</option>
              <option <?php if ($this->_tpl_vars['op']->cote == 'droit'): ?> selected="selected" <?php endif; ?> >droit     </option>
              <option <?php if ($this->_tpl_vars['op']->cote == 'gauche'): ?> selected="selected" <?php endif; ?> >gauche    </option>
              <option <?php if ($this->_tpl_vars['op']->cote == 'bilatéral'): ?> selected="selected" <?php endif; ?> >bilatéral</option>
            </select>
          </td>
        </tr>
        <?php endif; ?>

        <tr>
          <th class="mandatory">Temps opératoire:</th>
          <td colspan="2">
            <select name="_hour_op">
            <?php if (count($_from = (array)$this->_tpl_vars['hours'])):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['hour']):
?>
              <option <?php if (( ! $this->_tpl_vars['op'] && $this->_tpl_vars['key'] == 1 ) || $this->_tpl_vars['op']->_hour_op == $this->_tpl_vars['key']): ?> selected="selected" <?php endif; ?>><?php echo $this->_tpl_vars['key']; ?>
</option>
            <?php endforeach; unset($_from); endif; ?>
            </select>
            :
            <select name="_min_op">
            <?php if (count($_from = (array)$this->_tpl_vars['mins'])):
    foreach ($_from as $this->_tpl_vars['min']):
?>
              <option <?php if (( ! $this->_tpl_vars['op'] && $this->_tpl_vars['min'] == 0 ) || $this->_tpl_vars['op']->_min_op == $this->_tpl_vars['min']): ?> selected="selected" <?php endif; ?>><?php echo $this->_tpl_vars['min']; ?>
</option>
            <?php endforeach; unset($_from); endif; ?>
            </select>
          </td>
        </tr>

        <?php if (! $this->_tpl_vars['protocole']): ?>
        <tr>
          <th class="mandatory"><input type="hidden" name="plageop_id" value="<?php echo $this->_tpl_vars['plage']->id; ?>
" />Date de l'intervention:</th>
          <td class="readonly"><input type="text" name="date" readonly="readonly" size="10" value="<?php echo $this->_tpl_vars['plage']->_date; ?>
" /></td>
          <td class="button"><input type="button" value="choisir une date" onclick="popPlage()" /></td>
        </tr>
        <?php endif; ?>
        
        <tr>
          <th>Examens complémentaires:</th>
          <td colspan="2"><textarea name="examen" rows="3"><?php echo $this->_tpl_vars['op']->examen; ?>
</textarea></td>
        </tr>

        <?php if (! $this->_tpl_vars['protocole']): ?>
        <tr>
          <th>Matériel à prévoir:</th>
          <td colspan="2"><textarea name="materiel" rows="3"><?php echo $this->_tpl_vars['op']->materiel; ?>
</textarea></td>
        </tr>

        <tr>
          <th>Information du patient:</th>
          <td colspan="2">
            <input name="info" value="o" type="radio" <?php if ($this->_tpl_vars['op']->info == 'o'): ?> checked="checked" <?php endif; ?>/>Oui
            <input name="info" value="n" type="radio" <?php if (! $this->_tpl_vars['op'] || $this->_tpl_vars['op']->info == 'n'): ?> checked="checked" <?php endif; ?>/>Non
          </td>
        </tr>
        <?php endif; ?>

      </table>

    </td>
    <td>

      <table class="form">
        <?php if (! $this->_tpl_vars['protocole']): ?>
        <tr><th class="category" colspan="3">RDV d'anesthésie</th></tr>

        <tr>
          <th>Date:</th>
          <td class="readonly">
            <input type="hidden" name="_date_rdv_anesth" value="<?php echo $this->_tpl_vars['op']->_date_rdv_anesth; ?>
" />
            <input type="text" name="_rdv_anesth" value="<?php echo $this->_tpl_vars['op']->_rdv_anesth; ?>
" readonly="readonly" />
            <a href="#" onClick="popCalendar('_rdv_anesth', '_rdv_anesth');">
              <img src="./images/calendar.gif" width="24" height="12" alt="Choisir une date" />
            </a>
          </td>
        </tr>

        <tr>
          <th>Heure:</th>
          <td>
            <select name="_hour_anesth">
            <?php if (count($_from = (array)$this->_tpl_vars['hours'])):
    foreach ($_from as $this->_tpl_vars['hour']):
?>
              <option <?php if ($this->_tpl_vars['op']->_hour_anesth == $this->_tpl_vars['hour']): ?> selected="selected" <?php endif; ?>><?php echo $this->_tpl_vars['hour']; ?>
</option>
            <?php endforeach; unset($_from); endif; ?>
            </select>
            :
            <select name="_min_anesth">
            <?php if (count($_from = (array)$this->_tpl_vars['mins'])):
    foreach ($_from as $this->_tpl_vars['min']):
?>
              <option <?php if ($this->_tpl_vars['op']->_min_anesth == $this->_tpl_vars['min']): ?> selected="selected" <?php endif; ?>><?php echo $this->_tpl_vars['min']; ?>
</option>
            <?php endforeach; unset($_from); endif; ?>
            </select>
          </td>
        </tr>
        <?php endif; ?>
        
        <tr><th class="category" colspan="3">Admission</th></tr>

        <?php if (! $this->_tpl_vars['protocole']): ?>
        <tr>
          <th class="mandatory">Date:</th>
          <td class="readonly">
            <input type="hidden" name="_date_rdv_adm" value="<?php echo $this->_tpl_vars['op']->_date_rdv_adm; ?>
" />
            <input type="text" name="_rdv_adm" value="<?php echo $this->_tpl_vars['op']->_rdv_adm; ?>
" readonly="readonly" />
            <a href="#" onClick="popCalendar( '_rdv_adm', '_rdv_adm');">
              <img src="./images/calendar.gif" width="24" height="12" alt="Choisir une date" />
            </a>
          </td>
        </tr>

        <tr>
          <th class="mandatory">Heure:</th>
          <td>
            <select name="_hour_adm">
            <?php if (count($_from = (array)$this->_tpl_vars['hours'])):
    foreach ($_from as $this->_tpl_vars['hour']):
?>
              <option <?php if ($this->_tpl_vars['op']->_hour_adm == $this->_tpl_vars['hour']): ?> selected="selected" <?php endif; ?>><?php echo $this->_tpl_vars['hour']; ?>
</option>
            <?php endforeach; unset($_from); endif; ?>
            </select>
            :
            <select name="_min_adm">
            <?php if (count($_from = (array)$this->_tpl_vars['mins'])):
    foreach ($_from as $this->_tpl_vars['min']):
?>
              <option <?php if ($this->_tpl_vars['op']->_min_adm == $this->_tpl_vars['min']): ?> selected="selected" <?php endif; ?>><?php echo $this->_tpl_vars['min']; ?>
</option>
            <?php endforeach; unset($_from); endif; ?>
            </select>
          </td>
        </tr>
        <?php endif; ?>

        <tr>
          <th>Durée d'hospitalisation:</th>
          <td><input type"text" name="duree_hospi" size="1" value="<?php echo $this->_tpl_vars['op']->duree_hospi; ?>
"> jours</td>
        </tr>
        <tr>
          <th>Admission en:</th>
          <td>
            <input name="type_adm" value="comp" type="radio" <?php if (! $this->_tpl_vars['op'] || $this->_tpl_vars['op']->type_adm == 'comp'): ?> checked="checked" <?php endif; ?> />
            <label for="editFrm_type_adm_comp">Hospitalisation complète</label><br />
            <input name="type_adm" value="ambu" type="radio" <?php if ($this->_tpl_vars['op']->type_adm == 'ambu'): ?> checked="checked" <?php endif; ?> />
            <label for="editFrm_type_adm_ambu">Ambulatoire</label><br />
            <input name="type_adm" value="exte" type="radio" <?php if ($this->_tpl_vars['op']->type_adm == 'exte'): ?> checked="checked" <?php endif; ?> />
            <label for="editFrm_type_adm_exte">Externe</label><br />
          </td>
        </tr>
        
        <?php if (! $this->_tpl_vars['protocole']): ?>
        <tr>
          <th>Chambre particulière:</th>
          <td>
            <input name="chambre" value="o" type="radio" <?php if (! $this->_tpl_vars['op'] || $this->_tpl_vars['op']->chambre == 'o'): ?> checked="checked" <?php endif; ?>/>
            <label for="editFrm_chambre_o">Oui</label>
            <input name="chambre" value="n" type="radio" <?php if ($this->_tpl_vars['op']->chambre == 'n'): ?> checked="checked" <?php endif; ?>/>
            <label for="editFrm_chambre_n">Non</label>
          </td>
        </tr>
        <tr><th class="category" colspan="3">Autre</th></tr>
        <tr>
          <th>Risque ATNC:</th>
          <td>
            <input name="ATNC" value="o" type="radio" <?php if ($this->_tpl_vars['op']->ATNC == 'o'): ?> checked="checked" <?php endif; ?> />
            <label for="editFrm_ATNC_o">Oui</label>
            <input name="ATNC" value="n" type="radio" <?php if (! $this->_tpl_vars['op'] || $this->_tpl_vars['op']->ATNC == 'n'): ?> checked="checked" <?php endif; ?> />
            <label for="editFrm_ATNC_n">Non</label>
          </td>
        </tr>
        <tr>
          <th>Remarques:</th>
          <td><textarea name="rques" rows="3"><?php echo $this->_tpl_vars['op']->rques; ?>
</textarea></td>
        </tr>
        <?php endif; ?>

      </table>
    
    </td>
  </tr>

  <tr>
    <td colspan="2">

      <table class="form">
        <tr>
          <td class="button">
          <?php if ($this->_tpl_vars['op']): ?>
            <input type="reset" value="Réinitialiser" />
            <input type="submit" value="Modifier" />
            <input type="button" value="Supprimer" onclick="<?php echo 'if (confirm(\'Veuillez confirmer la suppression\')) {this.form.del.value = 1; this.form.submit();}'; ?>
"/>
          <?php else: ?>
            <input type="submit" value="Créer" />
          <?php endif; ?>
            <input type="button" value="Imprimer" onClick="printForm()" />
          </td>
        </tr>
      </table>
    
    </td>
  </tr>

</table>

</form>