<?php
include '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Calculation\DateTime;
use Carbon\Carbon;

dd(Carbon::parse('2018-05-29 13:26:00')->diffInRealSeconds());