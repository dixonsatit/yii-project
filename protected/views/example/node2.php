<?php
// create frame
$frame = Yii::app()->nodeSocket->createEventFrame();

// set event name
$frame->setEventName('updateBoard');

// set room name
$frame->setRoom('testRoom');

// set data
$frame['key'] = 5;

// send
$frame->send();
