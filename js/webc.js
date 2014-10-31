WebC = {
	textareaEditor: {},
	textareaInput: {},
	console: {},

	init: function() {
		WebC.console = $('#output');
		WebC.createCodemirrorCode();
		WebC.createCodemirrorInput();
		WebC.textareaEditor.focus();
	},

	createCodemirrorCode: function() {
		WebC.textareaEditor = CodeMirror.fromTextArea(document.getElementById('userCode'), {
			lineNumbers: true,
			matchBrackets: true,
			mode: "text/x-csrc",
			theme: 'default webc-code',
			indentUnit: 4,
			indentWithTabs: true,
			extraKeys: WebC.codemirrorExtraKeys
		});
	},

	createCodemirrorInput: function() {
		WebC.textareaInput = CodeMirror.fromTextArea(document.getElementById('userInput'), {
			mode: "text/plain",
			theme: 'default webc-input',
			smartIndent: false,
			indentUnit: 4,
			indentWithTabs: true,
			extraKeys: WebC.codemirrorExtraKeys
		});
	},

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
			data: {
				code: WebC.textareaEditor.getValue(),
				input: WebC.textareaInput.getValue(),
			},
			dataType: 'text'
		}).done(WebC.ajaxComplete);
	},
	
	codemirrorExtraKeys: { 
		F7: function(cm) { WebC.textareaEditor.focus(); },
		F9: function(cm) { WebC.textareaInput.focus(); },
		F10: function(cm) { WebC.run(); }
	}
};
