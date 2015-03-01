var utils = {
		
	/**
	 * Wrapper around dojo's fade in and fade out functionality to fade out of view one node for another
	 * @param {String} node1 The ID of the HTMLElement to fade out
	 * @param {String} node2 The ID of the HTMLElement to fade in
	 * @param {function()} func1 callback to invoke when node1 is completely faded out
	 * @param {function()}func2 callback to invoke when node2 is completely faded in
	 */
	swap:function(node1, node2, func1, func2) {
	    if (!dojo.byId(node1) || !dojo.byId(node2)) return;
	    dojo.style(node1, "opacity", "1");
	    dojo.fadeOut ({
            node: node1,
            duration: 300,
            onEnd: function() {
                dojo.style(node2, "opacity", "0");
                dojo.style(node2, "display", "");
                dojo.style(node1, "display", "none");                
                
                if (func1) func1();
                dojo.fadeIn ({
                    node: node2,
                    duration: 300,
                    onEnd: function() {   	                    	
                    	if (func2) func2();
                    }
                }).play();
            }
	    }).play();
	}
};

var UploadAndInsertDialog = {
		
	preInit : function() {
		tinyMCEPopup.requireLangPack();
	},
	
	insert:function(form,uploadedData)
	{
		var ed = tinyMCEPopup.editor;
		console.log('form',form.validate());
		console.log('formdata',form.attr('value'));
		console.log('uploaded',uploadedData);
		console.log('editor',ed);
		console.log('this',this);
		
		//Validate
		if (!form.validate())
		{
			alert('Please ensure you have specified a title and an alternate image description');
			return;
		}
		
		//Create img attributes
		var formData = form.attr('value');
		// Image attributes
		var att = {
			src:tinyMCEPopup.editor.documentBaseURI.toAbsolute(uploadedData.src),
			height:uploadedData.height,
			width:uploadedData.width,
			alt:formData['imgDesc'],
			title:formData['imgTitle']
		};
		
		ed.execCommand('mceInsertContent', false, tinyMCEPopup.editor.dom.createHTML('img', att), {skip_undo : 1});
		ed.undoManager.add();

		tinyMCEPopup.editor.execCommand('mceRepaint');
		tinyMCEPopup.editor.focus();
		tinyMCEPopup.close();
		
	},
	
	showPreviewImage:function(u,st)
	{
		if (!u) {
			tinyMCEPopup.dom.setHTML('previewImg', '');
			return;
		}
		u = tinyMCEPopup.editor.documentBaseURI.toAbsolute(u);

		tinyMCEPopup.dom.setHTML('prev', '<img id="previewImg" src="' + u + '" border="0"  />');
		
	}
};

UploadAndInsertDialog.preInit();