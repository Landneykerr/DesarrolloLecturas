<?php
require ("zipfile.php");

$zipfile = new zipfile();
$zipfile->add_dir("img/");
$zipfile->add_file(implode("",file("img01.jpg")), "img/img1.jpg");
$zipfile->add_file(implode("",file("img02.jpg")), "img/img2.jpg");
$zipfile->add_file(implode("",file("img03.jpg")), "img/img3.jpg");

header("Content-type: application/octet-stream");
header("Content-disposition: attachment; filename=fotos.zip");
echo $zipfile->file();
?>