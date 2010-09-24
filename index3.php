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
					tree:[{name:"read", len:2}]
					,grid:[
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
		<script type="text/javascript" src="static/js/Ext.ux.DirectLogGrid.js"></script>

		<script>
			Ext.onReady( function() {

			   var g = new Ext.ux.DirectMetaGrid({
					region:"center"
					,margins:"4 4 4 0"
					,api:{
		            	read:grid.read
		            	,create:grid.create
		            	,update:grid.update
		            	,destroy:grid.destroy
		          	}
				});

				var t = new Ext.tree.TreePanel({
					region:"west",
					split:true,
					margins:"4 0 4 4",
			        width:250,
			        autoScroll:true,
					visibleRoot:false,
			        root:new Ext.tree.AsyncTreeNode({
			            id:"host"
						,type:"database"
			            ,text:"Host"
						,expanded:true
			        }),
			        loader:new Ext.tree.TreeLoader({
			            directFn:tree.read
						,paramOrder:["node", "type"]
						,listeners:{
							beforeload:function(loader, node) {
								loader.baseParams.type = node.attributes.type;
							}
						}
			        })
					,listeners:{
						click:function(node) {
							if (node.attributes.type === "table") {
								g.store.baseParams.table = node.id;
								g.store.load({params:{start:0, limit:g.limit}});
							}
						}
					}
			    });


				new Ext.Viewport({
					layout:"border"
					,items:[g, t]
					,listeners:{
						afterrender:function() {
							
						}
					}
				})


/*
			    new Ext.ux.DirectLogGrid({
					height:200
					,width:500
				}).render(document.body);
*/

/*
				dgrid.store.on("beforeload", function() {
					console.info("beforeload", arguments);
				});
				dgrid.store.on("load", function() {
					console.info("load", arguments);
				});
				dgrid.store.on("write", function() {
					console.info("write", arguments);
				});
				dgrid.store.on("exception", function() {
					console.info("exception", arguments);
				});
*/
			});

		</script>
	</body>

</html>