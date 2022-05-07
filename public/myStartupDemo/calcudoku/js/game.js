////////////////////////////////////////////////////////////
// GAME v1.1
////////////////////////////////////////////////////////////

/*!
 *
 * GAME SETTING CUSTOMIZATION START
 *
 */

var stageW=1280; //game width
var stageH=768; //game height
var portraitW=380; //game portrait width
var portraitH=650; //game portrait height
var fitToScreen = true; //fit to browser screen
var maintainAspectRatio = true; //maintain aspect ratio

var puzzleSettings = {
    borderSize:5, //cell border size
    borderDividerSize:15, //cell border wall size
    borderColour:'#000', //cell border colour
    colour:['#ff9d00','#2ce6ff','#00ff79','#fb6b4b','#f0cd0f','#6864ff','#efc37d','#b95bfc','#e9f734','#ff83fc','#ffbab2','#35e5bb','#c6c6c6'], //cell group colours
}

var timerSettings = {
                        status:true, //true or false to enable timer
                        mode:'countdown', //default or countdown mode
                        hintPenalty:5000, //hint penalty
                        timesupText:'TIME\'S UP', //time's up text display
                    };

//easy, normal, hard, expert mode settings
var puzzleLevelSettings = [
                            {size:3, group_limit:3, timer:50000}, //grid size, group limit, timer for countdown mode
                            {size:4, group_limit:3, timer:80000},
                            {size:5, group_limit:4, timer:120000},
                            {size:6, group_limit:5, timer:250000},
                        ]

var enableHintButton = true; //toggle Hint button (true, false)
var enableSolveButton = true; //toggle Solve button (true, false)
var hintTips = 'Give me hint (-5s penalty).'; //hint tips
var solveTips = 'See solution.'; //solve tips

var instructionText = 'FILL IN THE GRID'; //instruction text display
var resultCompleteText = 'CALCUDOKU COMPLETED!'; //puzzle complete text display
var resultInCompleteText = 'BETTER LUCK NEXT TIME!'; //puzzle incomplete text display

//Social share, [SCORE] will replace with game score
var shareEnable = true; //toggle share
var shareText = 'Share your score'; //social share guide
var shareTitle = 'Highscore on CalcuDoku Game is [SCORE]';//social share score title
var shareMessage = '[SCORE] is mine new highscore on CalcuDoku Game! Try it now!'; //social share score message

/*!
 *
 * GAME SETTING CUSTOMIZATION END
 *
 */
var playerData = {timer:0};
var gameData = {page:'', mode:'landscape', completed:false, failed:false, solved:false};
var timeData = {enable:false, startDate:null, nowDate:null, timer:0, countdown:0, penalty:0};

var puzzleData = {
                    size:5,
                    borders:[],
                    groups:[],
                    rows:[],
                    cells:[],
                    group_limit:3,
                    solved:false,
                    result:false,
                    hint:false,
                    error:false,
                    colour:[]
                }

var puzzleFontSize = [
                    {size:3, number:100, rule:60},
                    {size:4, number:80, rule:50},
                    {size:5, number:65, rule:40},
                    {size:6, number:50, rule:30}
                ];

/*!
 *
 * GAME BUTTONS - This is the function that runs to setup button event
 *
 */
function buildGameButton(){
    puzzleData.colour = puzzleSettings.colour;

    toggleConfirm(false);
    toggleTutorial(false);

    $('.gameGuideStatus').html(instructionText);
    $('.gameResultStatus').html(resultCompleteText);
    $('.gameShareStatus').html(shareText);

    $('#buttonStart').click(function() {
        playSound('soundClick');
        toggleGameMode(true);
    });

    $('#buttonGameEasy').click(function() {
        playSound('soundClick');
        getSettings(0)
        goPage('game');
    });

    $('#buttonGameMedium').click(function() {
        playSound('soundClick');
        getSettings(1)
        goPage('game');
    });

    $('#buttonGameHard').click(function() {
        playSound('soundClick');
        getSettings(2)
        goPage('game');
    });

    $('#buttonGameExpert').click(function() {
        playSound('soundClick');
        getSettings(3)
        goPage('game');
    });

    $('#buttonHowToPlay, #buttonTutorial').click(function() {
        playSound('soundClick');
        toggleTutorial(true);
        toggleGameOption(false);
    });

    $('#buttonHowToPlayOk').click(function() {
        playSound('soundClick');
        toggleTutorial(false);
    });

    $('#buttonOk').click(function() {
        playSound('soundClick');
        toggleConfirm(false);
        stopGame();
        goPage('main');
    });

    $('#buttonCancel').click(function() {
        playSound('soundClick');
        toggleConfirm(false);
    });

    $('#buttonContinue').click(function() {
        playSound('soundClick');
        goPage('main');
    });

    $('#buttonFacebook').click(function() {
        share('facebook');
    });

    $('#buttonTwitter').click(function() {
        share('twitter');
    });

    $('#buttonWhatsapp').click(function() {
        share('whatsapp');
    });

    $('#buttonOption').click(function() {
        playSound('soundClick');
        toggleGameOption();
    });

    $('#buttonSound').click(function() {
        playSound('soundClick');
        toggleGameMute();
    });

    $('#buttonFullscreen').click(function() {
        playSound('soundClick');
        toggleFullScreen();
    });

    $('#buttonExit').click(function() {
        playSound('soundClick');
        toggleGameOption();
        toggleConfirm(true);
    });

    $(window).focus(function() {
        //resizeGameDetail();
    });
}


/*!
 *
 * DISPLAY PAGES - This is the function that runs to display pages
 *
 */
function goPage(page){
    gameData.page = page;
    $('#logoHolder').hide();
    $('#gameHolder').hide();
    $('#resultHolder').hide();
    $('#buttonExit').show();
    $('#buttonTutorial').show();

    var targetContainer = ''
    switch(page){
        case 'main':
            targetContainer = $('#logoHolder');
            $('#buttonExit').hide();
            $('#buttonTutorial').hide();
            toggleGameMode(false);
        break;

        case 'game':
            targetContainer = $('#gameHolder');
            startGame();
        break;
    }

    targetContainer.show();
    TweenMax.to(targetContainer, 0, {opacity:0, overwrite:true});
    TweenMax.to(targetContainer, 1, {opacity:1, overwrite:true});

    toggleGameOption(false);
    resizeGameDetail();
}

