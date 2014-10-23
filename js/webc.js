WebC = {
	editor: {},
	console: {},

	ajaxComplete: function(msg) {
		if (msg == 'WebC.exit') {
			window.location.href = 'index.php';
		} else {
			WebC.console.val(msg);
		}
	},
	
	run: function() {
		WebC.console.val('Compilando....');
		$.ajax({
			type: 'POST',
			url: 'run.php',
			data: {code: WebC.editor.getValue()},
			dataType: 'text'
		}).done(WebC.ajaxComplete);
	},
	
	codemirrorExtraKeys: { F10: function(cm) { WebC.run(); } }
};
