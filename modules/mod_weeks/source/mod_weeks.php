<?php
defined('_JEXEC') or die;

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

$autumnlenght = $params->get('autumnperiod'); // продолжительность осеннего семетсра в неделях

$curweeknum = date("W"); //текущая неделя по счету с начала года
$curdate = explode(".", date("d.m.Y"));

//var_dump($autumn); die();

if($curdate[1] >=9){
	//осенний семестра до нового года
	$calcyear = date("Y");
	$firstweeknum = date("W", mktime(0,0,0,9,1, $calcyear));
	$week = $curweeknum - $firstweeknum + 1;
}else{
	// вычисляем остаок недель осеннего семетра после НГ
	$oldyearfirstweeknum = date("W", mktime(0,0,0,9,1, date("Y")-1)); // 1 сентября прошлого года
	$tmpday = 31;
	$oldyearweekscount = date("W", mktime(0,0,0,12,$tmpday, date("Y")-1)); // Количество недель в прошлом году (52 или 53)

	if($oldyearweekscount == 1){ // из-за особенностей date("W") надо уточнить поиск
		while ($oldyearweekscount==1) {
			$tmpday--;
			$oldyearweekscount = date("W", mktime(0,0,0,12,$tmpday, date("Y")-1));
		}
	}

	$autumninoldyear = $oldyearweekscount - $oldyearfirstweeknum+1; // Количество недель осеннего семестра в прошлом году

	$autumninnewyear = $autumnlenght - $autumninoldyear; // Количество недель осеннего семестра в новом году

	if($curweeknum<=$autumninnewyear){
		// Продолжение осеннего семестра
		$week = $autumninoldyear + $curweeknum;
	}else{
		// Весенний семестр
		$week = $curweeknum - $autumninnewyear;
	}
}

require JModuleHelper::getLayoutPath('mod_weeks', $params->get('layout', 'default'));
