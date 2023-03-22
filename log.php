<?php
    require "functions.php";
    
    $mission = $_POST['mission'];
    $story = "Braveland";
    saveValue($mission);
    updateCurrentMission($mission, $story);

?>