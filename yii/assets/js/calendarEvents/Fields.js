/**
 * A field is a tuple of name, input type (email, url, number, etc.), 
 * and optionally a regular expression to validate the field against
 */
var Fields = {
	
	BasicRules:{
		number:{
			required:true,
			number:true
		}
	},
	
	getBasicRule:function(type) {
		if (!type) return null;
		return this.BasicRules[type];
	},
	
	createAmtField:function(name,unit)
	{
		if (!name) return null;
		return {
			name:name,
			unit:unit||null,
			rule:this.BasicRules.number
		};
	},
	
	createYesNoField:function(name)
	{
		if (!name) return;
		return {
			name:name,
			unit:['yes','no'],
			rule:{
				required:true
			}
		}
	},
	
	createField:function(field)
	{
		if (!field || !field['type']) return null;
		if (!this[field['type']]) return $.extend(this.generic,field);
		
		if (typeof(this[field['type']]) === 'function')
		{
			return this[field['type']](field);
		} else
		{
			return $.extend(this[field['type']],field);
		}
		//return $.extend({label:label||'Value'},this[type],name?{name:name}:{});
	},
	
	note:{
		rule:{required:false}
	},
	
	generic:{
		rule:{required:true}
	},
	
	quantity:{
		name:'quantity',
		unit:null,
		rule:{
			required:true,
			number:true
		}
	},
	
	no_input:{
		type:'hidden',
		name:'no_input',
		unit:null
	},
	
	boolean:{
		values:['yes','no'],
		type:'radio',
		unit:null,
		rule:{
			required:true
		}
	},
	
	radio:{
		type:'radio',
		unit:null,
		rule:{required:true}
	},
	
	'date':{
		type:'date',
		unit:null,
		rule:{
			required:true
		}
	},
	
	time:{
		unit:['am','pm'],
		rule:{
			required:true,
			pattern:new RegExp(/\d{1,2}\:\d\d/)
		},
		message:{
			pattern:'Enter a digital time format, e.g., 11:05'
		}
	},
		
	minutes:{
		name:'timeAmt',
		unit:['min','hrs'],
		rule:{
			required:true,
			number:true
		}
	},
	
	weight:{
		name:'weight',
		unit:['lbs','kg'],
		rule:{
			required:true,
			number:true
		}
	},
	
	height:{
		name:'height',
		unit:['ft','cm'],
		rule:{
			required:true,
			number:true
		}
	},
	
	distance:{
		name:'distance',
		unit:['miles','km'],
		rule:{
			required:true,
			number:true
		}
	},
	
	Description:{
		name:'who/what/where/when/title/notes/etc',
		unit:null,
		rule:{
			required:true
		}
	},
	
	scale_1_to_10:{
		name:'range',
		unit:null,
		rule:{
			required:true,
			range:[1,10]
		},
		message:{
			range:'Enter a value between 1 and 10'
		}
	},
	
	/*
	who:function(field) {return $.extend(this['interrogative',field);},
	what:function(field) {return $.extend(this['interrogative',field);},
	where:function(field) {return $.extend(this['interrogative',field);},
	'with':function(field) {return $.extend(this['interrogative',field);},
	*/
	interrogative:{
		unit:null,
		rule:{required:true}
	}
}
