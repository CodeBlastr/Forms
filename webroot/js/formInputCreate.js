$(document).ready(function() {
  
  /**
   * The ID of an element that is being dragged
   * @type String
   */
  var newId;
  
  /**
   * An array of the newId's that are being used
   * @type Array
   */
  var usedIds = [];
  
   /**
   * An array for tracking counts of each type of input, base zero
   * i.e.[checkbox: 0, textarea: 1]
   * @type Array
   */
  var inputs = [];
  

  /**
   * updates the values of each FormInput.x.Order field
   * @returns {void}
   */
  function updateFormInputOrders() {
	
	var newOrder = 0
	$("#formInputs div.usableInput").each(function() {
	  if ($(this).attr("id") !== undefined) {
		var formInputIndex = $(this).attr("data-formInput-x");
		$("#FormInput" + formInputIndex + "Order").val( newOrder );
		$("#FormInput" + formInputIndex + "ModelOverride").val( "FormAnswer."+newOrder );
		newOrder++;
	  }
	});
  }

  /**
   * 
   * @param {string} currentInputType
   * @returns {void}
   */
  function updateInputsArray(currentInputType) {
	
	if ($.inArray(currentInputType, inputs) === -1) {
	  inputs.push(currentInputType);
	  inputs[currentInputType] = 0;
	} else {
	  inputs[currentInputType] = inputs[currentInputType] + 1;
	}
  }

  /**
   * a new item has dropped
   * @param {object} ui
   * @returns {void}
   */
  function afterDroppableDrop(ui) {
	
	if ($.inArray(newId, usedIds) === -1) {
	  usedIds.push(newId);
	  ui.draggable.attr("id", newId).attr("data-formInput-x", usedIds.length - 1);
	  createInputCopies();
	  displayConfigPanel();
	}
  }

  /**
   * - copy #formMaster to new div in #inputOptions
   * - update the 'id' and 'name' attributes of the copy's inputs
   * 
   * @returns {void}
   */
  function createInputCopies() {
	
	// create a copy of the inputs
	$("<div />")
		.html($("#formMaster").html())
		.attr("id", "config_" + newId)
		.attr("class", "configPanel")
		.appendTo("#inputOptions form");
	// configure the copied inputs
	var currentIndex = usedIds.length - 1;
	$("#config_" + newId + " input, #config_" + newId + " select, #config_" + newId + " textarea").each(function(index, element) {
	  var indexedName = $(this).attr("name");
	  var indexedId = $(this).attr("id");
	  if (indexedName !== undefined) {
		indexedName = indexedName.replace("FormInput]", "FormInput][" + currentIndex + "]");
		indexedId = indexedId.replace("FormInput", "FormInput" + currentIndex);
		$(this).attr("name", indexedName).attr("id", indexedId);
	  }
	});
	$("#config_" + newId + " label").each(function(index, element) {
	  var indexedFor = $(this).attr("for");
	  indexedFor = indexedFor.replace("FormInput", "FormInput" + currentIndex);
	  $(this).attr('for', indexedFor);
	});
	$("#FormInput"+currentIndex+"InputType").val( newId.substr(0, newId.indexOf('_')) );
	$("#FormInput"+currentIndex+"ModelOverride").val( 'FormAnswer.'+currentIndex );
  }

  /**
   * display correct version of the options in the config panel
   * 
   * @returns {void}
   */
  function displayConfigPanel() {
	
	var configToShow = newId.substr(0, newId.indexOf('_'));
	if ($.inArray(configToShow, ["checkbox", "radio"]) !== -1) {
	  configToShow = "multiple";
	}
	$("#config_" + newId + " ." + configToShow + "Config").removeClass("hiddenConfig");
	$("#" + newId).click();
  }

/*
 * Let's Go!
 */

  $(".usableInput").draggable({
	connectToSortable: "#formInputs",
	containment: "#formBuilder",
	cursor: "move",
	cursorAt: {left: 5},
	grid: [10, 10],
	helper: "clone",
	snap: true,
	//snapMode :         "outer",
	zIndex: 100,
	start: function(event, ui) {
	  var currentInputType = $(ui.helper.context).attr("id");
	  updateInputsArray(currentInputType);
	  newId = currentInputType + "_" + inputs[currentInputType];
	},
	revert: function(event, ui) {
	  $(this).data("uiDraggable").originalPosition = {top: 0, left: 0};
	  return !event;
	}
  });

  $("#formInputs")
		  .droppable({
			accept: ".usableInput",
			activeClass: "ui-state-highlight",
			hoverClass: "drop-hover",
			drop: function(event, ui) {
			  afterDroppableDrop(ui);
			}
		  })
		  .sortable({
			update: function(event, ui) {
			  updateFormInputOrders();
			}
		  })
		  // highlight and show config panel
		  .on("click", ".usableInput", function(event) {
			$("#formInputs .usableInput").removeClass("configuring");
			$(this).addClass("configuring");
			$("#inputOptions .configPanel").hide();
			$("#inputOptions #config_" + $(this).attr("id")).show();
		  });

  $("#inputOptions")
		  // sync display of label with what they type in the label field
		  .on("input", ".FormInputName", function(event) {
			var $elementConfigDiv = $(this).parent().parent();
			var divId = $elementConfigDiv.attr("id");
			var sortableInputId = divId.substr(divId.indexOf('_')+1, divId.length);
			$("#"+sortableInputId + " label").html( $(this).val() );
		  })
		  // sync FormInputOptionValues with FormInputOptionNames
		  .on("input", ".FormInputOptionNames", function(event) {
			$(".FormInputOptionValues", $(this).parent().parent()).val( $(this).val() );
		  });

});
