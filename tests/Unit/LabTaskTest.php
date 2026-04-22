<?php

use App\Models\LabTask;

test('full marks returns total points', function () {
    $labTask = new LabTask();
    $labTask->total_points = 10;
    expect($labTask->getMarksType('full'))->toBe(10);
});

test('half marks returns half total points', function () {
    $labTask = new LabTask();
    $labTask->total_points = 10;
    expect($labTask->getMarksType('half'))->toBe(5.0);
});

test('invalid type returns 0', function () {
    $labTask = new LabTask();
    $labTask->total_points = 10;
    expect($labTask->getMarksType('invalid'))->toBe(0.0);
});