var spiderGraph = {
	canvasObj:null,
	debug:false,
	R0old:0,
	R1old:0,
	R2old:0,
	R3old:0,
	R0:0,
	R1:0,
	R2:0,
	R3:0,
	R0act:0,
	R1act:0,
	R2act:0,
	R3act:0,
	isRunning:0,
	scale:0.75,
	step:0,
	maxSteps:100,
	
	rescale:function(number) {
		return (number*this.scale+0.5)|0;		// returns rounded int
	},
	
	drawradar:function(moodValues,canvasObj) {
		this.isRunning++;

		if (!canvasObj) canvasObj = this.canvasObj||$('canvas');
		// read new stop values
		this.R2 = this.rescale(110*parseFloat(moodValues['happy']?moodValues['happy']:0));
		this.R1 = this.rescale(110*parseFloat(moodValues['angry']?moodValues['angry']:0));
		this.R3 = this.rescale(110*parseFloat(moodValues['sad']?moodValues['sad']:0));
		this.R0 = this.rescale(110*parseFloat(moodValues['anxious']?moodValues['anxious']:0));

		if (this.isRunning==1)		// only one instance of drawing function running
		{
			this.step=0;	
			var self = this;
			this.radar1(function() 
			{
				//alert("Drawing complete");
				self.R0old = self.R0;
				self.R1old = self.R1;
				self.R2old = self.R2;
				self.R3old = self.R3;
			},canvasObj);
		}
		else
		{
			this.isRunning--;
			this.R0old = this.R0act;
			this.R1old = this.R1act;
			this.R2old = this.R2act;
			this.R3old = this.R3act;
			this.step=0;	
		}		
	},
	
	radar1:function(_callback,canvasObj) {
		var r0 = this.R0old + (this.R0-this.R0old)/this.maxSteps*this.step;
		var r1 = this.R1old + (this.R1-this.R1old)/this.maxSteps*this.step;
		var r2 = this.R2old + (this.R2-this.R2old)/this.maxSteps*this.step;
		var r3 = this.R3old + (this.R3-this.R3old)/this.maxSteps*this.step;

		this.R0act = r0;
		this.R1act = r1;
		this.R2act = r2;
		this.R3act = r3;
		
		//var canvas = document.getElementById('canvas');
		var canvas = canvasObj&&canvasObj[0]?canvasObj[0]:$('canvas')[0];
		var ctx = canvas.getContext('2d');
		var x0   = parseInt(canvasObj.css('width'))/2;//canvas.width /2;
		var y0   = parseInt(canvasObj.css('height'))/2;//canvas.height/2;
		var xmax = parseInt(canvasObj.css('width'));//canvas.width;
		var ymax = parseInt(canvasObj.css('height'));//canvas.height;
		if (this.debug) {
			console.log('rectangle',xmax,ymax);
		}
		ctx.save();
		ctx.clearRect(0,0,Math.max(xmax,canvas.width),Math.max(ymax,canvas.height));
		ctx.font = this.rescale(20) + "px sans-serif";
		ctx.fillStyle="rgba(200,200,200,1)";

		ctx.shadowOffsetX=0;
		ctx.shadowOffsetY=0;
		ctx.shadowBlur=6;
		ctx.shadowColor="#AE84FF";

		ctx.fillText("ANGRY",   x0+this.rescale(100), y0-this.rescale(150));
		ctx.fillText("HAPPY",   x0-this.rescale(175), y0-this.rescale(150));
		ctx.fillText("SAD",     x0-this.rescale(175), y0+this.rescale(150));
		ctx.fillText("ANXIOUS", x0+this.rescale(100), y0+this.rescale(150));

		// draw circles
		ctx.shadowColor="transparent";
		
		for (i=0;i<4;i++)
		{	
			if (i<3) ctx.lineWidth=rescale(3);
			else     ctx.lineWidth=rescale(5);

			ctx.beginPath();
			ctx.strokeStyle = "rgba(100,100,100,1)";
			ctx.arc(x0, y0, rescale(i*35+60), 0, 2 * Math.PI, false);
			ctx.stroke();
		}
		
		// draw graphs - 1
		ctx.beginPath();
		ctx.lineWidth=8;
		ctx.strokeStyle = "rgba(128,97,188,0.3)";

		// intersection parameters
	  
		var delta1 = this.rescale(60);
		var delta2 = this.rescale(15);

		var r00 = 0.7*(r0 + delta1);
		var r11 = 0.7*(r1 + delta1);
		var r22 = 0.7*(r2 + delta1);
		var r33 = 0.7*(r3 + delta1);

		var iXY = delta2+0.3*Math.pow((r00*r11*r22*r33),0.235);

		var iYpos =  iXY;
		var iXpos =  iXY;
		var iYneg = -iXY;
		var iXneg = -iXY;

		var round=this.rescale(6);	//4

	      ctx.moveTo(x0,y0+iYpos);
	 
	      ctx.bezierCurveTo(x0+iXpos,y0+iYpos,x0+r00-round,y0+r00+round,x0+r00,y0+r00);
		ctx.bezierCurveTo(x0+r00+round,y0+r00-round,x0+iXpos,y0+iYpos,x0+iXpos,y0);
	        
	      ctx.bezierCurveTo(x0+iXpos,y0+iYneg,x0+r11+round,y0-r11+round,x0+r11,y0-r11);
		ctx.bezierCurveTo(x0+r11-round,y0-r11-round,x0+iXpos,y0+iYneg,x0,y0+iYneg);

	      ctx.bezierCurveTo(x0+iXneg,y0+iYneg,x0-r22+round,y0-r22-round,x0-r22,y0-r22);
		ctx.bezierCurveTo(x0-r22-round,y0-r22+round,x0+iXneg,y0+iYneg,x0+iXneg,y0);

	      ctx.bezierCurveTo(x0+iXneg,y0+iYpos,x0-r33-round,y0+r33-round,x0-r33,y0+r33);
		ctx.bezierCurveTo(x0-r33+round,y0+r33+round,x0+iXneg,y0+iYpos,x0,y0+iYpos);

		ctx.stroke();

		// draw graph - 2
		ctx.beginPath();
		ctx.lineWidth=4;
		ctx.strokeStyle = "rgba(128,97,188,0.9)";
		ctx.moveTo(x0,y0+iYpos);

	      ctx.bezierCurveTo(x0+iXpos,y0+iYpos,x0+r00-round,y0+r00+round,x0+r00,y0+r00);
		ctx.bezierCurveTo(x0+r00+round,y0+r00-round,x0+iXpos,y0+iYpos,x0+iXpos,y0);
	        
	      ctx.bezierCurveTo(x0+iXpos,y0+iYneg,x0+r11+round,y0-r11+round,x0+r11,y0-r11);
		ctx.bezierCurveTo(x0+r11-round,y0-r11-round,x0+iXpos,y0+iYneg,x0,y0+iYneg);

	      ctx.bezierCurveTo(x0+iXneg,y0+iYneg,x0-r22+round,y0-r22-round,x0-r22,y0-r22);
		ctx.bezierCurveTo(x0-r22-round,y0-r22+round,x0+iXneg,y0+iYneg,x0+iXneg,y0);

	      ctx.bezierCurveTo(x0+iXneg,y0+iYpos,x0-r33-round,y0+r33-round,x0-r33,y0+r33);
		ctx.bezierCurveTo(x0-r33+round,y0+r33+round,x0+iXneg,y0+iYpos,x0,y0+iYpos);

		ctx.stroke();

		// draw graph - 3
		ctx.beginPath();
		ctx.lineWidth=2;
		ctx.strokeStyle = "rgba(255,255,255,1)";
		ctx.fillStyle   = "rgba(128,97,188,0.8)";
	      ctx.moveTo(x0,y0+iYpos);

	      ctx.bezierCurveTo(x0+iXpos,y0+iYpos,x0+r00-round,y0+r00+round,x0+r00,y0+r00);
		ctx.bezierCurveTo(x0+r00+round,y0+r00-round,x0+iXpos,y0+iYpos,x0+iXpos,y0);
	        
	      ctx.bezierCurveTo(x0+iXpos,y0+iYneg,x0+r11+round,y0-r11+round,x0+r11,y0-r11);
		ctx.bezierCurveTo(x0+r11-round,y0-r11-round,x0+iXpos,y0+iYneg,x0,y0+iYneg);

	      ctx.bezierCurveTo(x0+iXneg,y0+iYneg,x0-r22+round,y0-r22-round,x0-r22,y0-r22);
		ctx.bezierCurveTo(x0-r22-round,y0-r22+round,x0+iXneg,y0+iYneg,x0+iXneg,y0);

	      ctx.bezierCurveTo(x0+iXneg,y0+iYpos,x0-r33-round,y0+r33-round,x0-r33,y0+r33);
		ctx.bezierCurveTo(x0-r33+round,y0+r33+round,x0+iXneg,y0+iYpos,x0,y0+iYpos);

		ctx.stroke();
		ctx.fill();

		var Timer1;

		if (this.step<this.maxSteps)
		{
			var self = this;
			this.step++;
			Timer1 = setTimeout(function() {self.radar1(_callback,canvasObj);},10);
		}
		else 
		{
			this.isRunning=0;
			_callback();
			return;
		}		
	}
};

