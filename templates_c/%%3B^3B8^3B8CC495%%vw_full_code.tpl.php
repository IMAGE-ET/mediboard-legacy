<?php /* Smarty version 2.6.3, created on 2004-12-13 15:12:07
         compiled from vw_full_code.tpl */ ?>
<table class="fullCode">
  <tr>
    <th colspan="2"><h1>&ldquo;<?php echo $this->_tpl_vars['master']['libelle']; ?>
&rdquo;</h1></th>
  </tr>
  
  <tr>
    <td class="leftPane">
      <form action="index.php" target="_self" name="selection" method="get" encoding="">
      <input type="hidden" name="m" value="dPcim10">
      <input type="hidden" name="tab" value="2">

      <table class="form">
        <tr>
          <th class="mandatory">Code de l'acte:</th>
          <td>
            <input tabindex="1" type="text" name="code" value="<?php echo $this->_tpl_vars['master']['code']; ?>
">
            <input tabindex="2" type="submit" value="afficher">
          </td>
        </tr>
      </table>

      </form>
    </td>

    <?php if ($this->_tpl_vars['canEdit'] && $this->_tpl_vars['master']['levelinf']['0']['sid'] == 0): ?>
    <td class="rightPane">
      <form name="addFavoris" action="./index.php?m=dPcim10" method="post">
      <input type="hidden" name="dosql" value="do_favoris_aed">
      <input type="hidden" name="del" value="0">
      <input type="hidden" name="favoris_code" value="<?php echo $this->_tpl_vars['master']['code']; ?>
">
      <input type="hidden" name="favoris_user" value="<?php echo $this->_tpl_vars['user']; ?>
">
      <input class="button" type="submit" name="btnFuseAction" value="Ajouter à mes favoris">
      </form>
    </td>
    <?php endif; ?>
  </tr>

  <tr>
    <td class="pane" colspan="2">
      <strong>Informations sur ce code:</strong>
      <ul>
        <?php if ($this->_tpl_vars['master']['descr'] != ""): ?>
        <li>
          Description:
          <ul>
            <?php if (count($_from = (array)$this->_tpl_vars['master']['descr'])):
    foreach ($_from as $this->_tpl_vars['curr_descr']):
?>
            <li><?php echo $this->_tpl_vars['curr_descr']; ?>
</li>
            <?php endforeach; unset($_from); endif; ?>
          </ul>
        </li>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['master']['exclude'] != ""): ?>
        <li>
          Exclusions:
          <ul>
            <?php if (count($_from = (array)$this->_tpl_vars['master']['exclude'])):
    foreach ($_from as $this->_tpl_vars['curr_exclude']):
?>
            <li><?php echo $this->_tpl_vars['curr_exclude']['text']; ?>
 (code: <a href="index.php?m=dPcim10&tab=2&code=<?php echo $this->_tpl_vars['curr_exclude']['code']; ?>
"><strong><?php echo $this->_tpl_vars['curr_exclude']['code']; ?>
</strong></a>)</li>
            <?php endforeach; unset($_from); endif; ?>
          </ul>
        </li>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['master']['glossaire'] != ""): ?>
        <li>
          Glossaire:
          <ul>
            <?php if (count($_from = (array)$this->_tpl_vars['master']['glossaire'])):
    foreach ($_from as $this->_tpl_vars['curr_glossaire']):
?>
            <li><?php echo $this->_tpl_vars['curr_glossaire']; ?>
</li>
            <?php endforeach; unset($_from); endif; ?>
          </ul>
        </li>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['master']['include'] != ""): ?>
        <li>
          Inclusions:
          <ul>
            <?php if (count($_from = (array)$this->_tpl_vars['master']['include'])):
    foreach ($_from as $this->_tpl_vars['curr_include']):
?>
            <li><?php echo $this->_tpl_vars['curr_include']; ?>
</li>
            <?php endforeach; unset($_from); endif; ?>
          </ul>
        </li>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['master']['indir'] != ""): ?>
        <li>
          Exclusions indirectes:
          <ul>
            <?php if (count($_from = (array)$this->_tpl_vars['master']['indir'])):
    foreach ($_from as $this->_tpl_vars['curr_indir']):
?>
            <li><?php echo $this->_tpl_vars['curr_indir']; ?>
</li>
            <?php endforeach; unset($_from); endif; ?>
          </ul>
        </li>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['master']['note'] != ""): ?>
        <li>
          Notes:
          <ul>
            <?php if (count($_from = (array)$this->_tpl_vars['master']['note'])):
    foreach ($_from as $this->_tpl_vars['curr_note']):
?>
            <li><?php echo $this->_tpl_vars['curr_note']; ?>
</li>
            <?php endforeach; unset($_from); endif; ?>
          </ul>
        </li>
        <?php endif; ?>
      </ul>
    </td>
  </tr>

  <tr>
    <?php if ($this->_tpl_vars['master']['levelsup']['0']['sid'] != 0): ?>
    <td class="pane">
      <strong>Codes de niveau supérieur:</strong>
      <ul>
        <?php if (count($_from = (array)$this->_tpl_vars['master']['levelsup'])):
    foreach ($_from as $this->_tpl_vars['curr_level']):
?>
        <?php if ($this->_tpl_vars['curr_level']['sid'] != 0): ?>
        <li><a href="index.php?m=dPcim10&tab=2&code=<?php echo $this->_tpl_vars['curr_level']['code']; ?>
"><strong><?php echo $this->_tpl_vars['curr_level']['code']; ?>
</strong></a>: <?php echo $this->_tpl_vars['curr_level']['text']; ?>
</li>
        <?php endif; ?>
        <?php endforeach; unset($_from); endif; ?>
      </ul>
    </td>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['master']['levelinf']['0']['sid'] != 0): ?>
    <td class="pane">
      <strong>Codes de niveau inferieur :</strong>
      <ul>
        <?php if (count($_from = (array)$this->_tpl_vars['master']['levelinf'])):
    foreach ($_from as $this->_tpl_vars['curr_level']):
?>
        <?php if ($this->_tpl_vars['curr_level']['sid'] != 0): ?>
        <li><a href="index.php?m=dPcim10&tab=2&code=<?php echo $this->_tpl_vars['curr_level']['code']; ?>
"><strong><?php echo $this->_tpl_vars['curr_level']['code']; ?>
</strong></a> : <?php echo $this->_tpl_vars['curr_level']['text']; ?>
</li>
        <?php endif; ?>
        <?php endforeach; unset($_from); endif; ?>
      </ul>
    </td>
    <?php endif; ?>
  </tr>
</table>