function toggleGameMode(con){
    $('#buttonStart').hide();
    $('#buttonHowToPlay').hide();
    $('#buttonGameEasy').hide();
    $('#buttonGameMedium').hide();
    $('#buttonGameHard').hide();
    $('#buttonGameExpert').hide();
    $('#logoHolder .action').removeClass('level');

    if(con){
        if(gameData.mode == 'landscape'){
            $('#logoHolder .action').addClass('level');
        }
        $('#buttonGameEasy').show();
        $('#buttonGameMedium').show();
        $('#buttonGameHard').show();
        $('#buttonGameExpert').show();
    }else{
        $('#buttonStart').show();
        $('#buttonHowToPlay').show();
    }
}

/*!
 *
 * START GAME - This is the function that runs to start play game
 *
 */
function startGame(){
    shuffleClass();
    playSound('soundStart');
    $('.gameButtonGuideStatus').html('').hide();

    toggleResult(false);
    toggleGameTimer(true);
    generatePuzzle();
}

 /*!
 *
 * STOP GAME - This is the function that runs to stop play game
 *
 */
function stopGame(){
    stopAudio();
    TweenMax.killAll(false, true, false);
}

function saveGame(score, type){
    /*$.ajax({
      type: "POST",
      url: 'saveResults.php',
      data: {score:score},
      success: function (result) {
          console.log(result);
      }
    });*/
}

function getSettings(type){
    puzzleData.size = puzzleLevelSettings[type].size;
    puzzleData.group_limit = puzzleLevelSettings[type].group_limit;
    timeData.countdown = puzzleLevelSettings[type].timer;
}

/*!
 *
 * GENERATE BOARD - This is the function that runs to generate board
 *
 */
function generateBoard(){
    var boardPuzzle = $('#puzzleTable');
    boardPuzzle.html("");

    puzzleData.borders = [];
    for (var row = 0; row < puzzleData.size + 1; row++) {
        puzzleData.borders.push([]);
        for (var col = 0; col < puzzleData.size + 1; col++) {
            puzzleData.borders[row].push(false);
        }
    }

    // reset rows
    puzzleData.rows = [];
    puzzleData.cells = [];
    puzzleData.groups = [];

    for (var row = 0; row < puzzleData.size; row++) {
        puzzleData.rows.push([]);

        var tableRow = $(document.createElement('tr'));
        tableRow.addClass('row');
        boardPuzzle.append(tableRow);

        for (var col = 0; col < puzzleData.size; col++) {
            var i = row * puzzleData.size + col;
            var tableCell = new createCell(row, col, i);
            puzzleData.rows[row][col] = tableCell;
            puzzleData.cells[i] = tableCell;
            tableRow.append(tableCell.div);
        }
    }
    updateGroups();
    updateInputs();
}

/*!
 *
 * UPDATE INPUTS - This is the function that runs to update inputs
 *
 */
function updateInputs(){
    $('.gameChoiceWrapper ul').empty();
    for (var i = 0; i < puzzleData.size; i++) {
        var numberHTML = '';
        numberHTML += '<li class="buttonClick" data-calcudoku="'+(i+1)+'">';
        numberHTML += '<div class="gameChoiceNumber resizeFont" data-fontSize="50" data-lineHeight="50">'+(i+1)+'</div>';
        numberHTML += '<img src="/myStartupDemo/calcudoku/assets/item_number_bg.svg" />';
        numberHTML += '</li>';

        $('.gameChoiceWrapper ul').append(numberHTML);
    }

    //remove
    var numberHTML = '';
    numberHTML += '<li class="buttonClick" data-calcudoku="">';
    numberHTML += '<div class="gameChoiceNumber resizeFont" data-fontSize="50" data-lineHeight="50">X</div>';
    numberHTML += '<img src="/myStartupDemo/calcudoku/assets/item_number_bg.svg" />';
    numberHTML += '</li>';
    $('.gameChoiceWrapper ul').append(numberHTML);

    //hint
    if(enableHintButton){
        var numberHTML = '';
        numberHTML += '<li class="buttonClick" data-calcudoku="HINT">';
        numberHTML += '<img src="/myStartupDemo/calcudoku/assets/button_hint.svg" />';
        numberHTML += '</li>';
        $('.gameChoiceWrapper ul').append(numberHTML);
    }

    //solve
    if(enableSolveButton){
        var numberHTML = '';
        numberHTML += '<li class="buttonClick" data-calcudoku="SOLVE">';
        numberHTML += '<img src="/myStartupDemo/calcudoku/assets/button_solve.svg" />';
        numberHTML += '</li>';
        $('.gameChoiceWrapper ul').append(numberHTML);
    }


    $('.gameChoiceWrapper li').each(function(index, element) {
        $(this).click(function() {
            playSound('soundSelect');
            var currentValue = $(this).attr('data-calcudoku');

            if(currentValue == 'SOLVE'){
                displayGroupSolve();
            }else if(currentValue == 'HINT'){
                findNextHint();
            }else{
                $('.currentFocus .numberWrapper').html(currentValue);
                checkCellError();
                checkCellComplete();
            }
        });

        $(this).hover(
           function () {
              var currentValue = $(this).attr('data-calcudoku');
               if(currentValue == 'SOLVE'){
                    $('.gameButtonGuideStatus').html(solveTips).fadeIn();
                }else if(currentValue == 'HINT'){
                    $('.gameButtonGuideStatus').html(hintTips).fadeIn();
                }else{
                    $('.gameButtonGuideStatus').html('').hide();
                }
           },

           function () {
              var currentValue = $(this).attr('data-calcudoku');
               $('.gameButtonGuideStatus').html('').hide();
           }
        );
    });

    $("body").keypress(function(e){
        if (e.shiftKey || e.ctrlKey || e.altKey) {
            e.preventDefault();
        } else {
        var key = e.keyCode;
            if (!((key == 8) || (key == 46) || (key >= 35 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105))) {
                e.preventDefault();
            }
        }

        if(gameData.completed){
            return;
        }

        var value = e.key;
        if(!isNaN(value)){
            if(value.length > 1){
                value = Number(value.substring(1,2));
            }

            if (value < 1 && isNaN(value)){
                value = 1;
            }

            if (value > puzzleData.size){
                value = puzzleData.size;
            }

            playSound('soundSelect');
            $('.currentFocus .numberWrapper').html(value);
            checkCellError();
            checkCellComplete();
        }
    });

    $('.numberWrapper').each(function(index, element) {
        $(this).click(function() {
            if(gameData.completed){
                return;
            }

            $('.numberWrapper').each(function(index, element) {
                $(this).parent().removeClass('currentFocus');
            });
            $(this).parent().addClass('currentFocus');
        });
    });
}

