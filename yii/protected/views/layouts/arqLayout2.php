<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="ArQnet Dashboard" />
	<meta name="author" content="" />
	
	<title>ArQnet | <?php echo CHtml::encode($this->pageTitle); ?></title>
	<link href='http://fonts.googleapis.com/css?family=Roboto:100' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
	<link rel="stylesheet" href="/assets/css/font-icons/entypo/css/entypo.css">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
	<link rel="stylesheet" href="/assets/css/bootstrap.css">
	<!--  
	<link rel="stylesheet" href="/assets/css/neon-core.css">
	<link rel="stylesheet" href="/assets/css/neon-theme.css">
	<link rel="stylesheet" href="/assets/css/neon-forms.css">
	-->
	<link rel="stylesheet" href="assets/css/neon.css">
	<link rel="stylesheet" type="text/css" href="styles/addonsG.css">
	<link rel="stylesheet" href="/assets/css/custom.css">
	
	<style type='text/css'>
		.addG-date {margin:-23px 42%;}
		.addG-panelhalfheight {height:398px;}
	</style>
	<script src="/assets/js/jquery-1.11.0.min.js"></script>

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
	
<script type='text/javascript'>

function updateMsg( description,t ) {
	description
      .text( t );
 }
 
</script>
</head>
<body class="page-body  page-fade">

<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->	


	<div class="sidebar-menu">
		
			
		<header class="logo-env">
			
			<!-- logo -->
			<div class="logo">
				<a href="index.html">
					<img src="assets/images/logo@2x.png" width="120" alt="" />
				</a>
			</div>
			
						<!-- logo collapse icon -->
						
			<div class="sidebar-collapse">
				<a href="#" class="sidebar-collapse-icon with-animation"><!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition -->
					<i class="entypo-menu"></i>
				</a>
			</div>
			
									
			
			<!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
			<div class="sidebar-mobile-menu visible-xs">
				<a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
					<i class="entypo-menu"></i>
				</a>
			</div>
			
		</header>
				
		
		
				
		
				
		<ul id="main-menu" class="">
			<!-- add class "multiple-expanded" to allow multiple submenus to open -->
			<!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
			<!-- Search Bar -->
			<li id="search">
				<form method="get" action="">
					<input type="text" name="q" class="search-input" placeholder="Search something..."/>
					<button type="submit">
						<i class="entypo-search"></i>
					</button>
				</form>
			</li>
			<li class="active opened active">
				<a href="/">
					<i class="entypo-gauge"></i>
					<span>HOME</span>
				</a>
				
			</li>
			<li>
				<a href="/arq">
					<i class="entypo-layout"></i>
					<span>ARQ</span>
				</a>
				
			</li>
			<li>
				<a href="/myJournals">
					<i class="entypo-newspaper"></i>
					<span>JOURNAL</span>
				</a>
				
			</li>
			<li>
						
						<a href="/calendar">
							<i class="entypo-calendar"></i>
							<span>CALENDAR</span>
						</a>
					</li>
			<li>
				<a href="/profile">
					<i class="entypo-doc-text"></i>
					<span>SETTINGS</span>
				</a>
				
			</li>	

		</ul>
				
	</div>	
		<div class="main-content" >
		
<?php $this->widget('HeaderWidget'); ?>



<?php echo $content; ?>

</div>



	<link rel="stylesheet" href="/assets/js/selectboxit/jquery.selectBoxIt.css">

	<link rel="stylesheet" href="/assets/js/rickshaw/rickshaw.min.css">
	<link rel="stylesheet" href="/assets/js/daterangepicker/daterangepicker-bs3.css">
	<!--  <link rel="stylesheet" href="/assets/css/bootstrap-timepicker.min.css">-->

	<!-- Bottom Scripts -->
	<script src="/assets/js/gsap/main-gsap.js"></script>
	<script src="/assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="/assets/js/bootstrap.min.js"></script>
	<script src="/assets/js/joinable.js"></script>
	<script src="/assets/js/resizeable.js"></script>
	<script src="/assets/js/bootstrap-datepicker.js"></script>
	<script src="/assets/js/bootstrap-timepicker.min.js"></script>

		<script src="/assets/js/daterangepicker/moment.min.js"></script>
	<script src="/assets/js/daterangepicker/daterangepicker.js"></script>
	<script src="/assets/js/neon-api.js"></script>
	<script src="/assets/js/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
	<script src="/assets/js/jvectormap/jquery-jvectormap-europe-merc-en.js"></script>
	<script src="/assets/js/jquery.sparkline.min.js"></script>
	<script src="/assets/js/rickshaw/vendor/d3.v3.js"></script>
	<script src="/assets/js/rickshaw/rickshaw.min.js"></script>
	<script src="/assets/js/raphael-min.js"></script>
	<script src="/assets/js/morris.min.js"></script>

	<script src="/assets/js/jquery.peity.min.js"></script>
	

	<script src="/assets/js/toastr.js"></script>
	
	<script src="/assets/js/neon-custom.js"></script>
	<script src="/assets/js/neon-demo.js"></script>
	<script src='/assets/js/flot/jquery.flot.js'></script>
	<script src='/assets/js/flot/jquery.flot.time.js'></script>
	
<?php 
/*
 * This is an extension of the slider, provided here
 * http://stackoverflow.com/questions/3836674/add-padding-to-jquery-ui-slider
 * 
 * so that the min and max values do not fall on the edge of the box but are instead
 * shifted over to the right and left, respectively
 * 
 * This extension adds two new parameters to the 'option' object on the slider - 
 * paddingMin and paddingMax
 */
?>
	<script type='text/javascript'>

