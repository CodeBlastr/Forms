$(document).ready(function() {

  var inputs = [];
  var newId;
  var usedIds = [];

  $("#formInputs").sortable();
  $(".usableInput").draggable({
	containment: "#formBuilder",
	cursor: 'move',
	cursorAt: {left: 5},
	grid: [10, 10],
	zIndex: 100,
	snap: true,
	//snapMode : 'outer',
	helper: 'clone',
	connectToSortable: '#formInputs',
	start: function(event, ui) {
	  var currentInputTypeId = $(ui.helper.context).attr('id');
	  if (inputs[currentInputTypeId] === undefined) {
		inputs[currentInputTypeId] = 0
	  } else {
		inputs[currentInputTypeId] = inputs[currentInputTypeId] + 1;
	  }
	  newId = currentInputTypeId + '_' + inputs[currentInputTypeId];
	},
	revert: function(event, ui) {
	  $(this).data("uiDraggable").originalPosition = {top: 0, left: 0};
	  return !event;
	},
	stop: function(event, ui) {
	} // might be overridden by the $.sortable.stop()
  });
  $("#formInputs").droppable({
	accept: ".usableInput",
	hoverClass: "drop-hover",
	activeClass: "ui-state-highlight",
	drop: function(event, ui) {
	  if ($.inArray(newId, usedIds) === -1) {
		console.log('changing an id to: ' + newId);
		usedIds.push(newId)
		ui.draggable.attr('id', newId);
	  }
	}
  });
  $("#formInputs").on("click", '.usableInput', function(event) {
	var configPanel;
	$("#formInputs .usableInput").removeClass('configuring');
	$(this).addClass('configuring');
	var type = $(this).attr('id').split("_")[0];
	var typeId = $(this).attr('id').split("_")[1];
	if ($.inArray(type, ['checkbox', 'radio']) !== -1) {
	  type = 'multiple';
	}
	// show new config panel of type: type
	if ($("#config_" + type).is('*')) {
	  configPanel = $("#config_" + type).html();
	} else {
	  configPanel = '';
	}
	$("#inputOptions").html(configPanel);
  });
});