/*!
 *
 * CHECK CELL ERROR - This is the function that runs to check cell error
 *
 */
function checkCellError(){
    puzzleData.error = false;
    $('.cell').removeClass('error');

    for (var i = 0; i < puzzleData.rows.length; i++) {
        var in_row = [];
        for (var j = 0; j < puzzleData.rows.length; j++) {
            var value = puzzleData.rows[i][j].div.find('.numberWrapper').html();

            if(value != ''){
                var checkIndex = jQuery.inArray(value, in_row);
                if(checkIndex != -1 && value != -1){
                     puzzleData.error = true;
                     puzzleData.rows[i][j].div.addClass('error');
                     puzzleData.rows[i][checkIndex].div.addClass('error');
                }
            }
            in_row.push(value);
        }
    }

    for (var i = 0; i < puzzleData.rows.length; i++) {
        var in_col = [];
        for (var j = 0; j < puzzleData.rows.length; j++) {
            var value = puzzleData.rows[j][i].div.find('.numberWrapper').html();

            if(value != ''){
                var checkIndex = jQuery.inArray(value, in_col);
                if(checkIndex != -1 && value != -1){
                     puzzleData.error = true;
                     puzzleData.rows[j][i].div.addClass('error');
                     puzzleData.rows[checkIndex][i].div.addClass('error');
                }
            }
            in_col.push(value);
        }
    }

    if(puzzleData.error)
        playSound('soundError');
}

/*!
 *
 * CHECK CELL COMPLETE - This is the function that runs to check if puzzle is complete
 *
 */
function checkCellComplete(){
    var currentLength = $('.numberWrapper').length;
    var completeLength = 0;

    $('.numberWrapper').each(function(index, element) {
        var currentValue = $(this).html();
        if(currentValue != '' && !isNaN(currentValue)){
            completeLength++;
        }
    });

    if(currentLength == completeLength && !puzzleData.error){
        if(!puzzleData.solved){
            groupSolve();
        }else{
            checkPuzzleSolve();
        }
    }
}

/*!
 *
 * CHECK PUZZLE SOLVE - This is the function that runs to check if puzzle is solve
 *
 */
function checkPuzzleSolve(){
    checkCellError();

    var correctGroupLength = 0;
    for (var i = 0; i < puzzleData.groups.length; i++) {
        var array = [];
        for (var m = 0; m < puzzleData.groups[i].members.length; m++) {
            var row = puzzleData.groups[i].members[m].row;
            var col = puzzleData.groups[i].members[m].col;
            array.push(puzzleData.rows[row][col].div.find('.numberWrapper').html());
        }

        for (var p = 0; p < puzzleData.groups[i].possible.length; p++) {
            var correctCount = 0;
            for (var v = 0; v < puzzleData.groups[i].possible[p].length; v++) {
                if(puzzleData.groups[i].possible[p][v] == array[v]){
                    correctCount++;
                }
            }

            if(correctCount == puzzleData.groups[i].possible[p].length){
                p = puzzleData.groups[i].possible.length;
                correctGroupLength++;
            }
        }
    }

    if(correctGroupLength == puzzleData.groups.length){
        toggleGameTimer(false);
        toggleResult(true);
    }
}

/*!
 *
 * CREATE CELL & GROUP - This is the function that runs to create cell and group
 *
 */
function createCell(row, col, index) {
    this.row = row;
    this.col = col;
    this.index = index;
    this.group = null;
    this.sol = function(solution) {
        return solution[this.row][this.col];
    };
    this.possible = [];
    this.addPossibleChoices = function(arr) {
        for (var i = 0; i < arr.length; i++) {
            if (jQuery.inArray(arr[i], this.possible) == -1) {
                this.possible.push(arr[i]);
            }
        }
    };
    // group related bits

    this.border_top = false;
    this.border_right = false;

    this.div = $(document.createElement("td")).attr({
            class: "cell buttonClick",
            id: "c" + row + "c" + col,
            row: row,
            col: col
        });

    var sizeIndex = 0;
    for(var n=0; n<puzzleFontSize.length; n++){
        if(puzzleData.size == puzzleFontSize[n].size){
           sizeIndex = n;
        }
    }

    this.bg_div = $(document.createElement("div"));
    this.value_div = $(document.createElement("div"));
    this.rule_div = $(document.createElement("div"));
    this.bg_div.addClass('bgWrapper');
    this.value_div.addClass('numberWrapper');
    this.value_div.addClass('resizeFont');
    this.value_div.attr('data-fontSize', puzzleFontSize[sizeIndex].number);
    this.value_div.attr('data-lineHeight', puzzleFontSize[sizeIndex].number * 2);
    this.rule_div.addClass('ruleWrapper');
    this.rule_div.addClass('resizeFont');
    this.rule_div.attr('data-fontSize', puzzleFontSize[sizeIndex].rule);
    this.rule_div.attr('data-lineHeight', puzzleFontSize[sizeIndex].rule);

    this.div.append(this.bg_div);
    this.div.append(this.value_div);
    this.div.append(this.rule_div);
}

