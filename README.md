# battleships
#Battlelship
===========

### Battleships game implementation for console and web.

One-sided game of battleships for playing against a computer. The program creates a 10x10 grid, and place a number of ships on the grid at random with the following sizes: 1 x Battleship (5 squares) 2 x Destroyers (4 squares)

It accepts input from the user in the format “A5” to signify a square to target, and output feedback to the user whether the shot was success, miss, and additionally report on the sinking of any vessels or end of the game.
Backdoor special word available - “show” for both web and cli. If applied ship positioning is revealed on the grid.
Special word “reset” available under cli. Used to reset the game.

Interface:
. = no shot
- = miss
x = hit

Starting the game:
- web: `//host.domain/index.php`
- cli: `php /path_to_game/index.php`

- [x] **Requires PHP 5.6.\***

More about the game: http://en.wikipedia.org/wiki/Battleship (game)
