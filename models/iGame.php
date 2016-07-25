<?php

/**
 * Game interface
 *
 * @author Svetoslav Dragoev
 */
interface iGame {
	/**
	 * How to mark a blank/default on the grid
	 */
	const BLANK = '.';

	/**
	 * How to mark a hit on the grid
	 */
	const HIT = 'x';

	/**
	 * How to mark a miss on the grid
	 */
	const MISS = '-';

	/**
	 * How to mark a ship on the grid
	 */
	const SHIP = 's';

	/**
	 * Grid size in number of slots
	 */
	const GRID_SIZE = 10;

	/**
	 * Character lower case A in the ASCII table
	 */
	const ASCII_A = 97;
}
