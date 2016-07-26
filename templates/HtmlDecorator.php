<?php

/**
 * Generate layout
 *
 * @author Svetoslav Dragoev
 */
trait HtmlDecorator {
	
/*
* Render HTML code
*
* @return void
* @access public
*/
public function render() {

echo <<<EOD
<!DOCTYPE html>
<html>
  <head>
    <title>BattleShips For Dummies</title>
    <style>
      /* link styles */
      a,
      a:visited,
      a:hover,
      a:active {
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        position:relative;
        transition: 0.5s color ease;
        text-decoration: none;
        color: #666;
        font-size: 0.75em;
        font-family: Arial, sans-serif;
      }

      a:hover{
        color: #d73444;
      }

      a:after {
        background: #d73444;
        content: "";
        transition: 0.5s all ease;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        position: absolute;
        bottom: -0.25em;
        left: 0;
        height: 1px;
        height: 0.15rem;
        width: 0;
      }

      a:hover:after {
        width: 100%;
      }
      /* /link styles */
      </style>
    </head>
    <body>
        <a href="/">Start New Game</a> / <a href="https://en.wikipedia.org/wiki/Battleship_(game)" target="_blank">Game rules</a>
        <sectip>
            <pre>{$this->_grid}</pre>
            <br />
            <form name="input" action="index.php" method="post">
                Please enter coordinates (row, col), e.g. A5: <input type="input" size="5" name="coordinates" autocomplete="off" autofocus>
                <input type="submit">
            </form>
        </sction>
    </body>
</html>
EOD;
	
}
}