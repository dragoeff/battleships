<?php

/**
 * Coordinates Translator
 *
 * @author Svetoslav Dragoev
 */
trait CoordinatesTranslator {
	/**
	 * Convert coordinates to an array
	 * Example: B2 -> ['y' => 1, 'x' => 1]
	 * Grid matrix is saved in the form [Y, [X, X,...]] because we first generate the row and then he columns
	 *
	 * @params string $coordinates
	 * @return array
	 * @access public
	 */
	public function coordinates_to_array($coordinates) {
		$coordinates = strtolower($coordinates);
		$alphabet = range('a', 'z');

		$coordinates_array = preg_split('/(\d+)/', $coordinates, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

		return [
			'y' => array_search($coordinates_array[0], $alphabet),
			'x' => $coordinates_array[1]-1
		];
	}

	/**
	* Convert coordinates to a string
	* Example: A1 -> 00
	*
	* @params string $coordinates
	* @return string
	* @access public
	*/
	public function coordinates_to_string($coordinates) {
		$coordinates = strtolower($coordinates);
		$alphabet = range('a', 'z');

		$coordinates_array = preg_split('/(\d+)/', $coordinates, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

		return array_search($coordinates_array[0], $alphabet) . $coordinates_array[1]-1;
	}
}