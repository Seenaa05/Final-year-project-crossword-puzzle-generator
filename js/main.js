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
    

    
   

    $('button[name=clear]').click(clearClickHandler);
    $('button[name=generate]').click(generateClickHandler);
    $('ul.inputData input[name=word]').change(wordChangeHandler);


  });
    cUI.prototype = {

	
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
  
  
  
  
  

  
})(jQuery);
