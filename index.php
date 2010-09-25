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
					tree:[
						{name:"read", len:3}
						,{name:"update", len:4}
						,{name:"create", len:2}
						,{name:"destroy", len:2}
					]
					,grid:[
						{name:"read", len:6}
						,{name:"update", len:1}
						,{name:"create", len:1}
						,{name:"destroy", len:1}
					]
				}
			};
		</script>

		<script type="text/javascript" src="static/js/Ext.ux.DirectMetaGrid.js"></script>
		<script type="text/javascript" src="static/js/ExtMyAdmin.TableGrid.js"></script>
		<script type="text/javascript" src="static/js/ExtMyAdmin.BrowsingTree.js"></script>

		<script>
			Ext.onReady( function() {

				Ext.Direct.addProvider(API);

				Ext.QuickTips.init();

				new Ext.Viewport({
					layout:"border"
					,items:[{
						xtype:"tablegrid"
						,ref:"tableGrid"
						,region:"center"
						,margins:"4 4 4 0"
						,limit:28
						,api:{
			            	read:grid.read
			            	,create:grid.create
			            	,update:grid.update
			            	,destroy:grid.destroy
			          	}
					}, {
						xtype:"browsingtree"
						,ref:"browsingTree"
						,region:"west"
						,split:true
						,margins:"4 0 4 4"
				        ,width:250
					}]
					,listeners:{
						afterrender:function() {
							this.tableGrid.relayEvents(this.browsingTree, ["tableselect"]);
						}
					}
				});

			});
		</script>
	</body>

</html>