function createGroup() {
    this.goal = 0;
    this.index = -1;
    this.operation = "+";

    // The cell containing the description.
    this.topmost = null;
    this.members = [];

    this.add = function(cell) {
        cell.group = this;
        $.each(puzzleData.colour, function(i, v){
           cell.bg_div.css('background', 'transparent');
        });
        cell.bg_div.css('background', puzzleData.colour[puzzleData.classShuffle[puzzleData.classNum]]);
        this.members.push(cell);
    }
    this.addAll = function(all) {
        for (var i = 0; i < all.length; i++) {
            this.add(all[i]);
        }
    }

    puzzleData.classNum++;
    if(puzzleData.classNum > puzzleData.classShuffle.length-1){
        shuffleClass();
    }
}

function shuffleClass(){
    puzzleData.classNum = 0;
    puzzleData.classShuffle = [];
    for(var n=0; n<puzzleData.colour.length; n++){
        puzzleData.classShuffle.push(n);
    }
    shuffle(puzzleData.classShuffle);
}

/*!
 *
 * GENERATE PUZZLE - This is the function that runs to create puzzle
 *
 */
function generatePuzzle() {
    puzzleData.solved = false;
    puzzleData.result = false;
    puzzleData.hint = false;

    generateBoard();

    for (var c = 0; c < puzzleData.cells.length; c++) {
        puzzleData.cells[c].group = null;
    }
    puzzleData.groups = [];

    // create groups
    var visited = []
    for (var c = 0; c < puzzleData.cells.length; c++) {
        var cell = puzzleData.cells[c];
        if (cell.group === null) {
            // add the rows to the group
            var g = new createGroup();
            g.topmost = cell;
            g.add(cell);
            var g_cell = cell;
            while (g.members.length < puzzleData.group_limit) {
                var r = Math.random();
                var neighbors = [];
                var addNeighbor = function(row, col) {
                    try {
                        var neighbor = puzzleData.rows[g_cell.row + row][g_cell.col + col];
                        if (neighbor.group == null) {
                            neighbors.push(neighbor);
                        }
                    } catch (err) {}
                };
                addNeighbor(0, -1); // left
                addNeighbor(0, 1); // right
                addNeighbor(1, 0); // top
                addNeighbor(-1, 0); // bottom

                if (neighbors.length == 0 || Math.random() < 0.3) {
                    break;
                }
                var pick = neighbors[Math.floor(Math.random() * neighbors.length)];
                g.add(pick);
                g_cell = pick;
            }
            // update borders
            for (var i = 0; i < g.members.length; i++) {
                var m = g.members[i];
                if (m.row > 0 && puzzleData.rows[m.row - 1][m.col].group != g) {
                    m.border_top = true;
                }
                if (m.col < puzzleData.rows.length - 1 && puzzleData.rows[m.row][m.col + 1].group != g) {
                    m.border_right = true;
                }
            }
            puzzleData.groups.push(g);
        }
    }

    var solution = [];
    var c = 0;
    for (var i = 0; i < puzzleData.rows.length; i++) {
        solution.push([]);
        for (var j = 1; j < puzzleData.rows.length + 1; j++) {
            solution[i].push(((c + i) % puzzleData.rows.length) + 1);
            c++;
        }
    }
    // randomize the rows
    for (var i = 0; i < 10; i++) {
        var from_row = Math.floor(Math.random() * puzzleData.rows.length);
        var to_row = Math.floor(Math.random() * puzzleData.rows.length);
        var temp = solution[from_row];
        solution[from_row] = solution[to_row];
        solution[to_row] = temp;
    }
    // randomize the columns
    for (var i = 0; i < 10; i++) {
        var from_col = Math.floor(Math.random() * puzzleData.rows.length);
        var to_col = Math.floor(Math.random() * puzzleData.rows.length);
        var temp;
        for (var j = 0; j < puzzleData.rows.length; j++) {
            temp = solution[j][from_col];
            solution[j][from_col] = solution[j][to_col];
            solution[j][to_col] = temp;
        }
    }

    // Choose an operation and calculate target
    var group_solutions = function(group) {
        var sols = [];
        for (var i = 0; i < group.members.length; i++) {
            sols.push(solution[group.members[i].row][group.members[i].col]);
        }
        return sols;
    };
    for (var i = 0; i < puzzleData.groups.length; i++) {
        var g = puzzleData.groups[i];
        var s = group_solutions(g);
        if (g.members.length === 1) {
            g.operation = "+";
        } else {
            var possible = ["+", "*"];
            if (g.members.length === 2) {
                possible.push("-");
                if ((s[0] / s[1]) == Math.floor(s[0] / s[1]) ||
                    (s[1] / s[0]) == Math.floor(s[1] / s[0])) {
                    possible.push("/");
                }
            }
            g.operation = possible[Math.floor(Math.random() * possible.length)];
        }
        if (g.operation == "-") {
            g.goal = (s[0] - s[1]) > 0 ? (s[0] - s[1]) : (s[1] - s[0]);
        } else if (g.operation == "/") {
            if (s[0] / s[1] == Math.floor(s[0] / s[1])) {
                g.goal = s[0] / s[1];
            } else {
                g.goal = s[1] / s[0];
            }
        } else if (g.operation == "*") {
            g.goal = 1;
            for (var j = 0; j < s.length; j++) {
                g.goal *= s[j];
            }
        } else if (g.operation == "+") {
            g.goal = 0;
            for (var j = 0; j < s.length; j++) {
                g.goal += s[j];
            }
        }
    }

    updateBorders();
    updateRules();
}

/*!
 *
 * GROUP CELLS - This is the function that runs to find group cells
 *
 */
function findNeighbors(cell, neighbors) {
    // banking on the fact that all top-row cells have a top border
    if (!neighbors) {
        neighbors = [cell];
    }
    if (!cell.border_top && cell.row > 0) {
        findNeighborsPush(puzzleData.rows[cell.row - 1][cell.col], neighbors);
    }
    if (!cell.border_right && cell.col < puzzleData.rows.length - 1) {
        findNeighborsPush(puzzleData.rows[cell.row][cell.col + 1], neighbors);
    }
    if (cell.row < puzzleData.rows.length - 1) {
        var below = puzzleData.rows[cell.row + 1][cell.col];
        if (!below.border_top) {
            findNeighborsPush(below, neighbors);
        }
    }
    if (cell.col > 0) {
        var left = puzzleData.rows[cell.row][cell.col - 1];
        if (!left.border_right) {
            findNeighborsPush(left, neighbors);
        }
    }
    return neighbors;
}

