<?php
require './DvdQuery.php';

use Database\Query\DvdQuery;

$dvdQuery = new DvdQuery();
$dvdQuery->titleContains('Die');
$dvdQuery->orderByTitle();
$dvds = $dvdQuery->find();
var_dump($dvds);
