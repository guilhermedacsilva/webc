WebC = {
	editor: {},

	ajaxComplete: function(msg) {
		alert(msg);
	},
	
	run: function() {
		$.ajax({
			type: 'POST',
			url: 'run.php',
			data: {code: WebC.editor.getValue()},
			dataType: 'text'
		}).done(WebC.ajaxComplete);
	},
	
	codemirrorExtraKeys: { F10: function(cm) { WebC.run(); } }
};