function findNeighborsPush(cell, neighbors) {
    if (jQuery.inArray(cell, neighbors) == -1) {
        neighbors.push(cell);
        findNeighbors(cell, neighbors);
    }
}

/*!
 *
 * UPDATE GROUPS - This is the function that runs to update cell groups
 *
 */
function updateGroups() {
    var old_groups = puzzleData.groups;
    puzzleData.groups = [];

    // get the list of groups from the UI.
    var g = new createGroup();
    var visited = [];
    for (var i = 0; i < puzzleData.cells.length; i++) {
        var cell = puzzleData.cells[i];

        if (jQuery.inArray(cell, visited) == -1) {
            var neighbors = findNeighbors(cell, null);
            g.addAll(neighbors);
            g.topmost = cell;
            g.index = puzzleData.groups.length;
            puzzleData.groups.push(g);
            for (var n = 0; n < neighbors.length; n++) {
                neighbors[n].group = g;
            }
            visited = jQuery.merge(visited, neighbors);
            // start again for next group
            g = new createGroup();
        }
    }
    var num_groups = Math.min(old_groups.length, puzzleData.groups.length);
    for (var i = 0; i < num_groups; i++) {
        puzzleData.groups[i].goal = old_groups[i].goal;
        puzzleData.groups[i].operation = old_groups[i].operation;
    }

    updateBorders();
    updateRules();
}

/*!
 *
 * UPDATE BORDERS - This is the function that runs to update cell borders
 *
 */
function updateBorders() {
    for (var row = 0; row < puzzleData.rows.length; row++) {
        for (var col = 0; col < puzzleData.rows[row].length; col++) {
            // Update cell border
            if (row == 0 || puzzleData.rows[row][col].border_top) {
                puzzleData.rows[row][col].div.addClass('border-top');
            } else {
                puzzleData.rows[row][col].div.removeClass('border-top');
            }
            if (col == puzzleData.rows[row].length - 1 || puzzleData.rows[row][col].border_right) {
                puzzleData.rows[row][col].div.addClass('border-right');
            } else if (col != puzzleData.rows[row].length) {
                puzzleData.rows[row][col].div.removeClass('border-right');
            }
            if (col == 0) {
                puzzleData.rows[row][col].div.addClass('border-left');
            } else {
                puzzleData.rows[row][col].div.removeClass('border-left');
            }
            if (row == puzzleData.rows.length - 1) {
                puzzleData.rows[row][col].div.addClass('border-bottom');
            } else {
                puzzleData.rows[row][col].div.removeClass('border-bottom');
            }
        }
    }
}

/*!
 *
 * UPDATE RULES - This is the function that runs to update rules
 *
 */
function updateRules() {
    for (var i = 0; i < puzzleData.cells.length; i++) {
        var cell = puzzleData.cells[i];
        cell.rule_div.html("");
        if (cell.group.topmost == cell) {
            cell.rule_div.append(cell.group.goal + " " + cell.group.operation);
        }
    }
}

function findNextHint(){
    puzzleData.hint = true;

    if(!puzzleData.solved){
        groupSolve();
    }else{
        displayHint();
    }
}

function displayHint(){
    var hintFound = false;

    for (var i = 0; i < puzzleData.rows.length; i++) {
        for (var j = 0; j < puzzleData.rows.length; j++) {
            var currentValue = Number(puzzleData.rows[i][j].div.find('.numberWrapper').html());
            if(currentValue != puzzleData.rows[i][j].div.solved){
                puzzleData.rows[i][j].div.find('.numberWrapper').html(puzzleData.rows[i][j].div.solved);
                i = puzzleData.rows.length;
                j = puzzleData.rows.length;
                hintFound = true;
            }
        }
    }
    puzzleData.hint = false;

    if(hintFound){
        timeData.penalty += timerSettings.hintPenalty;
        checkCellError();
        checkCellComplete();
    }
}

/*!
 *
 * DISPLAY GROUP SOLVE - This is the function that runs to reveal puzzle solution
 *
 */
function displayGroupSolve(){
    gameData.solved = true;
    puzzleData.result = true;
    groupSolve();
}

function checkCurrentSolve(){
    getAllGroupSolutions();
    puzzleData.groups = puzzleData.groups.sort(function(a, b) {
        if (a.possible.length < b.possible.length) {
            return -1;
        } else if (a.possible.length == b.possible.length) {
            return 0;
        }
        return 1;
    });
}