var R0old = 0;
var R1old = 0;
var R2old = 0;
var R3old = 0;

var R0;
var R1;
var R2;
var R3;

var R0act;
var R1act;
var R2act;
var R3act;

var isRunning = 0;	// keep track of instances of drawing functions
var scale     = .75;	// fits 400x400px canvas
var step      = 0; 	
var maxSteps  = 100;	// smaller number will run the animation faster. default: 100

function rescale(number)
{
	return (number*scale+0.5)|0;		// returns rounded int
}

function drawradar(moodValues,canvasObj)
{
	console.log('draw',moodValues);
	isRunning++;

	if (!canvasObj) canvasObj = $('canvas');
	// read new stop values
	R2 = rescale(110*parseFloat(moodValues['happy']?moodValues['happy']:0));
	R1 = rescale(110*parseFloat(moodValues['angry']?moodValues['angry']:0));
	R3 = rescale(110*parseFloat(moodValues['sad']?moodValues['sad']:0));
	R0 = rescale(110*parseFloat(moodValues['anxious']?moodValues['anxious']:0));

	if (isRunning==1)		// only one instance of drawing function running
	{
		step=0;	
		radar1(function() 
		{
			//alert("Drawing complete");
			R0old = R0;
			R1old = R1;
			R2old = R2;
			R3old = R3;
		},canvasObj);
	}
	else
	{
		isRunning--;
		R0old = R0act;
		R1old = R1act;
		R2old = R2act;
		R3old = R3act;
		step=0;	
	}
}

