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

	Ext.onReady(function() {

		API = {
			url:"php/controller/index.php"
			,type:"remoting"
			,actions:{
				grid:[
					{name:"read", len:5}
					,{name:"update", len:2}
					,{name:"create", len:2}
					,{name:"destroy", len:2}
				]
			}
		};

		Ext.Direct.addProvider(API);

		Ext.ux.EditorGridPanel = Ext.extend(Ext.grid.EditorGridPanel, {

		  initComponent: function() {

			this.columns = [];

			this.viewConfig = {onDataChange:this.onDataChange};

		    this.store = new Ext.data.DirectStore({
				fields:[]
				,remoteSort: true
				,baseParams:{table:"client"}
		      	,sortInfo:{field:"", direction:""}
		      	,writer:new Ext.data.JsonWriter({returnJson:false})
		      	// PROXY: Ext.data.DirectProxy (paramOrder,paramsAsHash,directFn,api)
		      	,paramsAsHash:false
		      	,paramOrder:["table", "start", "limit", "sort", "dir"]
		      	,api:{
		        	read:grid.read
		        	,create:grid.create
		        	,update:grid.update
		        	,destroy:grid.destroy
		      	}
		    });

		    this.bbar = new Ext.PagingToolbar({
				displayInfo:true
				,pageSize:10
				,store:this.store
		    });

		    Ext.ux.EditorGridPanel.superclass.initComponent.apply( this, arguments );

		    this.on("afterrender", function() {
		         this.store.load({ params: { start: 0, limit:10 } })
		    });
		  }
		
			,onDataChange:function() {
				var columns = this.ds.reader.jsonData.columns;
		        // columns.unshift(this.grid.checkboxSelModel);
		        this.cm.setConfig(columns);
		        this.syncFocusEl(0);
			}
		
		});

		Ext.onReady( function() {
		    new Ext.ux.EditorGridPanel({height:200, width:400}).render(document.body);
		});

	});
		</script>
	</body>

</html>