function groupSolve(){
    var start = new Date();
    getAllGroupSolutions();
    puzzleData.groups = puzzleData.groups.sort(function(a, b) {
        if (a.possible.length < b.possible.length) {
            return -1;
        } else if (a.possible.length == b.possible.length) {
            return 0;
        }
        return 1;
    });

    var searchspace = 1;
    var group_nums = "";
    for (var i = 0; i < puzzleData.groups.length; i++) {
        searchspace *= puzzleData.groups[i].possible.length;
        group_nums += puzzleData.groups[i].possible.length + " ";
    }

    var gindices = [];
    var solution = [];
    for (var i = 0; i < puzzleData.rows.length; i++) {
        solution.push([]);
        for (var j = 0; j < puzzleData.rows.length; j++) {
            solution[i].push(0);
        }
    }
    var fill_solution = function() {
        for (var i = 0; i < gindices.length; i++) {
            var group = puzzleData.groups[i];
            for (var c = 0; c < group.members.length; c++) {
                var cell = group.members[c];
                solution[cell.row][cell.col] = group.possible[gindices[i]][c];
            }
        }
        for (var j = gindices.length; j < puzzleData.groups.length; j++) {
            var group = puzzleData.groups[j];
            for (var i = 0; i < group.members.length; i++) {
                var cell = group.members[i];
                solution[cell.row][cell.col] = 0;
            }
        }
    };
    var visited = new Set();
    var to_visit = new Stack();
    for (var i = 0; i < puzzleData.groups[0].possible.length; i++) {
        to_visit.push([i]);
    }
    var solved = false;
    var traversed = 0;
    while (to_visit.length > 0) {
        gindices = to_visit.pop();
        traversed += 1;
        fill_solution();
        if (gindices.length == puzzleData.groups.length) {
            if (checkAll(solution)) {
                var solved = true;
                drawSolution(solution);
                break;
            } else {
                continue;
            }
        } else {
            if (!visited.contains(gindices) && checkRows(solution) && checkCols(solution)) {
                visited.add(gindices);
                var pos = gindices.length;
                gindices.push(0);
                for (var i = 0; i < puzzleData.groups[pos].possible.length; i++) {
                    gindices[pos] = i;
                    to_visit.push(gindices);
                }
            }
        }
    }
    var end = new Date();
    if (!solved) {
        alert("No solution!");
    } else {
        puzzleData.solved = true;
        if(puzzleData.hint){
            displayHint();
        }else{
            checkPuzzleSolve();
        }
        console.log("Solved in " + ((end - start) / 1000.) + " seconds.");
    }
}

function copyArray(arr) {
    var copy = [];
    for (var i = 0; i < arr.length; i++) {
        copy.push(arr[i]);
    }
    return copy;
}
function Set() {
    var visited = [];
    this.add = function(thing) {
        if (!this.contains(thing)) {

            visited.push(copyArray(thing));
        }
    };
    this.contains = function(thing) {
        return jQuery.inArray(thing, visited) != -1;
    };
}
function Stack() {
    var stack = [];
    this.length = 0;
    this.push = function(arr) {
        stack.push(copyArray(arr));
        this.length += 1;
    }
    this.pop = function(arr) {
        this.length -= 1;
        return stack.pop();
    }
}
function copyNumSort(array) {
    var copy = array.slice();
    copy.sort(function(a, b) { return a < b ? -1 : (a > b ? 1 : 0) });
    return copy;
}
function getAllGroupSolutions() {
    for (var g = 0; g < puzzleData.groups.length; g++) {
        var group = puzzleData.groups[g];
        var combine;
        if (group.operation == "-") {
            combine = function(choices) {
                choices = copyNumSort(choices);
                var val = choices[choices.length-1];
                for (var i = choices.length - 2; i >= 0; i--) {
                    val -= choices[i];
                }
                return val;
            }
        } else if (group.operation == "*") {
            combine = function(choices) {
                var total = 1;
                for (var i = 0; i < choices.length; i++) {
                    total *= choices[i];
                }
                return total;
            }
        } else if (group.operation == "/") {
            combine = function(choices) {
                choices = copyNumSort(choices);
                var val = choices[choices.length-1];
                for (var i = choices.length - 2; i >= 0; i--) {
                    val /= choices[i];
                }
                return val;
            }
        } else {
            combine = function(choices) {
                var total = 0;
                for (var i = 0; i < choices.length; i++) {
                    total += choices[i];
                }
                return total;
            }
        }
        var choices = [];
        var max = puzzleData.rows.length;
        var min = 1;
        for (var i = 0; i < group.members.length; i++) {
            choices.push(min);
        }
        var incomplete = 1;
        group.possible = [];
        var check_solution = function(choices) {
            if (combine(choices) != group.goal) {
                return false;
            }
            var solution = [];
            for (var i = 0; i < puzzleData.rows.length; i++) {
                solution.push([]);
                for (var j = 0; j < puzzleData.rows.length; j++) {
                    solution[i].push(0);
                }
            }
            for (var i = 0; i < choices.length; i++) {
                solution[group.members[i].row][group.members[i].col] = choices[i];
            }
            return checkRows(solution) && checkCols(solution);
        };
        while (incomplete) {
            if (check_solution(choices)) {
                group.possible.push(copyArray(choices));
            }
            var p = 0;
            choices[p] += 1;
            while (choices[p] > max) {
                choices[p] = min;
                p += 1;
                if (p >= choices.length) {
                    incomplete = 0;
                } else {
                    choices[p] += 1;
                }
            }
        }
    }

}
function constructGroupConstrainedChoices() {
    for (var g = 0; g < puzzleData.groups.length; g++) {
        var group = puzzleData.groups[g];
        var combine;
        if (group.operation == "-") {
            combine = function(choices) {
                choices = copyNumSort(choices);
                var total = choices[choices.length - 1];
                for (var i = choices.length - 2; i >= 0; i--) {
                    total -= choices[i];
                }
                return total;
            }
        } else if (group.operation == "*") {
            combine = function(choices) {
                var total = 1;
                for (var i = 0; i < choices.length; i++) {
                    total *= choices[i];
                }
                return total;
            }
        } else if (group.operation == "/") {
            combine = function(choices) {
                choices = copyNumSort(choices);
                var total = choices[choices.length - 1];
                for (var i = 0; i < choices.length; i++) {
                    total /= choices[i];
                }
                return total;
            }
        } else {
            combine = function(choices) {
                var total = 0;
                for (var i = 0; i < choices.length; i++) {
                    total += choices[i];
                }
                return total;
            }
        }
        var choices = [];
        var max = puzzleData.rows.length;
        var min = 1;
        for (var i = 0; i < group.members.length; i++) {
            choices.push(min);
        }
        var incomplete = 1;
        while (incomplete) {
            if (combine(choices) == group.goal) {
                for (var m = 0; m < group.members.length; m++) {
                    group.members[m].addPossibleChoices(choices);
                }
            }
            var p = 0;
            choices[p] += 1;
            while (choices[p] > max) {
                choices[p] = min;
                p += 1;
                if (p >= choices.length) {
                    incomplete = 0;
                } else {
                    choices[p] += 1;
                }
            }
        }
    }
}

/*!
 *
 * DRAW SOLUTION - This is the function that runs to draw solution
 *
 */
