<table class="main">
  <tr>
    <td>
      <form name="activite" action="index.php?m=dPstats" method="GET">
      <table class="form">
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
          <td colspan="2">
            <img src='?m=dPstats&amp;a=graph_activite&amp;suppressHeaders=1&amp;debut={$debutact}&amp;fin={$finact}' />
          </td>
        </tr>
      </table>
      </form>
    </td>
    <td>
      <form name="users" action="index.php?m=dPstats" method="GET">
      <table class="form">
        <tr>
          <th>Début:</th>
          <td><input type="text" name="debutlog" value="{$debutlog}" /></td>
          <th>user:</th>
          <td><input type="text" name="user_id" value="{$user_id}" /></td>
        </tr>
        <tr>
          <th>Fin:</th>
          <td><input type="text" name="finlog" value="{$finlog}" /></td>
        </tr>
        <tr>
        </tr>
        <tr>
          <td colspan="4" class="button"><button type="submit">Go</button></td>
        </tr>
        <tr>
          <td colspan="4">
            <img src='?m=dPstats&amp;a=graph_users&amp;suppressHeaders=1&amp;debut={$debutact}&amp;fin={$finact}&amp;user_id={$user_id}' />
          </td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
</table>