(
	    function($, undefined)
	    {
	        $.ui.slider.prototype.options =
	            $.extend(
	                {},
	                $.ui.slider.prototype.options,
	                {
	                    paddingMin: 0,
	                    paddingMax: 0
	                }
	            );

	        $.ui.slider.prototype._refreshValue =
	            function() {
	                var
	                    oRange = this.options.range,
	                    o = this.options,
	                    self = this,
	                    animate = ( !this._animateOff ) ? o.animate : false,
	                    valPercent,
	                    _set = {},
	                    elementWidth,
	                    elementHeight,
	                    paddingMinPercent,
	                    paddingMaxPercent,
	                    paddedBarPercent,
	                    lastValPercent,
	                    value,
	                    valueMin,
	                    valueMax;

	                if (self.orientation === "horizontal")
	                {
	                    elementWidth = this.element.outerWidth();
	                    paddingMinPercent = o.paddingMin * 100 / elementWidth;
	                    paddedBarPercent = ( elementWidth - ( o.paddingMin + o.paddingMax) ) * 100 / elementWidth;
	                }
	                else
	                {
	                    elementHeight = this.element.outerHeight();
	                    paddingMinPercent = o.paddingMin * 100 / elementHeight;
	                    paddedBarPercent = ( elementHeight - ( o.paddingMin + o.paddingMax) ) * 100 / elementHeight;
	                }

	                if ( this.options.values && this.options.values.length ) {
	                    this.handles.each(function( i, j ) {
	                        valPercent =
	                            ( ( self.values(i) - self._valueMin() ) / ( self._valueMax() - self._valueMin() ) * 100 )
	                            * paddedBarPercent / 100 + paddingMinPercent;
	                        _set[ self.orientation === "horizontal" ? "left" : "bottom" ] = valPercent + "%";
	                        $( this ).stop( 1, 1 )[ animate ? "animate" : "css" ]( _set, o.animate );
	                        if ( self.options.range === true ) {
	                            if ( self.orientation === "horizontal" ) {
	                                if ( i === 0 ) {
	                                    self.range.stop( 1, 1 )[ animate ? "animate" : "css" ]( { left: valPercent + "%" }, o.animate );
	                                }
	                                if ( i === 1 ) {
	                                    self.range[ animate ? "animate" : "css" ]( { width: ( valPercent - lastValPercent ) + "%" }, { queue: false, duration: o.animate } );
	                                }
	                            } else {
	                                if ( i === 0 ) {
	                                    self.range.stop( 1, 1 )[ animate ? "animate" : "css" ]( { bottom: ( valPercent ) + "%" }, o.animate );
	                                }
	                                if ( i === 1 ) {
	                                    self.range[ animate ? "animate" : "css" ]( { height: ( valPercent - lastValPercent ) + "%" }, { queue: false, duration: o.animate } );
	                                }
	                            }
	                        }
	                        lastValPercent = valPercent;
	                    });
	                } else {
	                    value = this.value();
	                    valueMin = this._valueMin();
	                    valueMax = this._valueMax();
	                    valPercent =
	                        ( ( valueMax !== valueMin )
	                        ? ( value - valueMin ) / ( valueMax - valueMin ) * 100
	                        : 0 )
	                        * paddedBarPercent / 100 + paddingMinPercent;

	                    _set[ self.orientation === "horizontal" ? "left" : "bottom" ] = valPercent + "%";

	                    this.handle.stop( 1, 1 )[ animate ? "animate" : "css" ]( _set, o.animate );

	                    if ( oRange === "min" && this.orientation === "horizontal" ) {
	                        this.range.stop( 1, 1 )[ animate ? "animate" : "css" ]( { width: valPercent + "%" }, o.animate );
	                    }
	                    if ( oRange === "max" && this.orientation === "horizontal" ) {
	                        this.range[ animate ? "animate" : "css" ]( { width: ( 100 - valPercent ) + "%" }, { queue: false, duration: o.animate } );
	                    }
	                    if ( oRange === "min" && this.orientation === "vertical" ) {
	                        this.range.stop( 1, 1 )[ animate ? "animate" : "css" ]( { height: valPercent + "%" }, o.animate );
	                    }
	                    if ( oRange === "max" && this.orientation === "vertical" ) {
	                        this.range[ animate ? "animate" : "css" ]( { height: ( 100 - valPercent ) + "%" }, { queue: false, duration: o.animate } );
	                    }
	                }
	            };

	        $.ui.slider.prototype._normValueFromMouse =
	            function( position ) {
	                var
	                    o = this.options,
	                    pixelTotal,
	                    pixelMouse,
	                    percentMouse,
	                    valueTotal,
	                    valueMouse;

	                if ( this.orientation === "horizontal" ) {
	                    pixelTotal = this.elementSize.width - (o.paddingMin + o.paddingMax);
	                    pixelMouse = position.x - this.elementOffset.left - o.paddingMin - ( this._clickOffset ? this._clickOffset.left : 0 );
	                } else {
	                    pixelTotal = this.elementSize.height - (o.paddingMin + o.paddingMax);
	                    pixelMouse = position.y - this.elementOffset.top - o.paddingMin - ( this._clickOffset ? this._clickOffset.top : 0 );
	                }

	                percentMouse = ( pixelMouse / pixelTotal );
	                if ( percentMouse > 1 ) {
	                    percentMouse = 1;
	                }
	                if ( percentMouse < 0 ) {
	                    percentMouse = 0;
	                }
	                if ( this.orientation === "vertical" ) {
	                    percentMouse = 1 - percentMouse;
	                }

	                valueTotal = this._valueMax() - this._valueMin();
	                valueMouse = this._valueMin() + percentMouse * valueTotal;

	                return this._trimAlignValue( valueMouse );
	            };
	    }
	)(jQuery);


</script>

</body>
</html>