function radar1(_callback,canvasObj){

	var r0 = R0old + (R0-R0old)/maxSteps*step;
	var r1 = R1old + (R1-R1old)/maxSteps*step;
	var r2 = R2old + (R2-R2old)/maxSteps*step;
	var r3 = R3old + (R3-R3old)/maxSteps*step;

	R0act = r0;
	R1act = r1;
	R2act = r2;
	R3act = r3;
	console.log('canvasObj',canvasObj);
	//var canvas = document.getElementById('canvas');
	var canvas = canvasObj&&canvasObj[0]?canvasObj[0]:$('canvas')[0];
	var ctx = canvas.getContext('2d');
	var x0   = parseInt(canvasObj.css('width'))/2;//canvas.width /2;
	var y0   = parseInt(canvasObj.css('height'))/2;//canvas.height/2;
	var xmax = parseInt(canvasObj.css('width'));//canvas.width;
	var ymax = parseInt(canvasObj.css('height'));//canvas.height;
	
	ctx.save();
	ctx.clearRect(0,0,xmax,ymax);

//	ctx.fillStyle = "rgba(50, 50, 50, 1)";		// transparent background
//	ctx.fillRect(5, 5, xmax-10, ymax-10);		// 

	ctx.font = rescale(20) + "px sans-serif";
	ctx.fillStyle="rgba(200,200,200,1)";

	ctx.shadowOffsetX=0;
	ctx.shadowOffsetY=0;
	ctx.shadowBlur=6;
	ctx.shadowColor="#AE84FF";

	//ctx.fillText("ANGRY",   xmax-rescale(100), rescale(50));
	//ctx.fillText("HAPPY",   rescale(25),       rescale(50));
	//ctx.fillText("SAD",     rescale(25),       ymax-rescale(50));
	//ctx.fillText("ANXIOUS", xmax-rescale(100), ymax-rescale(50));

	ctx.fillText("ANGRY",   x0+rescale(100), y0-rescale(150));
	ctx.fillText("HAPPY",   x0-rescale(175), y0-rescale(150));
	ctx.fillText("SAD",     x0-rescale(175), y0+rescale(150));
	ctx.fillText("ANXIOUS", x0+rescale(100), y0+rescale(150));

	// draw circles
	ctx.shadowColor="transparent";
	
	for (i=0;i<4;i++)
	{	
		if (i<3) ctx.lineWidth=rescale(3);
		else     ctx.lineWidth=rescale(5);

		ctx.beginPath();
		ctx.strokeStyle = "rgba(100,100,100,1)";
		console.log('Center',x0,y0,i,i*35+60,rescale(i*35+60));
		ctx.arc(x0, y0, rescale(i*35+60), 0, 2 * Math.PI, false);
		ctx.stroke();
	}
	
	// draw graphs - 1
	ctx.beginPath();
	ctx.lineWidth=8;
	ctx.strokeStyle = "rgba(128,97,188,0.3)";

	// intersection parameters
  
	var delta1 = rescale(60);
	var delta2 = rescale(15);

	var r00 = 0.7*(r0 + delta1);
	var r11 = 0.7*(r1 + delta1);
	var r22 = 0.7*(r2 + delta1);
	var r33 = 0.7*(r3 + delta1);

	var iXY = delta2+0.3*Math.pow((r00*r11*r22*r33),0.235);

	var iYpos =  iXY;
	var iXpos =  iXY;
	var iYneg = -iXY;
	var iXneg = -iXY;

	var round=rescale(6);	//4

      ctx.moveTo(x0,y0+iYpos);
 
      ctx.bezierCurveTo(x0+iXpos,y0+iYpos,x0+r00-round,y0+r00+round,x0+r00,y0+r00);
	ctx.bezierCurveTo(x0+r00+round,y0+r00-round,x0+iXpos,y0+iYpos,x0+iXpos,y0);
        
      ctx.bezierCurveTo(x0+iXpos,y0+iYneg,x0+r11+round,y0-r11+round,x0+r11,y0-r11);
	ctx.bezierCurveTo(x0+r11-round,y0-r11-round,x0+iXpos,y0+iYneg,x0,y0+iYneg);

      ctx.bezierCurveTo(x0+iXneg,y0+iYneg,x0-r22+round,y0-r22-round,x0-r22,y0-r22);
	ctx.bezierCurveTo(x0-r22-round,y0-r22+round,x0+iXneg,y0+iYneg,x0+iXneg,y0);

      ctx.bezierCurveTo(x0+iXneg,y0+iYpos,x0-r33-round,y0+r33-round,x0-r33,y0+r33);
	ctx.bezierCurveTo(x0-r33+round,y0+r33+round,x0+iXneg,y0+iYpos,x0,y0+iYpos);

	ctx.stroke();

	// draw graph - 2
	ctx.beginPath();
	ctx.lineWidth=4;
	ctx.strokeStyle = "rgba(128,97,188,0.9)";
	ctx.moveTo(x0,y0+iYpos);

      ctx.bezierCurveTo(x0+iXpos,y0+iYpos,x0+r00-round,y0+r00+round,x0+r00,y0+r00);
	ctx.bezierCurveTo(x0+r00+round,y0+r00-round,x0+iXpos,y0+iYpos,x0+iXpos,y0);
        
      ctx.bezierCurveTo(x0+iXpos,y0+iYneg,x0+r11+round,y0-r11+round,x0+r11,y0-r11);
	ctx.bezierCurveTo(x0+r11-round,y0-r11-round,x0+iXpos,y0+iYneg,x0,y0+iYneg);

      ctx.bezierCurveTo(x0+iXneg,y0+iYneg,x0-r22+round,y0-r22-round,x0-r22,y0-r22);
	ctx.bezierCurveTo(x0-r22-round,y0-r22+round,x0+iXneg,y0+iYneg,x0+iXneg,y0);

      ctx.bezierCurveTo(x0+iXneg,y0+iYpos,x0-r33-round,y0+r33-round,x0-r33,y0+r33);
	ctx.bezierCurveTo(x0-r33+round,y0+r33+round,x0+iXneg,y0+iYpos,x0,y0+iYpos);

	ctx.stroke();

	// draw graph - 3
	ctx.beginPath();
	ctx.lineWidth=2;
	ctx.strokeStyle = "rgba(255,255,255,1)";
	ctx.fillStyle   = "rgba(128,97,188,0.8)";
      ctx.moveTo(x0,y0+iYpos);

      ctx.bezierCurveTo(x0+iXpos,y0+iYpos,x0+r00-round,y0+r00+round,x0+r00,y0+r00);
	ctx.bezierCurveTo(x0+r00+round,y0+r00-round,x0+iXpos,y0+iYpos,x0+iXpos,y0);
        
      ctx.bezierCurveTo(x0+iXpos,y0+iYneg,x0+r11+round,y0-r11+round,x0+r11,y0-r11);
	ctx.bezierCurveTo(x0+r11-round,y0-r11-round,x0+iXpos,y0+iYneg,x0,y0+iYneg);

      ctx.bezierCurveTo(x0+iXneg,y0+iYneg,x0-r22+round,y0-r22-round,x0-r22,y0-r22);
	ctx.bezierCurveTo(x0-r22-round,y0-r22+round,x0+iXneg,y0+iYneg,x0+iXneg,y0);

      ctx.bezierCurveTo(x0+iXneg,y0+iYpos,x0-r33-round,y0+r33-round,x0-r33,y0+r33);
	ctx.bezierCurveTo(x0-r33+round,y0+r33+round,x0+iXneg,y0+iYpos,x0,y0+iYpos);

	ctx.stroke();
	ctx.fill();

	var Timer1;

	if (step<maxSteps)
	{
		step++;
		Timer1 = setTimeout(function() {radar1(_callback,canvasObj);},10);
	}
	else 
	{
		isRunning=0;
		_callback();
		return;
	}
} // end radar1


