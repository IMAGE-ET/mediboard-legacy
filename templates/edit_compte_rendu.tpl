<form name="editFrm" action="?m={$m}" method="POST">

<input type="hidden" name="m" value="{$m}" />
<input type="hidden" name="del" value="0" />
<input type="hidden" name="dosql" value="do_consultation_aed" />
<input type="hidden" name="consultation_id" value="{$consult->consultation_id}" />
<input type="hidden" name="plageconsult_id" value="{$consult->plageconsult_id}" />
<input type="hidden" name="patient_id" value="{$consult->patient_id}" />
<input type="hidden" name="heure" value="{$consult->heure}" />
<input type="hidden" name="duree" value="{$consult->duree}" />
<input type="hidden" name="motif" value="{$consult->motif}" />
<input type="hidden" name="secteur1" value="{$consult->secteur1}" />
<input type="hidden" name="secteur2" value="{$consult->secteur2}" />
<input type="hidden" name="rques" value="{$consult->rques}" />

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