<?php
$multipleFrame = Yii::app()->nodeSocket->createMultipleFrame();

$eventFrame = Yii::app()->nodeSocket->createEventFrame();

$eventFrame->setEventName('updateBoard');
$eventFrame['boardId'] = rand(1, 100);
$eventFrame['boardData'] = 'Text.....'.date('H:m:s');

$dataEvent = Yii::app()->nodeSocket->createPublicDataFrame();

$dataEvent->setKey('error.strings');
$dataEvent['key'] = 5;

$multipleFrame->addFrame($eventFrame);
$multipleFrame->addFrame($dataEvent);
$multipleFrame->send();