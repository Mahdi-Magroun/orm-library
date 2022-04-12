<?php 
include './ControlDb.class.php';
print_r(ControlDb:: search(["employee_id","last_name","first_name"],["salary"=>9000],"employees"));