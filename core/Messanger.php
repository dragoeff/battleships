<?php
namespace Battleships\Core;

/**
 * Messanger trait, provides basic messaging functionality
 *
 * @author Svetoslav Dragoev
 */
trait Messanger {
	protected $_html_mode = true;

	const MSG_SHOOT_MISS = 'Arrgh! You missed!';
	const MSG_SHOOT_WRONG = 'Oops! You already shot there!';
	const MSG_SHOOT_BULLSEYE = 'Bullseye! You hit a target!';
	const MSG_SHIP_SANK = 'Blimey! You sank a ship!';
	const MSG_CONGRATULATIONS = 'Congratulations! You sank all ships and won the game!';
	const MSG_TOTAL_SHOTS = 'Total shots: ';
	const MSG_HACKER_MODE = 'Hacker mode activated!';

	const HACKER_STRING = 'hacker';

	/**
	 * Class constructor
	 *
	 * @param boolean $_html_mode TRUE if messanger is loaded in HTML mode
	 */
	public function __construct($_html_mode = true) {
		$this->_html_mode = $_html_mode;
	}

	/**
	 * Generate message output
	 * @param  mixed $shot integer[2-3]|boolean|'hacker'
	 * @return string      The produced message
	 */
	public function get($shot) {
		if(self::HACKER_STRING === $shot) {
			return self::MSG_HACKER_MODE;
		}

		if(false === $shot) {
			return self::MSG_SHOOT_WRONG;
		}

		if(true === $shot) {
			if(true === $this->htmlMode) {
				return self::MSG_CONGRATULATIONS;
			}

			// if in console mode, add extra text
			$message  = self::MSG_SHOOT_BULLSEYE.' '.self::MSG_CONGRATULATIONS."\n";
			$message .= self::MSG_TOTAL_SHOTS.$this->map->getShotCount();
			return $message;
		}

		if(2 === (int) $shot) {
			return self::MSG_SHOOT_MISS;
		}

		if(3 === (int) $shot) {
			return self::MSG_SHOOT_BULLSEYE;
		}

		if(4 === (int) $shot) {
			return self::MSG_SHIP_SANK;
		}
	}
}