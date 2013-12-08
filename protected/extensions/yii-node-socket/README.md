Yii Node Socket
=================

Connect php, javascript, nodejs in one Yii application.

#Installation

Install nodejs, if not installed see http://nodejs.org/<br>
Install extension

 * Using git clone

```bash
$> git clone git@github.com:oncesk/yii-node-socket.git
```

Now go to the folder where you install extension  ***application.ext.yii-node-socket*** and execute<br>
```bash
$> git submodule init
$> git submodule update
```

Yii configuration<br>
 * Configure console command in (***main/console.php***). You can use config below:

```php
'commandMap' => array(
	'node-socket' => 'application.extensions.yii-node-socket.lib.php.NodeSocketCommand'
)
```

 * Register Yii component, need to add into **main.php and console.php**:

```php
'nodeSocket' => array(
	'class' => 'application.extensions.yii-node-socket.lib.php.NodeSocket',
	'host' => 'localhost',	// default is 127.0.0.1, can be ip or domain name, without http
	'port' => 3001		// default is 3001, should be integer
)
```
> Notice: ***host*** should be a domain name like in you virtual host configuration or server ip address if you request page using ip address

Install ***nodejs*** components in ***application.ext.yii-node-socket.lib.js.server***:
```bash
$> npm install
```

Congratulation, installation completed!

> Notice: if the name of the component will not be **nodeSocket**, your need to use special key in console command --componentName=component_name

###Console command actions

Use (**./yiic node-socket**)

```bash
$> ./yiic node-socket # show help
$> ./yiic node-socket start # start server
$> ./yiic node-socket stop # stop server
$> ./yiic node-socket restart # restart server
$> ./yiic node-socket getPid # show pid of nodejs process
```

##Javascript

Before use in javascript, register client stripts like here

```php
public function actionIndex() {
	// register node socket scripts
	Yii::app()->nodeSocket->registerClientScripts();
}
```

###Events

Predefined events:

* `listener.on('connect', function () {})` - "connect" is emitted when the socket connected successfully
* `listener.on('reconnect', function () {})` - "reconnect" is emitted when socket.io successfully reconnected to the server
* `listener.on('disconnect', function () {})` - "disconnect" is emitted when the socket disconnected

Your own events:

* `listener.on('update', function (data) {})` - emitted when PHP server emit update event
* `listener.on('some_event', function (data) {})` - emitted when PHP server emit some_event event

###Work in javascript

Use `YiiNodeSocket` class

####Start work

```javascript

// create object
var socket = new YiiNodeSocket();

// enable debug mode
socket.debug(true);
```

####Catch Events

Now events can be created only on PHP side. All data transmitted in json format.
Into callback function data was pasted as javascript native object (or string, integer, depends of  your PHP Frame config)

```javascript
// add event listener
socket.on('updateBoard', function (data) {
	// do any action
});
```

####Rooms

```javascript
socket.room('testRoom').join(function (success, numberOfRoomSubscribers) {
	// success - boolean, numberOfRoomSubscribers - number of room members
	// if error occurred then success = false, and numberOfRoomSubscribers - contains error message
	if (success) {
		console.log(numberOfRoomSubscribers + ' clients in room: ' + roomId);
		// do something
		
		// bind events
		this.on('join', function (newMembersCount) {
			// fire on client join
		});
		
		this.on('data', function (data) {
			// fire when server send frame into this room with 'data' event
		});
	} else {
		// numberOfRoomSubscribers - error message
		alert(numberOfRoomSubscribers);
	}
});
```

####Shared Public Data

You can set shared data only from PHP using PublicData Frame (see below into PHP section).
To access data you can use `getPublicData(string key, callback fn)` method

```javascript
socket.getPublicData('error.strings', function (strings) {
	// you need to check if strings exists, because strings can be not setted or expired,
	if (strings) {
		// do something
	}
});
```

##PHP

####Client scripts registration

```php
public function actionIndex() {
	...

	Yii::app()->nodeSocket->registerClientScripts();
	
	...
}
```

####Event frame

```php

...

// create event frame
$frame = Yii::app()->nodeSocket->createEventFrame();

// set event name
$frame->setEventName('updateBoard');

// set data using ArrayAccess interface
$frame['boardId'] = 25;
$frame['boardData'] = $html;

// or you can use setData(array $data) method
// setData overwrite data setted before

$frame->send();

...

```

####Set up shared data

You can set expiration using ***setLifeTime(integer $lifetime)*** method of class PublicData

```php

...

// create frame
$frame = Yii::app()->nodeSocket->createPublicDataFrame();

// set key in storage
$frame->setKey('error.strings');

// set data
$frame->setData($errorStrings);

// you can set data via ArrayAccess interface
// $frame['empty_name'] = 'Please enter name';

// set data lifetime
$frame->setLifeTime(3600*2);	// after two hours data will be deleted from storage

// send
$frame->send();

...

```

####Room events

```php

...

// create frame
$frame = Yii::app()->nodeSocket->createEventFrame();

// set event name
$frame->setEventName('updateBoard');

// set room name
$frame->setRoom('testRoom');

// set data
$frame['key'] = $value;

// send
$frame->send();

...

```

Only member of testRoom can catch this event

####Invoke client function or method

In your PHP application you can invoke javascript function or method of object in window context.

```php

$invokeFrame = Yii::app()->nodeSocket->createInvokeFrame();
$invokeFrame->invokeFunction('alert', array('Hello world'));
$invokeFrame->send();	// alert will be showed on all clients

```

Extends from Event frame => you can send it into specific room

####DOM manipulations with jquery

Task: you need update price on client side after price update in each product

```php

...

$product = Product::model()->findByPk($productId);
if ($product) {
	$product->price = $newPrice;
	if ($product->save()) {
		$jFrame = Yii::app()->nodeSocket->createJQueryFrame();
		$jFrame
			->createQuery('#product' . $product->id)
			->find('span.price')
			->text($product->price);
		$jFrame->send();
		// and all connected clients will can see updated price
	}
}

...

```

####Send more than one frame per a time

Example 1: 

```php

$multipleFrame = Yii::app()->nodeSocket->createMultipleFrame();

$eventFrame = Yii::app()->nodeSocket->createEventFrame();

$eventFrame->setEventName('updateBoard');
$eventFrame['boardId'] = 25;
$eventFrame['boardData'] = $html;

$dataEvent = Yii::app()->nodeSocket->createPublicDataFrame();

$dataEvent->setKey('error.strings');
$dataEvent['key'] = $value;

$multipleFrame->addFrame($eventFrame);
$multipleFrame->addFrame($dataEvent);
$multipleFrame->send();

```

Example 2:

```php

$multipleFrame = Yii::app()->nodeSocket->createMultipleFrame();

$eventFrame = $multipleFrame->createEventFrame();

$eventFrame->setEventName('updateBoard');
$eventFrame['boardId'] = 25;
$eventFrame['boardData'] = $html;

$dataEvent = $multipleFrame->createPublicDataFrame();

$dataEvent->setKey('error.strings');
$dataEvent['key'] = $value;

$multipleFrame->send();

```

##In plans

1. Create subscribe/unsibscribe system
2. Store channel information into db (mongoDB, mysql)
3. The ability to create private, public and etc. channels
4. Socket Authentication and authorization
