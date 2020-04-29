<?php

if (($handle = fopen('Issues.csv', "r")) !== FALSE) {
    $the_big_array = array();
    $data_value = array();
    $data_value1 = array();
echo "<pre>";
   $arrays=array();
   $mapping=array();
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
		$mapping[]=array([$data[0]],array([$data[1]],array([$data[2]],array([$data[3]]))));//[$data[2]][$data[3]];
      
    }
	print_r($mapping);
    fclose($handle);
} 
?>