function drawSolution(solution) {
    for (var i = 0; i < puzzleData.rows.length; i++) {
        for (var j = 0; j < puzzleData.rows.length; j++) {
            puzzleData.rows[i][j].div.solved = solution[i][j];
            if(puzzleData.result){
                puzzleData.rows[i][j].div.find('.numberWrapper').html(solution[i][j]);
            }
        }
    }
}

/*!
 *
 * CHECK PUZZLE VALIDITY - This is the function that runs to check if puzzle have a valid solution
 *
 */
function checkAll(solution) {
    return checkGroups(solution) && checkCols(solution) && checkRows(solution);
}
function checkGroups(solution) {
    for (var g = 0; g < puzzleData.groups.length; g++) {
        if (!checkGroup(puzzleData.groups[g], solution)) {
            return false;
        }
    }
    return true;
}
function checkGroup(group, solution) {
    if (group.members.length == 1) {
        return true;
    } else if (group.operation == '+') {
        var total = 0;
        for (var g = 0; g < group.members.length; g++) {
            total += group.members[g].sol(solution);
        }
        return total == group.goal;
    } else if (group.operation == '*') {
        var total = 1;
        for (var g = 0; g < group.members.length; g++) {
            total *= group.members[g].sol(solution);
        }
        return total == group.goal;
    } else {
        // For divide and subtract, arrange operands in descending order.
        var operands = [];
        for (var i = 0; i < group.members.length; i++) {
            operands.push(group.members[i].sol(solution));
        }
        operands = copyNumSort(operands);
        var val = operands[operands.length - 1];
        for (var i = operands.length - 2; i >= 0; i--) {
            if (group.operation == '-') {
                val -= operands[i];
            } else if (group.operation == '/') {
                val /= operands[i];
            }
        }
        return val == group.goal;
    }
    return false;
}

function checkRows(solution) {
    for (var row = 0; row < puzzleData.rows.length; row++) {
        var in_row = [];
        for (var col = 0; col < puzzleData.rows[row].length; col++) {
            var val = solution[row][col];
            if (val != 0 && jQuery.inArray(val, in_row) != -1) {
                return false;
            }
            in_row.push(val);
        }
    }
    return true;
}
function checkCols(solution) {
    for (var col = 0; col < puzzleData.rows.length; col++) {
        var in_col = [];
        for (var row = 0; row < puzzleData.rows.length; row++) {
            var val = solution[row][col];
            if (val != 0 && jQuery.inArray(val, in_col) != -1) {
                return false;
            }
            in_col.push(val);
        }
    }
    return true;
}

/*!
 *
 * GAME TIMER - This is the function that runs for game timer
 *
 */
function toggleGameTimer(con){
    if(!timerSettings.status){
        return;
    }

    if(con){
        timeData.penalty = 0;
        timeData.startDate = new Date();
        loopGameTimer();
    }else{

    }
    timeData.enable = con;
}

function loopGameTimer(){
    TweenMax.to(timeData, .2, {overwrite:true, onComplete:updateGameTimer});
}

function updateGameTimer(){
    if(!timeData.enable){
        return;
    }

    timeData.nowDate = new Date();
    var elapsedTime = (timeData.nowDate.getTime() - timeData.startDate.getTime());

    if(timerSettings.mode == 'default'){
        timeData.timer = Math.floor(elapsedTime + timeData.penalty);
    }else if(timerSettings.mode == 'countdown'){
        timeData.timer = Math.floor(((timeData.countdown)) - (elapsedTime + timeData.penalty))
    }

    playerData.timer = timeData.timer;
    $('.gameTimerStatus').html(millisecondsToTime(timeData.timer));

    var timerContinue = true;
    if(timerSettings.mode == 'countdown'){
        if(timeData.timer <= 0){
            timerContinue = false;
        }
    }

    if(timerContinue){
        loopGameTimer();
    }else{
        $('.gameTimerStatus').html(millisecondsToTime(0));

        gameData.failed = true;
        toggleResult(true);
    }
}

/*!
 *
 * TOGGLE RESULT - This is the function that runs to toggle result
 *
 */
function toggleResult(con){
    $('.gameChoiceHolder').hide();
    $('.gameResultHolder').hide();

    if(con){
        $('.numberWrapper').each(function(index, element) {
            $(this).parent().removeClass('currentFocus');
        });
        gameData.completed = true;
        $('.gameResultHolder').fadeIn();

        $('.resultOption').show();
        if(!shareEnable){
            $('.resultOption').hide();
        }

        stopGame();
        if(gameData.solved){
            playSound('soundOver');
            $('.resultOption').hide();
            $('.gameResultStatus').html(resultInCompleteText);
        }else if(gameData.failed){
            $('.gameResultStatus').html(timerSettings.timesupText);
            playSound('soundOver');
        }else{
            $('.gameResultStatus').html(resultCompleteText);
            playSound('soundComplete');
        }
    }else{
        gameData.completed = false;
        gameData.failed = false;
        gameData.solved = false;
        $('.gameChoiceHolder').fadeIn();
    }
}

