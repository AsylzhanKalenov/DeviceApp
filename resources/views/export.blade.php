<?php
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename="exporttest.xls"');
readfile(public_path().'/upload/testexport/'.$file.'.xls');
