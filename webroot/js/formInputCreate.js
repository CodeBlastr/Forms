$(document).ready(function() {

  var inputs = [], usedIds = [], newId;

  $("#formInputs").sortable();
  $(".usableInput").draggable({
	containment: "#formBuilder",
	cursor: "move",
	cursorAt: {left: 5},
	grid: [10, 10],
	zIndex: 100,
	snap: true,
	//snapMode : "outer",
	helper: "clone",
	connectToSortable: "#formInputs",
	start: function(event, ui) {
	  var currentInputTypeId = $(ui.helper.context).attr("id");
	  if ($.inArray(currentInputTypeId, inputs) === -1) {
		inputs.push(currentInputTypeId);
		inputs[currentInputTypeId] = 0
	  } else {
		inputs[currentInputTypeId] = inputs[currentInputTypeId] + 1;
	  }
	  newId = currentInputTypeId + "_" + inputs[currentInputTypeId];
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
		// a new item has dropped
		usedIds.push(newId)
		ui.draggable.attr("id", newId);
		// copy #formMaster to new div in #inputOptions
		$("<div />")
				.html($("#formMaster").html())
				.attr("id", "config_" + newId)
				.attr("class", "configPanel")
				.appendTo("#inputOptions form");
		$("#config_" + newId + " input, #config_" + newId + " select, #config_" + newId + " textarea").each(function(index, element) {
		  var indexedName = $(this).attr("name");
		  if (indexedName !== undefined) {
			var currentIndex = usedIds.length - 1;
			indexedName = indexedName.replace("FormInput]", "FormInput][" + currentIndex + "]");
			$(this).attr("name", indexedName);
		  }
		});
		var configToShow = newId.substr(0, newId.indexOf('_'));
		if ($.inArray(configToShow, ["checkbox", "radio"]) !== -1) {
		  configToShow = "multiple";
		}
		$("#config_" + newId + " ." + configToShow + "Config").removeClass("hiddenConfig");
		ui.draggable.click();
	  }
	}
  });
  $("#formInputs").on("click", ".usableInput", function(event) {
//			var configPanel;
	$("#formInputs .usableInput").removeClass("configuring");
	$(this).addClass("configuring");
	$("#inputOptions .configPanel").hide();
	$("#inputOptions #config_" + $(this).attr("id")).show();
//			var type = $(this).attr("id").split("_")[0];
//
//			var typeId = $(this).attr("id").split("_")[1];
//			if ( $.inArray(type, ["checkbox", "radio"]) !== -1 ) {
//				type = "multiple";
//			}
//			// show new config panel of type: type
//			if ( $("#config_"+type).is("*") ) {
//				configPanel = $("#config_"+type).html();
//			} else {
//				configPanel = "";
//			}
//			$("#inputOptions").html( configPanel );
  });
});