function resizeGameDetail(){
    var curScalePercent = scalePercent;

    if(gameData.mode == 'portrait'){
        curScalePercent = scalePercent * .5;
        $('.gameTimerHolder').css('left', 'auto');

        $('.gameTimerStatus').attr('data-fontsize', 80);
        $('.gameTimerStatus').attr('data-lineheight', 160);

        $('.gameGuideStatus').attr('data-fontsize', 50);
        $('.gameGuideStatus').attr('data-lineheight', 50);

        $('.gameButtonGuideStatus').attr('data-fontsize', 40);
        $('.gameButtonGuideStatus').attr('data-lineheight', 40);
    }else{
        var targetPuzzle = $("#puzzleHolder").position();
        $('.gameTimerHolder').css('left', targetPuzzle.left + $("#puzzleHolder").outerWidth());

        $('.gameTimerStatus').attr('data-fontsize', 60);
        $('.gameTimerStatus').attr('data-lineheight', 120);

        $('.gameGuideStatus').attr('data-fontsize', 30);
        $('.gameGuideStatus').attr('data-lineheight', 30);

        $('.gameButtonGuideStatus').attr('data-fontsize', 25);
        $('.gameButtonGuideStatus').attr('data-lineheight', 25);
    }

    $('#puzzleTable .cell').each(function(index, element) {
        var currentWidth = $(this).outerWidth();
        $(this).css('height', currentWidth);
        $(this).css('border', puzzleSettings.borderColour+' solid '+(puzzleSettings.borderSize * curScalePercent)+'px');
    });

    $('.border-left').each(function(index, element) {
        $(this).css('border-left', puzzleSettings.borderColour+' solid '+(puzzleSettings.borderDividerSize * curScalePercent)+'px');
    });
    $('.border-right').each(function(index, element) {
        $(this).css('border-right', puzzleSettings.borderColour+' solid '+(puzzleSettings.borderDividerSize * curScalePercent)+'px');
    });
    $('.border-top').each(function(index, element) {
        $(this).css('border-top', puzzleSettings.borderColour+' solid '+(puzzleSettings.borderDividerSize * curScalePercent)+'px');
    });
    $('.border-bottom').each(function(index, element) {
        $(this).css('border-bottom', puzzleSettings.borderColour+' solid '+(puzzleSettings.borderDividerSize * curScalePercent)+'px');
    });

    resizeGameFonts();
}

/*!
 *
 * RESIZE GAME FONTS - This is the function that runs to resize game fonts
 *
 */
function resizeGameFonts(){
    var curScalePercent = scalePercent;
    if(gameData.mode == 'portrait'){
        curScalePercent = scalePercent * .5;
    }

    $('.resizeFont').each(function(index, element) {
        $(this).css('font-size', Math.round(Number($(this).attr('data-fontSize'))*curScalePercent)+'px');
        $(this).css('line-height', Math.round(Number($(this).attr('data-lineHeight'))*curScalePercent)+'px');
    });
}

/*!
 *
 * MILLISECONDS CONVERT - This is the function that runs to convert milliseconds to time
 *
 */
function millisecondsToTime(milli) {
    var milliseconds = milli % 1000;
    var seconds = Math.floor((milli / 1000) % 60);
    var minutes = Math.floor((milli / (60 * 1000)) % 60);

    if(seconds<10){
        seconds = '0'+seconds;
    }

    if(minutes<10){
        minutes = '0'+minutes;
    }

    return minutes+':'+seconds;
}


/*!
 *
 * TOGGLE CONFIRM - This is the function that runs to toggle confirm exit
 *
 */
function toggleConfirm(con){
    if(con){
        $('#confirmHolder').show();
    }else{
        $('#confirmHolder').hide();
    }
}

/*!
 *
 * TOGGLE TUTORIAL - This is the function that runs to toggle tutorial content
 *
 */
function toggleTutorial(con){
    if(con){
        $('#tutorialHolder').show();
    }else{
        $('#tutorialHolder').hide();
    }
}


/*!
 *
 * OPTIONS - This is the function that runs to mute and fullscreen
 *
 */
function toggleGameOption(con){
    if(con != undefined){
        $('#buttonOption').removeClass('buttonOptionOff');
        $('#buttonOption').removeClass('buttonOptionOn');

        if(!con){
            $('#buttonOption').addClass('buttonOptionOn');
            $('#optionList').show();
        }else{
            $('#buttonOption').addClass('buttonOptionOff');
            $('#optionList').hide();
        }
    }
    if($('#buttonOption').hasClass('buttonOptionOn')){
        $('#buttonOption').removeClass('buttonOptionOn');
        $('#buttonOption').addClass('buttonOptionOff');
        $('#optionList').hide();
    }else{
        $('#buttonOption').removeClass('buttonOptionOff');
        $('#buttonOption').addClass('buttonOptionOn');
        $('#optionList').show();
    }
}

function toggleGameMute(){
    if($('#buttonSound').hasClass('buttonSoundOn')){
        $('#buttonSound').removeClass('buttonSoundOn');
        $('#buttonSound').addClass('buttonSoundOff');
        toggleMute(true);
    }else{
        $('#buttonSound').removeClass('buttonSoundOff');
        $('#buttonSound').addClass('buttonSoundOn');
        toggleMute(false);
    }
}


function toggleFullScreen() {
  if (!document.fullscreenElement &&    // alternative standard method
      !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement ) {  // current working methods
    if (document.documentElement.requestFullscreen) {
      document.documentElement.requestFullscreen();
    } else if (document.documentElement.msRequestFullscreen) {
      document.documentElement.msRequestFullscreen();
    } else if (document.documentElement.mozRequestFullScreen) {
      document.documentElement.mozRequestFullScreen();
    } else if (document.documentElement.webkitRequestFullscreen) {
      document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
    }
  } else {
    if (document.exitFullscreen) {
      document.exitFullscreen();
    } else if (document.msExitFullscreen) {
      document.msExitFullscreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
      document.webkitExitFullscreen();
    }
  }
}


/*!
 *
 * SHARE - This is the function that runs to open share url
 *
 */
function share(action){
    gtag('event','click',{'event_category':'share','event_label':action});

    var loc = location.href
    loc = loc.substring(0, loc.lastIndexOf("/") + 1);

    var title = '';
    var text = '';

    title = shareTitle.replace("[SCORE]", millisecondsToTime(playerData.timer));
    text = shareMessage.replace("[SCORE]", millisecondsToTime(playerData.timer));
    var shareurl = '';

    if( action == 'twitter' ) {
        shareurl = 'https://twitter.com/intent/tweet?url='+loc+'&text='+text;
    }else if( action == 'facebook' ){
        shareurl = 'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(loc+'share.php?desc='+text+'&title='+title+'&url='+loc+'&thumb='+loc+'share.jpg&width=590&height=300');
    }else if( action == 'whatsapp' ){
        shareurl = "whatsapp://send?text=" + encodeURIComponent(text) + " - " + encodeURIComponent(loc);
    }

    window.open(shareurl);
}
