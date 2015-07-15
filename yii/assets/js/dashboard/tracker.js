var Tracker = {
	
		trackerPlot:null,
		placeholder:'#trackerChart',
		plotMax:50,
		plotColors:['#FF0000','#0066FF','#009933','#FF3399','#660033'],
		maxPlots:5,
		plotData:[],
		plotSpecifications:{},
		
		/*
		 * Used to map responses from dashboard service to points on the slider
		 */
		sliderValues:null,

		doHover:function(event,pos,item) {
			var x = item.datapoint[0].toFixed(2),
			y = item.datapoint[1].toFixed(2);

			var pointOffset = this.trackerPlot.pointOffset({x:pos.x,y:pos.y});
			$("#Tooltip").html("Y = " + y)
				.css({top: pointOffset.top, left: pointOffset.left+40})
				.fadeIn(200);
			},
		
		showTooltips:function(ui) {
			if (!this.trackerPlot) return;
			if (!ui || !('value' in ui) ) return;
			
			var sliderValue = this.getSliderValue(ui.value),
				offset = ui['trackerOffset']?ui['trackerOffset']:0,
				self = this;
			
			this.trackerPlot.unhighlight();
			$.each(this.trackerPlot.getData(),function(index,item) {
				var point = {x:item.data[sliderValue][0],y:item.data[sliderValue][1]};
				var pointOffset = self.trackerPlot.pointOffset(point);
				if (!point || !('x' in point)) return;
				
				var html = '';
				
				if (item.capped) {
					if (item.originalData[point['x']]) {
						
						html = item.tipLabel;
					} else {
						$("#Tooltip"+index).hide();
						return;
					}
				} else {
					if (!item.originalData) {
						$('#Tooltip'+index).hide();
						return;
					}
					if (!item.originalData[point['x']])
					{
						html = item.tipLabel+' = None';
					} else {
						html = item.tipLabel+' = '+item.originalData[point['x']];//point['y'].toFixed(2);
					}
				}
				$("#Tooltip"+index).html(html/*'Y = '+point['y'].toFixed(2)*/)
				.css({top: pointOffset.top+20, left: pointOffset.left+40+offset})
				.fadeIn(200);
				self.trackerPlot.highlight(index,sliderValue);
			});
		},
		
		drawOverlayLine:function(ui)
		{
			if (!this.trackerPlot || !this.trackerPlot.getCanvasContext) return;
			if (!ui || !('value' in ui) ) return;
			this.trackerPlot.draw();
			var sliderValue = this.getSliderValue(ui.value);
			var xOffset = this.trackerPlot.getPlotOffset().left;

			var ctx = this.trackerPlot.getCanvasContext();
			var axes = this.trackerPlot.getAxes();
			if (!axes || !axes.yaxis.box) return;
	        var yStart = axes.yaxis.box.top;
	        var yStop = yStart + axes.yaxis.box.height;
	        var xCor = axes.xaxis.p2c(sliderValue);// + axes.yaxis.box.top Why do I need this?
	        
	        ctx.font = '9pt Arial';
	        ctx.strokeStyle = 'blue';
	        ctx.fillStyle = 'blue';
	        ctx.lineWidth = 2;
	        ctx.textAlign = 'left';
	        ctx.textBaseline = 'middle';

	        ctx.fillText(' ',xCor,yStart);
	        
	        ctx.beginPath();
	        ctx.moveTo(xCor+xOffset,yStart);
	        ctx.lineTo(xCor+xOffset, yStop);
	        ctx.stroke();
		},
		
		/*
		 * Take plot data, shift the indices in plot data one spot to the left
		 */
		shiftDataLeft:function(plotData) {
			var entry;
			for (var i = 0;i<plotData.data.length;i++) {
				entry = plotData.data[i];
				entry[0]++;
			}
		},

		/*
		 * Take plot data, shift the indices in plot data one spot to the right
		 */		
		shiftDataRight:function(plotData) {
			var entry;
			for (var i = 0;i<plotData.data.length;i++) {
				entry = plotData.data[i];
				entry[0]--;
			}			
		},
		
		getPlotDataEntry:function(color) {
			for (var i =0;i<this.plotData.length;i++) {
				if (this.plotData[i].color == color) return i;
			}
			return -1;
		},
		
		getDataPoint:function(_trackerData,_averages,range,capped,uncapped,shiftDir) {
			var index = 0,
				plotIndex = -1;
			for (var i in capped) {
				var values = [],
				data=[],
				originalValues = [];
				plotIndex = this.getPlotDataEntry(capped[i]);
				if (plotIndex <0) continue;
				
				for (var j in _averages) {
					var _date = _averages[j]['date'];
					values.push(_trackerData['cappable_events'][i][_date]);
				}
				
				for (var v = 0;v<values.length;v++) 
				{
					if (values[v] == 0) {
						data.push([v,null]);
						originalValues.push(values[v]);
				    } else {
				    	data.push([v,values[v]+index*range+(range/2),index*range]);
				    	originalValues.push(values[v]);
				    }
				}
				/* Add on original data so we can normalize the new value */
				for (var i = 0;i<this.plotData[plotIndex].originalData.length;i++) values.push(this.plotData[plotIndex].originalData[i]);
				values = this.normalizeValues(values, eventUnits||null, index*range,range);
				values = [values.shift()];			
				if (shiftDir == 'left') {
					for (var v = 0;v<data.length;v++) this.plotData[plotIndex].data.unshift(data[v]);
					for (var v = 0;v<originalValues.length;v++) this.plotData[plotIndex].originalData.unshift(originalValues[v]);
				} else {
					for (var v = 0;v<data.length;v++) this.plotData[plotIndex].data.push(data[v]);
					for (var v = 0;v<originalValues.length;v++) this.plotData[plotIndex].originalData.push(originalValues[v]);
				}
				index++;
			}
			
			for (var i in uncapped) {
				var values = [],data=[],originalValues = [];
				plotIndex = this.getPlotDataEntry(uncapped[i]);
				
				if (plotIndex<0) continue;
				for (var j in _averages) {
					var _date = _averages[j]['date'];
					values.push(_trackerData['non_cappable_events'][i][_date]);
					originalValues.push(_trackerData['non_cappable_events'][i][_date]);
				}
				/* Add on original data so we can normalize the new value */
				for (var i = 0;i<this.plotData[0].originalData.length;i++) values.push(this.plotData[0].originalData[i]);
				values = this.normalizeValues(values, eventUnits||null, index*range,range);
				values = [values.shift()];
				
				if (shiftDir == 'left') {
					for (var v = 0;v<values.length;v++) 
					{
						data.push([v,values[v]]);
					}
					for (var v = 0;v<data.length;v++) this.plotData[plotIndex].data.unshift(data[v]);
					for (var v = 0;v<originalValues.length;v++) this.plotData[plotIndex].originalData.unshift(originalValues[v]);
				} else {
					for (var v = 0;v<values.length;v++) 
					{
						data.push([this.plotData[plotIndex].data.length,values[v]]);
					}
					for (var v = 0;v<data.length;v++) this.plotData[plotIndex].data.push(data[v]);
					for (var v = 0;v<originalValues.length;v++) this.plotData[plotIndex].originalData.push(originalValues[v]);
				}
				index++;
			}			
		},
		
		/*
		 * Chop off the last data point in the plot and add a new one to the beginning
		 * 
		 */
		updateLeft:function(selection,_trackerData,_averages) {
			if (!selection || !_trackerData||!_averages||!this.trackerPlot||!this.plotData||this.plotData.length<=0) return;
			
			for (var i =0;i<this.plotData.length;i++) {
				this.plotData[i].data.pop();
				this.plotData[i].originalData.pop();
				this.shiftDataLeft(this.plotData[i]);
			}
				
			var nPlots = this.getNumberOfPlots(selection),
				range = this.plotMax/nPlots,
				index = 0,
				capped = selection['cappable_events'],
				uncapped = selection['non_cappable_events'],
				plotIndex = -1;
			this.getDataPoint(_trackerData, _averages, range, capped,uncapped,'left');
			this.trackerPlot.setData(this.plotData);
			this.trackerPlot.draw();
		},
		
		/*
		 * Chop off the first data point in the plot and add a new one to the end
		 * 
		 */		
		updateRight:function(selection,_trackerData,_averages) {
			if (!selection || !_trackerData||!_averages||!this.trackerPlot||!this.plotData||this.plotData.length<=0) return;
			for (var i = 0;i<this.plotData.length;i++) {
				this.plotData[i].originalData.shift();
				this.plotData[i].data.shift();
				this.shiftDataRight(this.plotData[i]);				
			}
			var nPlots = this.getNumberOfPlots(selection),
				range = this.plotMax/nPlots,
				index = 0,
				capped = selection['cappable_events'],
				uncapped = selection['non_cappable_events'],
				plotIndex = -1;
			this.getDataPoint(_trackerData, _averages, range, capped,uncapped,'right');
			this.trackerPlot.setData(this.plotData);
			this.trackerPlot.draw();			
		},
		
		getNumberOfPlots:function(selection) {
			var nPlots = 0;
			for (var i in selection) {
				for (var j in selection[i])
				nPlots++;
			}
			return nPlots;
		},
		
		/**
		 * Get plot data based on specified selection
		 */
		getPlotData:function(selection) {
			if (!selection) return;
			// Globally defined
			if (!trackerData) return;
			if (!_avg) return;
			/*
			if (this.plotData.length>0) {
				this.plotData.slice(1);
			}
			*/
			this.plotData = [];
			var nPlots = 0,
			range,
			index = 0,
			capped = selection['cappable_events'],
			uncapped = selection['non_cappable_events'];
			for (var i in selection) {
				for (var j in selection[i])
				nPlots++;
			}
		
			range = this.plotMax/nPlots;
			
			for (var i in capped/*var i = 0;i<capped.length;i++*/) {
				var values = [],data=[],originalValues = [];
				for (var j in _avg) {
					var _date = _avg[j]['date'];
					values.push(trackerData['cappable_events'][i][_date]);
				}
				//values = this.normalizeValues(values, eventUnits||null, index*range);
				
				for (var v = 0;v<values.length;v++) 
				{
					if (values[v] == 0) {
						data.push([v,null]);
						originalValues.push(values[v]);
				    } else {
				    	data.push([v,values[v]+index*range+(range/2),index*range]);
				    	originalValues.push(values[v]/*+index*range+(range/2)*/);
				    }
				}
				
				this.plotData.push({
					data:data,
					color:capped[i],
					tipLabel:i,
					capped:true,
					originalData:originalValues,
					lines:{
						fill:1,
						fillColor:capped[i]
					}
						
				});
				index++;
			}
			
			for (var i in uncapped/*var i = 0;i<uncapped.length;i++*/) {
				var values = [],data=[],originalValues = [];
				for (var j in _avg) {
					var _date = _avg[j]['date'];
					values.push(trackerData['non_cappable_events'][i][_date]);
					originalValues.push(trackerData['non_cappable_events'][i][_date]);
				}
				
				values = this.normalizeValues(values, eventUnits||null, index*range,range);
				for (var v = 0;v<values.length;v++) 
				{
					data.push([v,values[v]]);
				}
				this.plotData.push({data:data,color:uncapped[i],tipLabel:i,originalData:originalValues});
				index++;
			}
			return this.plotData;
		},
		
		/*
		 * plots looks like:
		 * {
		 *   cappable_events:{},
		 *   non_cappable_events:{}
		 */
		_draw:function(selection)
		{
			if (this.trackerPlot) {
				this.trackerPlot.shutdown();
				this.trackerPlot.destroy();
			}
			/*
			var plotData = [];
			if (!selection) return;
			// Globally defined
			if (!trackerData) return;
			if (!_avg) return;
			
			var nPlots = 0,
				range,
				index = 0,
				capped = selection['cappable_events'],
				uncapped = selection['non_cappable_events'];
			for (var i in selection) {
				for (var j in selection[i])
				nPlots++;
			}
			
			range = this.plotMax/nPlots;
		
			for (var i in capped) {
				var values = [],data=[],originalValues = [];
				for (var j in _avg) {
					var _date = _avg[j]['date'];
					values.push(trackerData['cappable_events'][i][_date]);
				}
				//values = this.normalizeValues(values, eventUnits||null, index*range);
				
				for (var v = 0;v<values.length;v++) 
				{
					if (values[v] == 0) {
						data.push([v,null]);
						originalValues.push(values[v]);
				    } else {
				    	data.push([v,values[v]+index*range+(range/2),index*range]);
				    	originalValues.push(values[v]);//*+index*range+(range/2)*);
				    }
				}
				
				plotData.push({
					data:data,
					color:capped[i],
					tipLabel:i,
					capped:true,
					originalData:originalValues,
					lines:{
						fill:1,
						fillColor:capped[i]
					}
						
				});
				index++;
			}
			
			for (var i in uncapped) {
				var values = [],data=[],originalValues = [];
				for (var j in _avg) {
					var _date = _avg[j]['date'];
					values.push(trackerData['non_cappable_events'][i][_date]);
					originalValues.push(trackerData['non_cappable_events'][i][_date]);
				}
				
				values = this.normalizeValues(values, eventUnits||null, index*range,range);
				for (var v = 0;v<values.length;v++) 
				{
					data.push([v,values[v]]);
				}
				plotData.push({data:data,color:uncapped[i],tipLabel:i,originalData:originalValues});
				index++;
			}
			*/
			this.trackerPlot = $.plot(this.placeholder, this.getPlotData(selection), {
          		series: {
          			lines: {
          				show: true
          			},
          			points: {
          				show: true
          			}
          		},
          		grid: {
          			hoverable: true,
          			clickable: true
          		},
          		yaxis: {
          			min: 0,
          			max: this.plotMax,
          			ticks:[]
          		},
          		xaxis:{
          			min:0,
          			ticks:[]
          		}
          	});
          	
		},
		
		draw:function(range) {

			if (this.trackerPlot) {
				this.trackerPlot.shutdown();
				this.trackerPlot.destroy();
			}
			
			/*
			 * Eventually we will have real data
			 * Right now this is a test to get the slider to line up
			 * with the points on the plot
			 */
			var sin = [],cos=[];
			for (var i = 0;i<range;i++) {
				sin.push([i, Math.sin(i)]);
				cos.push([i, Math.cos(i)]);				
			}
			this.trackerPlot = $.plot(this.placeholder, [
         		{ data: sin/*, label: "sin(x)"*/},
         		{ data: cos/*, label: "cos(x)"*/}
         	], {
         		series: {
         			lines: {
         				show: true
         			},
         			points: {
         				show: true
         			}
         		},
         		grid: {
         			hoverable: true,
         			clickable: true
         		},
         		yaxis: {
         			min: -1.2,
         			max: 1.2
         		}
         	});
			
			$(this.placeholder).bind('plothover',function(event,pos,item) {
				if (item) {
					var thisValue = item.dataIndex,
						sliderValue = Tracker.getSliderValue($('#slider1').slider('values')[0]);
					if (thisValue == sliderValue) {
						$("#Tooltip").hide();
						return;
					}
					Tracker.doHover(event,pos,item);
				} else $("#Tooltip").hide();
			});
		},
		
		generateSliderValues:function(range,plotWidth) {
			if (!this.trackerPlot) this.draw(null,range);
			var offset = this.trackerPlot.getPlotOffset(),
				values = [],
				xaxis = this.trackerPlot.getAxes()['xaxis']||null;
			
			if (this.plotData.length<=0 && plotWidth) {
				var increment = plotWidth/range;
			
				for (var i = 0;i<range;i++) {
					values[i] = increment*i;
				}
			} else {
				if (!xaxis) return [];
				
				for (var i = 0;i<range;i++) {
					values.push(xaxis.p2c(i)/*+offset.left*/);
				}
			}
			this.sliderValues = $.extend({},values);
			return values;	
		},
		
		adjustSliderHandle:function(handle)
		{
			return;
			if (!handle) return;
			if (!this.offsetDiff) return;
			var left = parseInt(handle.css('left'));
			handle.css('left',left+this.offsetDiff+'px');
		},
		
		getSliderValue:function(v)
		{
			if (!v) return 0;
			if (!this.sliderValues) return 0;
			
			for (var i in this.sliderValues)
			{
				if (v.toFixed(2) == this.sliderValues[i].toFixed(2)) return parseInt(i);
			}
			return -1;
		},
		
		normalizeValues:function(values,units,displacement,range) {
			var sortedHash = {},
				sorted = [],
				offset = range?range/10:0;

			for (var i = 0;i<values.length;i++) {
				values[i] = this.convertUnit(values[i]);
			}
			
			for (var i = 0;i<values.length;i++) sortedHash[i] = values[i];
			for (var i in sortedHash) {
				sorted.push({index:i,value:parseFloat(sortedHash[i])});
			}
			
			sorted.sort(function(a,b) {
				if (a.value < b.value) return -1;
				else if (a.value == b.value) return 0;
				else return 1;
			});
			
			var max = sorted[sorted.length-1].value;
			for (var i = 0;i<values.length;i++) {
				if (max>0) {
					if (values[i] == max) {values[i] = displacement+range;}
					else if (values[i]<0.000001) {
						values[i] = displacement+offset;
					} else
						values[i] = (values[i]/max)*(range) + displacement +offset;
				} else values[i] = values[i] + displacement+offset;
			}
			return values;
		},
		
		convertUnit:function(value) {
			if (value == null) return 0;
			if (!eventUnits) return value;
			if (!value.split) return value;
			var tmp = value.split(/\s+/);
			if (tmp && tmp.length>1)
			{
				var unit = tmp[1];
				var index = $.inArray(unit,eventUnits);
				if (index>=0) {
					if (this['convert_'+eventUnits[index]]) {
						return this['convert_'+eventUnits[index]](tmp[0]);
					} else return tmp[0];
				} else return tmp[0];
			} else {return value;}
		},
		
		convert_am:function(value) {
			return this.convert_time(value);
		},
		
		convert_pm:function(value) {
			return this.convert_time(value);
		},
		
		convert_time:function(value) {
			if (!value) return 0;
			var baseDate = new Date();
			baseDate.setHours(0);
			baseDate.setMinutes(0);
			baseDate.setSeconds(0);
			var myD = new Date(baseDate.toDateString()+", "+value);
			var diff = Math.abs(baseDate.getTime() - myD.getTime());
			return diff/(1000*60);
		},
		
		convert_hours:function(value) {
			if (!value) return 0;
			return value*60*60;
		},
		convert_minutes:function(value) {
			if (!value) return 0;
			return value*60;
		},
		convert_lbs:function(value) {
			if (!value) return 0;
			return (value/2.20462)*1000;
		},
		convert_kg:function(value) {
			if (!value) return 0;
			return value*1000;
		},
		
		convert_miles:function(value) {
			if (!value) return 0;
			return value*5280*12*2.5;
		},
		convert_meters:function(value) {
			if (!value) return 0;
			return value*100;
		},
		
		convert_feet:function(value) {
			if (!value) return 0;
			return value*12*2.5;
		},
		
		convert_inches:function(value) {
			if (!value) return 0;
			return value*2.5;
		},
};