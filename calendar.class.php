<?php /* $Id$ */

/**
* @package Mediboard
* @subpackage dPbloc
* @version $Revision$
* @author Romain Ollivier
*/

class Ccalendar
{
  var $day;
  var $month;
  var $year;
  var $date;
  var $lang;
  var $url;
  var $dayWeekList;
  var $monthList;
  
  function Ccalendar($url, $day = -1, $month = -1, $year = -1, $lang = "fr")
  {
    $this->url = $url;
    $this->lang = $lang;
	$this->initList();
    if($day == -1)
	{
	  $this->day = date("d");
	}
	else
	  $this->day = $day;
	if($month == -1)
	{
	  $this->month = date("m");
	}
	else
	  $this->month = $month;
	if($year == -1)
	{
	  $this->year = date("Y");
	}
	else
	  $this->year = $year;
  }
  function initList()
  {
    switch($this->lang)
	{
	  case "fr" :
	  {
	    $this->dayWeekList[1] = "Lu";
	    $this->dayWeekList[2] = "Ma";
	    $this->dayWeekList[3] = "Me";
	    $this->dayWeekList[4] = "Je";
	    $this->dayWeekList[5] = "Ve";
	    $this->dayWeekList[6] = "Sa";
	    $this->dayWeekList[7] = "Di";

		$this->monthList[1] = "Janvier";
		$this->monthList[2] = "Février";
		$this->monthList[3] = "Mars";
		$this->monthList[4] = "Avril";
		$this->monthList[5] = "Mai";
		$this->monthList[6] = "Juin";
		$this->monthList[7] = "Juillet";
		$this->monthList[8] = "Aout";
		$this->monthList[9] = "Septembre";
		$this->monthList[10] = "Octobre";
		$this->monthList[11] = "Novembre";
		$this->monthList[12] = "Décembre";
		break;
	  }
	  default :
	  {
	    $this->dayWeekList[1] = "Lundi";
	    $this->dayWeekList[2] = "Mardi";
	    $this->dayWeekList[3] = "Mercredi";
	    $this->dayWeekList[4] = "Jeudi";
	    $this->dayWeekList[5] = "Vendredi";
	    $this->dayWeekList[6] = "Samedi";
	    $this->dayWeekList[7] = "Dimanche";

		$this->monthList[1] = "Janvier";
		$this->monthList[2] = "Février";
		$this->monthList[3] = "Mars";
		$this->monthList[4] = "Avril";
		$this->monthList[5] = "Mai";
		$this->monthList[6] = "Juin";
		$this->monthList[7] = "Juillet";
		$this->monthList[8] = "Aout";
		$this->monthList[9] = "Septembre";
		$this->monthList[10] = "Octobre";
		$this->monthList[11] = "Novembre";
		$this->monthList[12] = "Décembre";
		break;
	  }
	}
  }
  function display()
  {
    $strout = "";
    $numDays = array(7, 1, 2, 3, 4, 5, 6);
    $firstDayOfMonth = $numDays[date("w", mktime(0, 0, 0, $this->month, 1, $this->year))];
	$lastDayOfMonth = date("t", mktime(0, 0, 0, $this->month, 1 ,$this->year));
	$lastDayOfLastMonth = date("t", mktime(0, 0, 0, $this->month - 1, 1 ,$this->year));
	if(strstr($this->url, "?"))
	{
	  $prev = $this->url."&day=".$this->day."&month=".($this->month-1)."&year=".$this->year;
	  $next = $this->url."&day=".$this->day."&month=".($this->month+1)."&year=".$this->year;
	}
	else
	{
	  $prev = $this->url."?day=".$this->day."&month=".($this->month-1)."&year=".$this->year;
	  $next = $this->url."?day=".$this->day."&month=".($this->month+1)."&year=".$this->year;
	}
	$strout .= "<table>\n";
	$strout .= "<tr>\n";
	$strout .= "<td><a href=\"$prev\" target=\"_self\"><<</a></td>\n";
	$strout .= "<td align=\"center\" colspan=\"7\" bgcolor=\"#3333ff\">\n";
	$strout .= "<b>".$this->monthList[intval($this->month)]." ". $this->year."</b>\n";
	$strout .= "</td>\n";
	$strout .= "<td><a href=\"$next\" target=\"_self\">>></a></td>\n";
	$strout .= "</tr>\n";
	$strout .= "<tr>\n";
	$strout .= "<td></td>\n";
	for($i = 1; $i < 8; $i++)
	{
	  $strout .= "<td align=\"center\" bgcolor=\"#cccccc\">";
	  $strout .= $this->dayWeekList[$i];
	  $strout .= "</td>\n";
	}
	$strout .= "<td></td>\n";
	$strout .= "</tr>\n";
	$currDay = $lastDayOfLastMonth - ($firstDayOfMonth - 2);
	if($firstDayOfMonth == 1)
	{
	  $currDay = 1;
	  $currMonth = 1;
    }
	else
	{
	  $currMonth = 0;
	}
	for($i = 1; $i < 7; $i++)
	{
	  $strout .= "<tr>\n";
	  $strout .= "<td></td>\n";
	  for($j = 1; $j < 8; $j++)
	  {
		switch($currMonth)
		{
		  case 0 :
		  {
		    if(strstr($this->url, "?"))
	        {
	          $chgday = $this->url."&day=".$currDay."&month=".($this->month-1)."&year=".$this->year;
	        }
	        else
	        {
	          $chgday = $this->url."?day=".$currDay."&month=".($this->month-1)."&year=".$this->year;
	        }
		    $strout .= "<td align=\"center\">";
			$strout .= "<a href=\"$chgday\" target=\"_self\">".$currDay."</a>";
			$strout .= "</td>\n";
			$currDay++;
			if($currDay > $lastDayOfLastMonth)
			{
			  $currDay = 1;
			  $currMonth = 1;
			}
		    break;
		  }
		  case 1 :
		  {
		    if(strstr($this->url, "?"))
	        {
	          $chgday = $this->url."&day=".$currDay."&month=".$this->month."&year=".$this->year;
	        }
	        else
	        {
	          $chgday = $this->url."?day=".$currDay."&month=".$this->month."&year=".$this->year;
	        }
			if($currDay == $this->day)
			{
			  $strout .= "<td align=\"center\" bgcolor=\"#ffddcc\">";
			  $strout .= "<a href=\"$chgday\" target=\"_self\">".$currDay."</a>";
			  $strout .= "</td>\n";
			}
			else
			{
		      $strout .= "<td align=\"center\" bgcolor=\"#ffffff\">";
			  $strout .= "<a href=\"$chgday\" target=\"_self\">".$currDay."</a>";
			  $strout .= "</td>\n";
		    }
			$currDay++;
			if($currDay > $lastDayOfMonth)
			{
			  $currDay = 1;
			  $currMonth = 2;
			}
			break;
		  }
		  case 2 :
		  {
		    if(strstr($this->url, "?"))
	        {
	          $chgday = $this->url."&day=".$currDay."&month=".($this->month+1)."&year=".$this->year;
	        }
	        else
	        {
	          $chgday = $this->url."?day=".$currDay."&month=".($this->month+1)."&year=".$this->year;
	        }
		    $strout .= "<td align=\"center\">";
			$strout .= "<a href=\"$chgday\" target=\"_self\">".$currDay."</a>";
			$strout .= "</td>\n";
			$currDay++;
			break;
		  }
		}
	  }
	  $strout .= "<td></td>\n";
	  $strout .= "</tr>\n";
	}
    $strout .= "</table>\n";
	return $strout;
  }
}

?>