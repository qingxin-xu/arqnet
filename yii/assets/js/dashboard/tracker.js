var Tracker = {
	
		trackerPlot:null,
		placeholder:'#trackerChart',

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
				self = this;
			
			this.trackerPlot.unhighlight();
			$.each(this.trackerPlot.getData(),function(index,item) {
				var point = {x:item.data[sliderValue][0],y:item.data[sliderValue][1]};
				var pointOffset = self.trackerPlot.pointOffset(point);

				$("#Tooltip"+index).html('Y = '+point['y'].toFixed(2))
				.css({top: pointOffset.top, left: pointOffset.left+40})
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
		
		generateSliderValues:function(range) {
			if (!this.trackerPlot) this.draw(null,range);
			var offset = this.trackerPlot.getPlotOffset(),
				values = [],
				xaxis = this.trackerPlot.getAxes()['xaxis']||null;
			
				if (!xaxis) return [];
				for (var i = 0;i<range;i++) {
					values.push(xaxis.p2c(i)/*+offset.left*/);
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
			return 0;
		}
		
};