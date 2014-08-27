var journal_autosave = {
	prev:null,
	compare:function(formID) {
		var current = $(formID).serializeArray();
		var diff = {};
		if (!current) return diff;
		if (!this.prev) return {changes:1};
		for (var i = 0;i<current.length;i++)
		{
			for (var j = 0;j<prev.length;j++)
			{
				if (current[i]['name'] == this.prev[j]['name'])
				{
					if (current[i]['value'] != this.prev[j]['value'])
					{
						diff['compare_'+current[i]['name']] = 1;
						if (!diff['changes']) diff['changes']=1;
					}
					break;
				}
			}
		}
		return diff;

	},
	
	setForm:function(formID) {
		if (!formID) return;
		this.prev = $(formID).serializeArray();
	}
};