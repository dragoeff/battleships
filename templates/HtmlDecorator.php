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
      .button {
          font: bold 11px Arial;
          text-decoration: none;
          background-color: #eee;
          color: #333;
          padding: 2px 6px;
          border-top: 1px solid #ccc;
          border-right: 1px solid #333;
          border-bottom: 1px solid #333;
          border-left: 1px solid #ccc;
      }
      </style>
    </head>
    <body>
        <a href="/" class="button">Start New Game</a>
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