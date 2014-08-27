/**
 * To generate the necessary forms to put in the pop up dialog
 * when DnD an event onto the calendar
 */
var formFactory = {
		
	service:'/createCalendarEvent',
	
	dialogClass:'DndDlg',
	formClass:'DndForm',
	
	onSuccess:function(response)
	{
		
	},
	
	onError:function()
	{
		
	},
	
	submit:function(dialog,form,nonFormValues,eventObj,tooltipFields)
	{
		console.log("SUBMIT",dialog,form,eventObj,tooltipFields);
		if (!eventObj) return;
		var self = this;
		if (!dialog) return;
		//if (!form) return;
		console.log("SUBMIT",tooltipFields);
		var formValues = form?form.serializeArray():null;
		var values = this.createEventValues(formValues,nonFormValues,tooltipFields);
		dialog.dialog('close');
		if (form) form.remove();
		dialog.remove();
		eventObj.description = values;
		//console.log('EVENT OBJ',eventObj);
		var myData = {
			start:new Date(eventObj.start).toISOString(),
			end:eventObj.end,
			all_day:eventObj.allDay?1:0,
			definitions:values
		};
		//close dialog
		//dialog.close();
		$.ajax({
			url:self.service,
			type:'POST',
			dataType:'json',
			data:myData,
			success:function(response) {
				if ('success' in response && response['success']==1) {
					if (response['calendar_event_id'])
					{
						eventObj['calendar_event_id'] = response['calendar_event_id'];
						eventObj['className'] +=' '+response['calendar_event_id'];
					}
					$('#calendar').fullCalendar('renderEvent', eventObj, true);
				} else {
					var msg = 'Unable to create event';
					if ('msg' in response) msg = response['msg'];
					updateMsg($('.myErrorMsg_msg'),msg);
					$('#myErrorMsg').dialog('open');
					setTimeout(function() {$('#myErrorMsg').dialog('close');},2000);
				}
			},
			error:function(err) {
				var msg = 'Unable to create event';
				updateMsg($('.myErrorMsg_msg'),msg);
				$('#myErrorMsg').dialog('open');
				setTimeout(function() {$('#myErrorMsg').dialog('close');},2000);
			}
		});
	},
	
	/*
	 * Creates the tooltip for the created event, as well as the definitions array
	 * to pass back to the create calendar service
	 */
	createEventValues:function(values,nonFormValues,tooltipFields) 
	{
		var formValues = [];
		for (var t = 0;t<tooltipFields.length;t++)
		{
			var name = tooltipFields[t].name,
				label = tooltipFields[t].label,
				units = tooltipFields[t].unit||null;
			for (var v =0;v<values.length;v++) 
			{
				if (values[v].name == name) {
					var tip = label+' '+values[v].value
					if (units) {
						for (u = 0;u<values.length;u++)
						{
							if (values[u].name == name+'_unit') {
								for (var unit=0;unit<units.length;unit++)
								{
									if (units[unit]['event_unit_id'] == values[u].value)
									{
										tip+=' '+units[unit].name;
									}
								}
							}
						}
					}
					formValues.push({value:tip,definition_id:values[v].name});
				}
			}
		}
		for (var i = 0;i<nonFormValues.length;i++)
		{
			formValues.push({value:'Yes',type:'NonForm',definition_id:nonFormValues[i].name});
		}
		return formValues;
	},
	
	testCreate:function()
	{
	
		var fields = [];
		fields.push({
			label:'test',
			name:'test',
			rule:{required:true,pattern:new RegExp(/\d+\:\d\d/)},
			message:{pattern:'Enter a time format'}
		});
		
		fields.push({
			label:'test2',
			name:'test2',
			unit:['km','miles']
		});
		
		fields.push({
			label:'test3',
			name:'test3',
			type:'radio',
			values:['up','yers']
		});
		
		this.create(fields);
	},
	
	
	create:function(/** {FieldEvents} **/fields,eventObj,dndSource)
	{
		console.log('CREATE',fields);
		if (dndSource && dndSource.remove) dndSource.remove();
		var self = this;
		if (!fields) return null;
		
		var form,
			dialog = this._createDlg(),
			table = $('.'+this.formClass +' table'),
			rules = {},
			messages = {},
			tooltipFields = [],
			nonFormValues = [],
			formStr = '';
		
		//for (var i = 0;i<fields.length;i++)
		for (var i in fields)
		{
			if (fields[i].type == '_no_input_') {
				nonFormValues.push(fields[i]);
				continue;
			};
			var myField = Fields.createField(fields[i]);
			tooltipFields.push($.extend({},myField));
			formStr+=this.createFieldRow(myField);
			if (myField.rule) rules[myField.name] = myField.rule;
			if (myField.message) messages[myField.name] = myField.message;
		}
		
		formStr+=this.createBtnRow();
		
		table.append(formStr);
		

		dialog.dialog({
			autoOpen:false,
			closeOnEscape:false,
			modal:true,
			draggable:true,
			width:433,
			height:220
		});
		/*
		$('.'+this.formClass+' input[type=button]').on('click',function() {
			dialog.dialog('close');
		});
		*/
		/*
		 * If we have input; we have rules, and the form needs to be filled out and
		 * validated before submission
		 * 
		 * Otherwise we can submit directly
		 */
		if (Object.keys(rules).length>0)
		{
			form = $('.'+this.formClass);
			form.validate({
				rules:rules,
				messages:messages,
				submitHandler:function(evt) {
					self.submit(dialog,form,nonFormValues,eventObj,tooltipFields);
				}
			});
			
			dialog.dialog('open');
			
			return dialog;
		} else
		{
			this.submit(dialog,form,nonFormValues,eventObj,tooltipFields);
			return dialog;
		}
	},
	
	createFieldRow:function(field)
	{
		var row = '<tr height="40">';
		row+='<td>'+this.createLabel(field.label,field.name)+'</td>';
		
		if (field['type'] && this['create_'+field['type']])
		{
			row+='<td>'+this['create_'+field['type']](field)+'</td>';
		} else
		{
			row+='<td>'+this.createInput(field)+'</td>';
		}
		if (field['unit'] && Object.prototype.toString.call( field['unit'] ) === '[object Array]' ) {
			
			row+='<td>'+this.createDropDown(field['name']+'_unit',field['unit'])+'</td>';
		}
		row+='</tr>';
		return row;
	},
	
	createBtnRow:function()
	{
		var str = '<tr height="40"><td>'+'<input type="submit" value="Submit" /></td>';
		//str+='<td><input type="button" value="Cancel" class="cancelDnD" /></td></tr>';
		return str;
	},
	
	createLabel:function(value,labelFor)
	{
		if (!value) return '';
		var label = '<label ';
		if (labelFor) label+='"for"="'+labelFor+'" >'
		else label+='>';
		label+=value;
		label+='</label>';
		return label
	},
	
	createInput:function(field)
	{
		if (!field) return '';
		var input = '<input name="'+field['name']+'" placeholder="" />';
		return input;
	},
	
	create_radio:function(field)
	{
		var str = '';
		for (var i = 0;i<field.values.length;i++)
		{
			str+='<input class="radio_field" type="radio" name="'+field['name']+'" value="'+field.values[i]+'" /><span class="radio_text">'+field.values[i]+'</span>';
		}
		
		return str;
	},
	
	createDropDown:function(name,values)
	{
		if (!values || !values.length || values.length<=0) return '';
		var str = '<select class="event_field" name="'+name+'">';
		
		for (var i = 0;i<values.length;i++)
		{
			str+='<option value="'+values[i].event_unit_id+'">'+values[i].name+'</option>';
		}
		str+='</select>';
		return str;
	},
	
	_createDlg:function()
	{
		var div = $(this.formTemplate,{'class':this.dialogClass});
		$('body').append(div);
		return div;
	},
	
	onCloseDlg:function()
	{
		if ( $('.DndDlg') ) {
			$('.DndDlg').remove();
		}
	},
	
	// Title attribute for items of the calendar views
	_renderTitleTooltip:function (event)
	{
		//console.log('_render tooltip',event);
		var html = " title='";
		if (event && (event.description) )
		{
			for (var i =0;i< event.description.length;i++)
			{
				if (!event.description[i]|| event.description[i].type !='_no_input_')
					html += ""+event.description[i].value+"<br>"; 
			}
			html+="' ";
		}
		return html;
	},

	formTemplate:[
		'<div title="Describe Your Task/Event" class="DndDlg">',
			'<form class="DndForm">',
			'<fieldset>',
				'<table>',
				'</table>',
			'</fieldset>',
			'</form>',
		'</div>'        
	              ].join(''),
	              
	              
};