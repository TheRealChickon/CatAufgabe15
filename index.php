<?php

require_once 'CatBowl.php';
require_once 'Cat.php';

echo '<pre>';

// create catbowl object from class
$bowl = new CatBowl(1.0, 0.0);
// refill it
$bowl->refill(10.0);
// output current status
$bowl->showStatus();
echo "<br />";

// Create Cat Objects with the Constructor
$robert = new Cat("Robert", "rot", 3.5);
$felix1 = new Cat("Felix der 1.", "grau", 4.0);
echo "<br />";

// aktionen
$robert->showStatus();
$robert->sleep();

$robert->eat($bowl);
$robert->eat($bowl);
echo "<br />";

$felix1->eat($bowl);
$felix1->eat($bowl);
$felix1->eat($bowl);
echo "<br />";

$bowl->showStatus();
echo "<br />";


$robert->showStatus();
$felix1->showStatus();
$robert->play($felix1);
echo "<br />";

$robert->defecate();
echo "<br />";

$robert->sleep();
echo "<br />";

$robert->showStatus();
$felix1->showStatus();
$bowl->showStatus();

echo '</pre>';