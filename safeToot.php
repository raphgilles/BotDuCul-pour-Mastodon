<?php

function safeToot($toot){
	$isSafe = !stristr($toot, "viol") || 
	(stristr($toot, "violet") || stristr($toot, "violen") || stristr($toot, "violem") ||
	stristr($toot, "aviol") || stristr($toot, "violac") || stristr($toot, "violaç") ||
	stristr($toot, "inviol") || stristr($toot, "violat") || stristr($toot, "violât")  || 
	stristr($toot, "violon") || stristr($toot, "violine")); // allow violence, violent, violet, violâtre, violaçé, violacer, violon, violoncelles
	$simpleWords = array("bougnoul", "bounioul", "chineto", "niak",
	"nègre", "négre", "négrillon", "négro",  // allow négritude
	"shoah", "youp", "banania", 
	"pédé", "pd", "gouine", "tafiol", "tarlou", "travelo");
	foreach ($simpleWords as $key => $word) {
		$isSafe = $isSafe && !stristr($toot, $word);
	}
	return $isSafe;
}

?>