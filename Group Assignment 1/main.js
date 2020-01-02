/*** Configuration constants ***/

// MOVE_SCRIPT_ROOT_URL represents the path that this JavaScript
// will use to find the PHP script that makes the move. For example,
// if this is "/", then this code will load the script from "/move.php."
//
// It's fine to just leave this as "/" as long as move.php is running
// on the same server as this HTML file.
//
// If you need to run them across different servers (for example, if you
// are running this HTML file on your own computer but hosting move.php on
// the server side), then just comment out the first line that just says
// "/move.php" and uncomment the second line that has the full URL.
var MOVE_SCRIPT_ROOT_URL = "/";
// var MOVE_SCRIPT_ROOT_URL = 'http://ahmedalsaisaban.epizy.com/';

// In debug mode, the game will never stop -- very useful for
// testing.
var DEBUG = false;

/*** Globals ***/
var timerInterval = null;
var timerValue = null; // This will stay null until the game starts.
var playing = false;
var flagging = false;
var flagCount = 10;

// *** Main code ***

/*
 * Takes a board as an argument and returns back a count of flags
 * that you still have left to use (counting down from 10 to 0).
 * Used to populate the flag counter at the top left, and also
 * to disable flagging when all the flags are used up.
 *
 * We could also keep track of this on the client side with a counter,
 * but that introduces the risk of bugs, would probably require adding a global,
 * and we still would need this function anyway to figure out the number of
 * flags when loading up an existing game.
 */

function flipFlagging() {
  flagging = !flagging;

  if (flagging) {
    $("#flips").addClass("flaggingOn");
  } else {
    $("#flips").removeClass("flaggingOn");
  }
}

function getNumberOfFlags(board) {
  var numOfFlags = 10;
  board.forEach(function(row) {
    row.forEach(function(cellData) {
      if (numOfFlags > 0 && cellData.flagged) {
        numOfFlags--;
      }
    });
  });
  return numOfFlags;
}

function stopGame() {
  stopTimer();
  playing = false;
}

function startTimer(startTime) {
  // If there is an existing timer interval running, clear it out so we can start again.
  // (This scenario currently never happens, but it will happen if a feature is added
  // to restart the game.)
  if (timerInterval !== null) {
    clearInterval(timerInterval);
  }

  timerInterval = setInterval(updateTimer, 1000);
  updateTimer(); // runs immediately
}

function stopTimer() {
  clearInterval(timerInterval);
  timerInterval = null;
}

function updateTimer() {
  // Convert the time duration to separate variables for hours, minutes
  // and seconds. Taken from this Stack Overflow answer:
  // https://stackoverflow.com/a/5539081
  var hours = Math.floor(timerValue / 3600),
    minutes = Math.floor((timerValue % 3600) / 60);
  seconds = Math.floor((timerValue % 3600) % 60);

  // Now display the time in HH:MM:SS format.
  var newText =
    (hours ? (hours > 9 ? hours : "0" + hours) : "00") +
    ":" +
    (minutes ? (minutes > 9 ? minutes : "0" + minutes) : "00") +
    ":" +
    (seconds > 9 ? seconds : "0" + seconds);

  $("#timer").text(newText);
  timerValue++;
}

/*
 * This function is used to populate a cell button with the appropriate content
 * and also set the appropriate background color.
 
 * It takes an object representing the data about the cell as returned by the
 * "move.php" script on the server side. The keys are "x", "y", "revealed",
 * "flagged", "hasMine", and "neighborMineCount".
 *
 * See the documentation comments in move.php to find out more about what
 * these keys are for.  
 */
function populateCell(cellData) {
  // First, we need to *find* the button element in the document so that we
  // can start to update it. To do this, we build a CSS selctor that looks up
  // the cell by the data-x and data-y attributes.
  //
  // For example, if we want to look up the cell in position 3, 4, this is the
  // CSS selector that is built:
  //     button[data-x=3][data-y=4]
  var x = cellData.x,
    y = cellData.y,
    flagged = cellData.flagged,
    revealed = cellData.revealed;
  var cellSelector = 'button[data-x="' + x + '"][data-y="' + y + '"]';

  // Now we use the jQuery $ function to look up the element using the
  // selector we just made. Note that when you use the $ function to look
  // up something in the page, it doesn't just return an element. It returns
  // a *jQuery array*, which is an array containing all of the results found.
  // (It's always an array, even if there is only one element.) jQuery also
  // adds a ton of methods to the array make it convenient to manipulate the
  // element or elements that you found.
  //
  // Some examples of methods added by jQuery would be the .css() method to get or
  // update a CSS style, or the .text() method to set the inside text of elements, etc.

  var $el = $(cellSelector);

  // OK, now we've got our button element! Time to start filling it in
  // based on the stuff inside the cellData object that we received
  // from the server.

  // If the cell is not revealed, empty it out and fill it with red (if it's flagged)
  // or blue (if it's not flagged).

  // Hack alert: we're storing data against DOM elements with jQuery to keep track of which
  // cells are flagged. Kinda gross, but probably better than adding global state.
  if (!revealed) {
    if (flagged) {
      $el.data("flagged", true);
      $el.text("").css("background", "red");
    } else {
      $el.data("flagged", false);
      $el.text("").css("background", "blue");
    }
    return;
  }

  // Cell is not revealed. If it has a mine, display a yellow "B."
  if (cellData.hasMine) {
    $el.css("background", "yellow").text("B");
    return;
  }

  // OK, the cell is revealed, it is not flagged, and it has no
  // mine. That means we want to display with the default background,
  // and inside we'll show the number of neighbors that have mines. If the
  // number of neighbors is 0, we display the cell empty.

  if (cellData.neighborMineCount === 0) {
    var cellContent = "";
  } else {
    var cellContent = cellData.neighborMineCount;
  }
  $el.text(cellContent);
  $el.css("background", "");
}

