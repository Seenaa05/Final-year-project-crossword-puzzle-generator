(function($) {
  'use strict';
  
  var DIRECTION = { 
    LEFT: -1,
    NONE:  0,
    RIGHT: 1
  };
  
  var SLIDE_DURATION = 350; 
  
  

  function cUI(crossword) {
    this.crossword = crossword;
    this.$crossword = null;
  }
  

  
  
  var crosswords4UI = [];
  var currentCrosswordIndex = -1;
  var generatedAllAlternatives = false;


  $(document).ready(function() {
    

    
   
    $(window).resize(windowResizeHandler);
    $('button[name=add]').click(addWordClickHandler);
    $('button[name=remove]').click(removeWordClickHandler);
    $('button[name=clear]').click(clearClickHandler);
    $('button[name=generate]').click(generateClickHandler);
	$('button[name=printTeacher]').click(print4TeacherClickHandler);
    $('button[name=printStudent]').click(print4StudentClickHandler);
    
    // Connect input data change handler
    $('ul.inputData input[name=word]').change(wordChangeHandler);
    
    $('ul.inputData input[name=word]').change(); // 
    
    $('button[name=generate]').click(); // 
  });
    cUI.prototype = {
    // Returns (possibly also creates) the crossword's DOM element
	
    getDOMElement: function() {
		console.log("Return DOM element");
      if (this.$crossword === null) {
        this.$crossword = this.crossword.createDOMElement();
        
      }
      
      return this.$crossword;
    },
    
    // Deletes the crossword's DOM element
    deleteDOMElement: function() {
		console.log(" DOM deleted");
      if (this.$crossword === null) {
        return;
      }
      
      this.$crossword.remove();
      
      this.$crossword = null;
      this.rotationState = null;
    }
  };
  
  // Returns left margin for centering an absolutely positioned child within its parent
  function getLeftMarginForCentering($parent, $child) {
    return ($parent.width() - $child.width()) / 2.0;
  }
  
  
  // Returns left margin of centered child shifted by its width (times a factor)
  function getShiftedLeftMargin($parent, $child, direction) {
    var FACTOR = 1.2;
    
    var margin;
	margin = ($parent.width() - $child.width()) / 2.0;
    
	if (direction === DIRECTION.RIGHT) {
  return margin + $child.width() * FACTOR;
	}
	if (direction === DIRECTION.LEFT) {
  return margin - $child.width() * FACTOR;
	}

    
    return margin;
  }
  
  
  // Handler for window resizing events
  function windowResizeHandler(/*event*/) {
    var $parent = $('#crosswordWrapper');
    var $child  = $('#crosswordWrapper table.crossword');
    
    $child.css({ marginLeft: getLeftMarginForCentering($parent, $child) + 'px' });
  }
  
 
  
  // Handler for "Add Word" button clicks
  function addWordClickHandler(/*event*/) {
    if ($('ul.inputWords li').length === 1) {
      $('button[name=remove]').removeAttr('disabled');
    }
    
    var $wordLi = $('ul.inputWords li:last-child').clone();
    $wordLi.find('button[name=remove]').click(removeWordClickHandler);
    
    var $input = $wordLi.find('input');
    $input.val('');
    $input.change(wordChangeHandler);
    
    var $clueLi = $('ul.inputClues li:last-child').clone();
    $clueLi.find('div.clueSwitch button.segment').click(clueSwitchClickHandler);
    
    var $textarea = $clueLi.find('textarea');
    $textarea.html('');
    
    var $switch = $clueLi.find('div.clueSwitch');
    
    $switch.find('button.segment.active').removeClass('active');
    $($switch.find('button.segment')[0]).addClass('active');
    
    var $label = $clueLi.find('label[for=' + $switch.attr('id') + ']');
    
    var updateId = function($element) {
      var id = $element.attr('id');
      
      var numberMatch = id.match(/\d+/);
      
      var prefix = id.slice(0, numberMatch.index);
      var number = parseInt(id.slice(numberMatch.index));
      
      $element.attr('id', prefix + (number + 1));
    };
    
    updateId($input);
    updateId($textarea);
    updateId($switch);
    
    $label.attr('for', $switch.attr('id'));
    
    $('ul.inputWords').append($wordLi);
    $('ul.inputClues').append($clueLi);
  }
  
    function clueSwitchClickHandler(/*event*/) {
    if ($(this).hasClass('active')) {
      return;
    }
    
    $(this).parent().find('button.segment').toggleClass('active');
  }

  function removeWordClickHandler(event) {
	  console.log("Removed word");
    var $parent = $(event.currentTarget).parent();
    
    while (! $parent.parent().hasClass('inputWords')) {
      $parent = $parent.parent();
    }
    
    var index = $('ul.inputWords li').index($parent);
    
    $parent.remove();
    
    if ($('ul.inputWords li').length === 1) {
      $('button[name=remove]').attr('disabled', 'disabled');
    }
    
    $('ul.inputClues li:nth-child(' + (index + 1) + ')').remove();
  }
  
  
  // Handler for "Clear" button clicks
  function clearClickHandler(/*event*/) {
    clear();
    console.log("Clear worked");
    // Clear input data
    $('ul.inputData input[name=word]').val('');
    $('ul.inputData textarea[name=clue]').empty();
  }
  
  
  // Clears all input data and any generated crosswords
  function clear() {
	  try{
    $('.infoMessage').hide();
    
    $('#crosswordWrapper').empty();
    
    crosswords4UI = [];
    currentCrosswordIndex = -1;
    generatedAllAlternatives = false;
	console.log("Clear worked");
	  }
	  catch(err) {
  console.log("Error");
}
  }
  
  
  // Disables the button with the specified name
  function disableButton(name) {
    $('button[name=' + name + ']').attr('disabled', 'disabled');
  }
  
  
  // Enables the button with the specified name
  function enableButton(name) {
    $('button[name=' + name + ']').removeAttr('disabled');
  }
  
  
  // Generates and stores the next crossword
  function generateNextCrossword() {
    var crossword = $.crosswordGenerator('generate-next');
    
    if (crossword === null) {
      generatedAllAlternatives = true;
    } else {
      crosswords4UI.push(new cUI(crossword));
    }
    
    return !generatedAllAlternatives;
  }
  
  
  // Handler for "Generate" clicks
  function generateClickHandler(/*event*/) {
    clear(); // Clear any previous crosswords
    
    // Read input words
    var words = [];
    $('ul.inputData.inputWords li').each(function(index, element) {
      var word = $.trim($(element).find('input[name=word]').val());
      
      if (word.length) {
		
        words.push(word);
      }
    });
    
    // Check for empty input data
    if (! words.length) {
      console.log('no input data');
      return;
    }
    
    $.crosswordGenerator('init', { words: words, debug: false });
    
    if (generateNextCrossword()) { // Generate first alternative
      // Select and display first alternative
      currentCrosswordIndex = 0;
      slideIn(crosswords4UI[currentCrosswordIndex], DIRECTION.NONE);
      
      
      if (generateNextCrossword()) { // Generate second alternative
        enableButton('next');
      }
    } else {
      $('#errorMessage').slideDown();
    }
  }
  
  

  
  
  // Slides a crossword in from the specified direction
  function slideIn(cUI, from) {
    var $parent    = $('#crosswordWrapper');
    var $crossword = cUI.getDOMElement();
    
    var needsInitialMargin = true;
    
    if ($crossword.is(':animated')) { // Cancel previous slide animation
      $parent.stop();
      $crossword.stop();
      needsInitialMargin = false;
    } else { // Hide and append new crossword
      $('#crosswordWrapper').append($crossword.css({ opacity: 0 }));
    }
    
    setTimeout(function() { // Work around rendering race condition
      var marginStop  = getLeftMarginForCentering($parent, $crossword);
      
      if (needsInitialMargin) { // Initially shift crossword
        var marginStart = getShiftedLeftMargin($parent, $crossword, from);
        $crossword.css({ marginLeft: marginStart + 'px' });
      }
      
      animateSlide($crossword, marginStop, 1);
      
      // Resize container
      if (from !== DIRECTION.NONE) {
        $parent.animate({ height: $crossword.height() + 'px' }, SLIDE_DURATION);
      } else {
        $parent.css({ height: $crossword.height() + 'px' });
      }
    }, 0);
  }
  
  
  // Slides a crossword out to the specified direction
  function slideOut(cUI, to) {
    var $parent    = $('#crosswordWrapper');
    var $crossword = cUI.getDOMElement();
    var marginStop = getShiftedLeftMargin($parent, $crossword, to);
    
    $crossword.stop(); // Cancel any previous slide animation
    
    animateSlide($crossword, marginStop, 0, function() {
      cUI.deleteDOMElement();
    });
  }
  
  
  // Animates the slide of a crossword
  function animateSlide($crossword, marginLeft, opacity, callback) {
    $crossword.animate({ // Hide old crossword
      opacity:    opacity,
      marginLeft: marginLeft + 'px'
    }, SLIDE_DURATION, callback);
  }
  
  
    function print4StudentClickHandler() {
    print(true);
  }
    function print4TeacherClickHandler() {
    print(false);
  }
    function print(forStudent) {
    if (currentCrosswordIndex < 0 || currentCrosswordIndex > crosswords4UI.length - 1) {
      return;
    }
    
    var crossword4UI = crosswords4UI[currentCrosswordIndex];
    var $crossword   = crossword4UI.getDOMElement();
    var $letters     = null;
    
    var marginLeft = $crossword.css('margin-left');
    
    //$crossword.css({ marginLeft: 0 }); // Clear margin
    
    if (forStudent) { // Hide crossword letters
      $letters = $crossword.find('tr td.letter div');
      $letters.hide();
    }
    
    renderClues(forStudent);
    
    window.print();
    
    if (forStudent) { // Show crossword letters
      $letters.show();
    }
    
    //$crossword.css({ marginLeft: marginLeft }); // Restore margin
  }
  // Renders clues for a crossword
  function renderClues(forStudent) {
    var $ul = $('<ul>').addClass('clues');
    
    $('ul.inputClues li').each(function(index, element) {
      var clue = $(element).find('textarea[name=clue]').val();
      var $li  = $('<li>').html(clue);
      
      if ($(element).find('button.segment.clue').hasClass('active')) {
        if (forStudent) {
          $li.html('______________________________');
        } 
		else {
          $li.html(clue);
        }
      }
      
      $ul.append($li);
    });
    
    $('section.clues').html($ul);
  }
  
  
  
  // Handler for word change events
  function wordChangeHandler(/*event*/) {
    $('#outdatedMessage').show();
    
    var $parent = $(this);
    
    while ($parent && ! $parent.is('li')) {
      $parent = $parent.parent();
    }
    
    var index = $('ul.inputWords li').index($parent);
    
    $('ul.inputClues li:nth-child(' + (index + 1) + ') .wordCopy span').html($(this).val());
  }
  
})(jQuery);
