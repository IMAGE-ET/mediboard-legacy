{literal}
<script type="text/javascript">

function pageMain() {
  initGroups("acte");
}

</script>
{/literal}

<table class="tbl">
        <tr>
          <th>Actes</th>
          <td class="text">
          {include file="../../dPsalleOp/templates/inc_manage_codes.tpl"}
          </td>
        </tr>
        <tr>
          <th>
            Intervention
            <br />
            Côté {$selOp->cote}
            <br />
            ({$selOp->temp_operation|date_format:"%Hh%M"})
          </th>
          <td class="text">
          {include file="../../dPsalleOp/templates/inc_codage_actes.tpl"}
          </td>
        </tr>
</table>