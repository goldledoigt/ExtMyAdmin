<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" href="/lib/ext/resources/css/ext-all.css" />
		<link rel="stylesheet" type="text/css" href="static/css/style.css" />
	</head>

	<body>
		<script type="text/javascript" src="/lib/ext/adapter/ext/ext-base-debug.js"></script>
		<script type="text/javascript" src="/lib/ext/ext-all-debug.js"></script>

		<script>
			API = {
				url:"php/controller/index.php"
				,type:"remoting"
				,actions:{
					grid:[
						{name:"read", len:5}
						,{name:"update", len:1}
						,{name:"create", len:1}
						,{name:"destroy", len:1}
					]
				}
			};
			Ext.Direct.addProvider(API);
		</script>

		<script type="text/javascript" src="static/js/Ext.ux.DirectMetaGrid.js"></script>

		<script>
			Ext.onReady( function() {
			    new Ext.ux.EditorGridPanel({
					height:200
					,width:500
				}).render(document.body);
			});
		</script>
	</body>

</html>