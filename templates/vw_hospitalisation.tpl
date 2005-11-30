<table class="main">
  <tr>
    <td>
      <form name="activite" action="index.php" method="GET">
      <input type="hidden" name="m" value="dPstats" />
      <table class="form">
        <tr>
          <th colspan="4" class="category">Activité du bloc opératoire</th>
        </tr>
        <tr>
          <th>Début:</th>
          <td><input type="text" name="debutact" value="{$debutact}" /></td>
        </tr>
        <tr>
          <th>Fin:</th>
          <td><input type="text" name="finact" value="{$finact}" /></td>
        </tr>
        <tr>
          <td colspan="2" class="button"><button type="submit">Go</button></td>
        </tr>
        <tr>
          <td colspan="2" class="button">
            <img src='?m=dPstats&amp;a=graph_activite&amp;suppressHeaders=1&amp;debut={$debutact}&amp;fin={$finact}' />
          </td>
        </tr>
      </table>
      </form>
    </td>
    <td>
      <form name="test" action="index.php" method="GET">
      <input type="hidden" name="m" value="dPstats" />
      <table class="form">
        <tr>
          <th colspan="2" class="category">Autre graph</th>
        </tr>
        <tr>
          <th>Début:</th>
          <td><input type="text" name="test" value="" /></td>
        </tr>
        <tr>
          <th>Fin:</th>
          <td><input type="text" name="test" value="" /></td>
        </tr>
        <tr>
          <td colspan="2" class="button"><button type="submit">Go</button></td>
        </tr>
        <tr>
          <td colspan="2" class="button">
          </td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
</table>