<?php
session_start();
if(isset($_SESSION['studentID'])) {
    echo $_SESSION['studentID'];
} else {
    echo "0"; 
}
?>