function populateBoard(board) {
  // This steps through the JSON array representing the board, which we
  // received from the move.php script.
  //
  // For every cell object in the array, it calls populateCell to process
  // the contents of that cell object and fill in the appropriate button element
  // on the page.
  board.forEach(function(row) {
    row.forEach(function(cellData) {
      populateCell(cellData);
    });
  });
}

function handleCellClicked(el, x, y) {
  // Perform a request to our "move.php" script to make a move.
  //
  // As parameters, we give the script x and y coordinates. If the
  // game is in flagging mode, we also include a parameter "toggleflag"
  // to tell the server that we want to flip the "flagged" status on
  // the cell instead of performing a normal move.
  //
  // We provide a short function for jQuery to run once the result comes in.
  // jQuery will call this function with the JSON array that we get back from
  // the server.
  //
  // We also provide jQuery the parameter "dataType: 'json'", so it will
  // automatically convert the response from JSON to an actual JavaScript
  // value for us.

  // If the cell is flagged, we want to exit now (don't want people to
  // be able to click flagged cells). One exception: if we are in flagging mode,
  // do allow it, so the person can flip the button back.
  if ($(el).data("flagged") && !flagging) {
    return;
  }

  // If we are in flagging mode and the flag count has reached 0, bail out.
  if (flagging && flagCount <= 0) {
    return;
  }

  var dataToSend = {};
  dataToSend.x = x;
  dataToSend.y = y;
  if (flagging) {
    dataToSend.toggleflag = "1";
  }

  $.ajax({
    url: MOVE_SCRIPT_ROOT_URL + "move.php",
    method: "post",
    dataType: "json",
    data: dataToSend
  }).done(function(response) {
    var board = response.board,
      gameStage = response.gameStage;

    var wasPlaying = playing;

    playing = gameStage === "playing";

    // If the player wasn't playing the last click, but they are now, then
    // we start the timer.
    if (playing && !wasPlaying) {
      startTimer();
    }

    // Get and display flag count
    flagCount = getNumberOfFlags(board);
    $("#flagCount").text(flagCount);

    // Take the board array we received from the API, pull out the cell object at the appropriate
    // position, and then pass that cell object into populateCell to upate that cell on the screen.
    var clickedCell = board[y][x];

    populateCell(clickedCell);
    // If the game is now over. Stop the game interface.
    if (gameStage === "won" || gameStage === "lost") {
      stopGame();
     if(gameStage === "won")
      {
        $("#won").text("Win!");
      }
      if(gameStage === "lost")
      {
        $("#lost").text("Lost!");  
      }
    }
  });
}

function handleInitialApiResponse(response) {
  var board = response.board,
    timePlaying = response.timePlaying,
    gameStage = response.gameStage;

  // Populate some globals
  playing = gameStage === "playing";
  timerValue = timePlaying;
  flagCount = getNumberOfFlags(board);

  // If we're in the middle of a game, we start the timer.
  // Otherwise (if we're on a new game, or a previous one that's already been won/lost)
  // we just display the time in the box without starting the timer.

  if (gameStage === "playing") {
    startTimer(timePlaying);
  } else {
    updateTimer();
  }

  $("#flagCount").text(flagCount);

  populateBoard(response.board);
}

function startPage() {
  // To start off the page, use jQuery to load our "move.php"
  // script. We don't send any "x" or "y" parameters. This
  // means "don't actually make a move, just give me the current
  // board."
  //
  // We ask jQuery to call the populateBoard function with the
  // result.
  //
  // We also pass the "dataType: 'json'" parameter, which
  // tells jQuery to automatically parse the JSON of the
  // response and convert it to an actual JavaScript value
  // so we don't have to do it ourselves.

  $.ajax({
    url: MOVE_SCRIPT_ROOT_URL + "move.php",
    method: "post",
    dataType: "json"
  }).done(handleInitialApiResponse);
}

// This is the entry point of the code for the whole page.
// We call the "$" function and pass in our function "startPage".
// This tells jQuery to wait until all of the elements on the page
// are loaded and ready to go, and then call the startPage function.

$(startPage);