/**
 * Spider Graph for Moods
 */
 var R0old = 0;
 var R1old = 0;
 var R2old = 0;
 var R3old = 0;

 function drawradar(moodValues,canvasObj){
	 
	 	if (!canvasObj) canvasObj = $('canvas');
         var R0 = moodValues['happy']?2*5*moodValues['happy']:0;
         var R1 = moodValues['anxious']?2.5*moodValues['anxious']:0;//2*5*moodValues['angry'];
         var R2 = moodValues['sad']?2*5*moodValues['sad']:0;
         var R3 = moodValues['angry']?2*5*moodValues['angry']:0;//2*5*moodValues['anxious'];
         
         radar(R0old,R1old,R2old,R3old,R0,R1,R2,R3,canvasObj);

         R0old = R0;
         R1old = R1;
         R2old = R2;
         R3old = R3;
 }

 function radar(r0,r1,r2,r3,R0,R1,R2,R3,canvasObj){
	 
         var canvas = canvasObj&&canvasObj[0]?canvasObj[0]:$('canvas')[0];
         var ctx = canvas.getContext('2d');
		/*
         var x0 = canvas.width /2;
         var y0 = canvas.height/2;

         var xmax = canvas.width;
         var ymax = canvas.height;
         */
         var obj = canvasObj?canvasObj:$('canvas'),//$('canvas'),
         	x0 = parseInt(obj.css('width'))/2,
         	xmax = parseInt(obj.css('width')),
         	y0 = parseInt(obj.css('height'))/2,
         	ymax =parseInt(obj.css('height'));
			//console.log('sizes',[x0,y0,xmax,ymax]);
		 ctx.canvas.width=xmax;
		 ctx.canvas.height=ymax;
         ctx.save();
         ctx.clearRect(0,0,xmax,ymax);

         ctx.fillStyle = "rgba(50, 50, 50, 1)";
         ctx.fillRect(5, 5, xmax-10, ymax-10);


 	/*
 	ctx.shadowColor="transparent";
	
 	var happy  =Math.round(10*r0/13.4)/10+' ';
 	var angry  =Math.round(10*r3/13.4)/10+' ';
 	var sad    =Math.round(10*r2/13.4)/10+' ';
 	var anxious=Math.round(10*r1/13.4)/10+' ';
 	
 	ctx.font = "bolt 20px sans-serif";
 	ctx.fillStyle="rgba(128,97,188,1)";

	
 	ctx.fillText(angry  , 50, ymax-25);//quadrant 3
 	ctx.fillText(happy  , xmax-100, ymax-25);//quadrant 4
 	ctx.fillText(sad    , 50, 75);//quadrant 2
 	ctx.fillText(anxious, xmax-100, 75);//quadrant 1
	*/
 
         for (i=0;i<4;i++)
         {
 		 if (i<4) ctx.lineWidth=3;
 		 else     ctx.lineWidth=5;
          ctx.beginPath();
          ctx.strokeStyle = "rgba(100,100,100,1)";
          ctx.arc(x0, y0, i*40+60, 0, 2 * Math.PI, false);
          ctx.stroke();

         }
		
         var d0 = 10 + Math.pow(r0,1.7)/50;
         var d1 = 10 + Math.pow(r1,1.7)/50;
         var d2 = 10 + Math.pow(r2,1.7)/50;
         var d3 = 10 + Math.pow(r3,1.7)/50;
         
         ctx.beginPath();
         ctx.lineWidth=8;
         ctx.strokeStyle = "rgba(128,97,188,0.3)";
         
         ctx.moveTo(x0,y0+5+0.3*(r0+r3));

         ctx.lineTo(x0+r0,y0+r0);
         ctx.lineTo(x0+5+0.3*(r0+r1),y0);
         
         ctx.lineTo(x0+r1,y0-r1);
         ctx.lineTo(x0,y0-5-0.3*(r1+r2));

         ctx.lineTo(x0-r2,y0-r2);
         ctx.lineTo(x0-5-0.3*(r2+r3),y0);

         ctx.lineTo(x0-r3,y0+r3);
         ctx.lineTo(x0,y0+5+0.3*(r0+r3));

         ctx.stroke();
		
         ctx.beginPath();
         ctx.lineWidth=4;
         ctx.strokeStyle = "rgba(128,97,188,0.9)";
         
         ctx.moveTo(x0,y0+5+0.3*(r0+r3));

         ctx.lineTo(x0+r0,y0+r0);
         ctx.lineTo(x0+5+0.3*(r0+r1),y0);
         
         ctx.lineTo(x0+r1,y0-r1);
         ctx.lineTo(x0,y0-5-0.3*(r1+r2));

         ctx.lineTo(x0-r2,y0-r2);
         ctx.lineTo(x0-5-0.3*(r2+r3),y0);

         ctx.lineTo(x0-r3,y0+r3);
         ctx.lineTo(x0,y0+5+0.3*(r0+r3));


         ctx.stroke();
 
         ctx.beginPath();
         ctx.lineWidth=1;
         ctx.strokeStyle = "rgba(255,255,255,1)";
         ctx.fillStyle   = "rgba(128,97,188,0.5)";
         
         ctx.moveTo(x0,y0+5+0.3*(r0+r3));
         
         ctx.lineTo(x0+r0,y0+r0);
         ctx.lineTo(x0+5+0.3*(r0+r1),y0);
        
         ctx.lineTo(x0+r1,y0-r1);
         ctx.lineTo(x0,y0-5-0.3*(r1+r2));

         ctx.lineTo(x0-r2,y0-r2);
         ctx.lineTo(x0-5-0.3*(r2+r3),y0);

         ctx.lineTo(x0-r3,y0+r3);
         ctx.lineTo(x0,y0+5+0.3*(r0+r3));

         ctx.stroke();
         
         ctx.fill();

      	ctx.font = "15px sans-serif";
     	ctx.fillStyle="rgba(200,200,200,1)";

     	ctx.shadowOffsetX=0;
     	ctx.shadowOffsetY=0;
     	ctx.shadowBlur=6;
     	ctx.shadowColor="#AE84FF";

     	ctx.fillText("ANGRY",   50, ymax-50);//quadrant 3 
     	ctx.fillText("HAPPY",   xmax-100, ymax-50);//quadrant 4
     	ctx.fillText("SAD",     50, 50);//quadrant 2
     	ctx.fillText("ANXIOUS", xmax-100, 50);//quadrant 1
         var flag = 0;
         var Timer1;
		/*
         if (R0>r0) r0+=1;
         if (R0<r0) r0-=1;
         if (R0==r0)flag++;

         if (R1>r1) r1+=1;
         if (R1<r1) r1-=1;
         if (R1==r1)flag++;

         if (R2>r2) r2+=1;
         if (R2<r2) r2-=1;
         if (R2==r2)flag++;

         if (R3>r3) r3+=1;
         if (R3<r3) r3-=1;
         if (R3==r3)flag++;
		*/
	    if (radarRound(R0)>radarRound(r0)) r0+=0.1;
        if (radarRound(R0)<radarRound(r0)) r0-=0.1;
        if (radarRound(R0)==radarRound(r0))flag++;

        if (radarRound(R1)>radarRound(r1)) r1+=0.1;
        if (radarRound(R1)<radarRound(r1)) r1-=0.1;
        if (radarRound(R1)==radarRound(r1))flag++;

        if (radarRound(R2)>radarRound(r2)) r2+=0.1;
        if (radarRound(R2)<radarRound(r2)) r2-=1;
        if (radarRound(R2)==radarRound(r2))flag++;

        if (radarRound(R3)>radarRound(r3)) r3+=0.1;
        if (radarRound(R3)<radarRound(r3)) r3-=0.1;
        if (radarRound(R3)==radarRound(r3))flag++;
		
         if (flag !=4)  Timer1 = setTimeout('radar('+r0+','+r1+','+r2+','+r3+','+R0+','+R1+','+R2+','+R3+')',1);
         if (flag ==4)  return;
 }

 function radarRound(num,places)
 {
	 if (!num) return 0.0;
	 var to = 10;
	 if (places) to = places*10;
	 return Math.round(num*to)/to;
 }