<script language="JavaScript" type="text/javascript">
{literal}

function pageMain() {
  Calendar.setup( {
      flat         : "calendar-container",
      flatCallback : dateChanged         ,
	  showsTime    : true,
      date         : {/literal}new Date({$year}, {$month}, {$day}, {$hour}, {$min}, 0){literal}
    }
  );
}

function dateChanged(calendar) {
  if (calendar.dateClicked) {
    var y = calendar.date.getFullYear();
    var m = calendar.date.getMonth();
    var d = calendar.date.getDate();
    var h  = calendar.date.getHours();
    var mi = calendar.date.getMinutes();
   
    var url = "index.php?m={/literal}{$m}{literal}";
    url += "&tab={/literal}{$tab}{literal}";
    url += "&year="  + y;
    url += "&month=" + m;
    url += "&day="   + d;
    url += "&hour="  + h;
    url += "&min="   + mi;

    window.location = url;
  }
}

function popPlanning() {
  var url = '?m=dPhospi&a=vw_affectations&dialog=1';
  {/literal}
  url += '&day=' + {$day} + '&month=' + {$month} + '&year=' + {$year}
  {literal}
  popup(700, 550, url, 'Planning');
}

{/literal}
</script>

<script type="text/javascript" src="lib/jscalendar/calendar.js"></script>
<script type="text/javascript" src="lib/jscalendar/lang/calendar-fr.js"></script>
<script type="text/javascript" src="lib/jscalendar/calendar-setup.js"></script>

<table class="main">
  <tr>
    <td class="Pane">
      <strong><a href="#" onclick="popPlanning()">Afficher le planning</a></strong>
      <div id="calendar-container"></div>
    </td>
    <td class="greedyPane">
      <table class="tbl">
        <tr>
          <th class="title" colspan="4">
            {$date|date_format:"%A %d %B %Y à %H h %M"} : {$libre|@count} lit(s) disponible(s)
          </th>
        </tr>
        <tr>
          <th>Service</th>
          <th>Chambre</th>
          <th>Lit</th>
          <th>Fin de disponibilité</th>
        </tr>
        {foreach from=$libre item=curr_lit}
        <tr>
          <td>{$curr_lit.service}</td>
          <td>{$curr_lit.chambre}</td>
          <td>{$curr_lit.lit}</td>
          <td>{$curr_lit.limite|date_format:"%A %d %B %Y à %H h %M"}
        </tr>
        {/foreach}
      </table>
    </td>
  </tr>
</table>
