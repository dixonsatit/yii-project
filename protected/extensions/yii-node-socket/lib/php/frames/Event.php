<?php
namespace YiiNodeSocket\Frame;

class Event extends AFrame {

	/**
	 * @return string
	 */
	public function getType() {
		return self::TYPE_EVENT;
	}

	/**
	 * @param $eventName
	 *
	 * @return Event
	 */
	public function setEventName($eventName) {
		if (is_string($eventName) && !empty($eventName)) {
			$this->addMetaData('event', $eventName);
		}
		return $this;
	}

	/**
	 * @param string $room
	 *
	 * @return Event
	 */
	public function setRoom($room) {
		if ((is_string($room) || is_numeric($room)) && !empty($room)) {
			$this->addMetaData('room', $room);
		}
		return $this;
	}

	protected function init() {
		$this->setEventName('undefined');
	}

	/**
	 * @return bool
	 */
	public function isValid() {
		return $this->hasMetaData('event');
	}
}