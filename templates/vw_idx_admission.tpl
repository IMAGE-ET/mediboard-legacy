<!-- $Id$ -->

{literal}
<script type="text/javascript">
//<![CDATA[

function printAdmission(id) {
  var url = './index.php?m=dPadmissions&a=print_admission&dialog=1';
  url = url + '&id=' + id;
  window.open(url, 'Patient', 'left=10,top=10,height=550,width=700,resizable=1,scrollbars=1');
}

//]]>
</script>
{/literal}

<table class="main">
  <tr>
    <th width="50%">
        <a href="index.php?m={$m}&amp;tab={$tab}&amp;day={$pmonthd}&amp;month={$pmonth}&amp;year={$pmonthy}"><<</a>
        {$title1}
        <a href="index.php?m={$m}&amp;tab={$tab}&amp;day={$nmonthd}&amp;month={$nmonth}&amp;year={$nmonthy}">>></a>
      </th>
      <th width="50%">
        <a href="index.php?m={$m}&amp;tab={$tab}&amp;day={$pday}&amp;month={$pdaym}&amp;year={$pdayy}"><<</a>
        {$title2}
        <a href="index.php?m={$m}&amp;tab={$tab}&amp;day={$nday}&amp;month={$ndaym}&amp;year={$ndayy}">>></a>
        <i>Admissions affichées :
        {if $selAdmis == "n"}admissions non effectuées
        {elseif $selSaisis == "n"}préadmission AS/400 non faite
        {else}toutes les admissions
        {/if}</i>
    </th>
  </tr>
  <tr>
    <td>
      <table class="tbl">
        <tr>
          <th>Date</th>
          <th><a href="index.php?m={$m}&amp;tab={$tab}&amp;selAdmis=0&amp;selSaisis=0">Toutes les admissions</a></th>
          <th><a href="index.php?m={$m}&amp;tab={$tab}&amp;selAdmis=0&amp;selSaisis=n">Préadmission AS/400 non faite</a></th>
          <th><a href="index.php?m={$m}&amp;tab={$tab}&amp;selAdmis=n&amp;selSaisis=0">Admissions non effectuées</a></th>
        </tr>
        {foreach from=$list1 item=curr_list}
        <tr>
          <td align="right">
            <a href="index.php?m={$m}&amp;tab={$tab}&amp;day={$curr_list.day}&amp;month={$month}&amp;year={$year}">
            {$curr_list.dateFormed}
            </a>
          </td>
          <td align="center">
            {$curr_list.num}
          </td>
          <td align="center">
            {$curr_list.num3}
          </td>
          <td align="center">
            {$curr_list.num2}
          </td>
        </tr>
        {/foreach}
      </table>
    </td>
    <td>
      <table class="color">
        <tr>
          <th>
            Nom
          </th>
          <th>
            Prénom
          </th>
          <th>
            Chirurgien
          </th>
          <th>
            Heure
          </th>
          <th>
            Admis
          </th>
          <th>
            Saisis
          </th>
        </tr>
        {foreach from=$today item=curr_adm}
        <tr style="background: {if $curr_adm.type_adm == 'ambu'}#faa{elseif $curr_adm.type_adm == 'comp'}#fff{else}#afa{/if}">
          <td>
            <a href="#" onclick="printAdmission({$curr_adm.operation_id})">
            {$curr_adm.nom}
            </a>
          </td>
          <td>
            <a href="#" onclick="printAdmission({$curr_adm.operation_id})">
            {$curr_adm.prenom}
            </a>
          </td>
          <td>
            <a href="#" onclick="printAdmission({$curr_adm.operation_id})">
            Dr. {$curr_adm.chir_lastname} {$curr_adm.chir_firstname}
            </a>
          </td>
          <td>
            <a href="#" onclick="printAdmission({$curr_adm.operation_id})">
            {$curr_adm.hour}
            </a>
          </td>
          <td>
            {if $curr_adm.admis == "n"}
            <form name="editAdmFrm{$curr_adm.id}" action="index.php" method="get">
            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="a" value="do_edit_admis" />
            <input type="hidden" name="id" value="{$curr_adm.operation_id}" />
            <input type="hidden" name="mode" value="admis" />
            <input type="submit" value="Admis" />
            </form> 
            {/if}
          </td>
          <td>
            {if $curr_adm.saisie == "n"}
            <form name="editSaisFrm{$curr_adm.id}" action="index.php" method="get">
            <input type="hidden" name="m" value="{$m}" />
            <input type="hidden" name="a" value="do_edit_admis" />
            <input type="hidden" name="id" value="{$curr_adm.operation_id}" />
            <input type="hidden" name="mode" value="saisie" />
            <input type="submit" value="Saisie" />
            </form> 
            {/if}
          </td>
        </tr>
        {/foreach}
      </table>
    </td>
  </tr>
</table>