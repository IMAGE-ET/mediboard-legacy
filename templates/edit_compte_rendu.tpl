<form name="editFrm" action="?m={$m}" method="POST">

<input type="hidden" name="m" value="{$m}" />
<input type="hidden" name="del" value="0" />
<input type="hidden" name="dosql" value="do_planning_aed" />
<input type="hidden" name="special" value="1" />
<input type="hidden" name="cr_valide" value="0" />
<input type="hidden" name="operation_id" value="{$op->operation_id}" />

<table class="form">
  <tr>
    <td class="button">
      <input type="submit" value="Modifier" />
      <input type="reset" value="Réinitialiser" />
    </td>
  </tr>
</table>

<textarea style="width: 99%" id="htmlarea" name="compte_rendu" rows="40">
  {$templateManager->document}
</textarea>

</form>