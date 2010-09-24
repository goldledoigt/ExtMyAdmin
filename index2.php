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

			Ext.ns("ExtMyAdmin.GridPanel");
			
			ExtMyAdmin.API = {
				url:"php/controller/index.php"
				,type:"remoting"
				,actions:{
					grid:[
						{name:"read", len:2}
						,{name:"update", len:2}
						,{name:"create", len:2}
						,{name:"destroy", len:2}
					]
				}
			};

			Ext.Direct.addProvider(ExtMyAdmin.API);

			ExtMyAdmin.GridPanel = Ext.extend(Ext.grid.GridPanel, {

			    initComponent:function() {

			        this.columns = [{header:"id", dataIndex:"id"}];

					this.viewConfig = {onDataChange:this.onDataChange};

			        this.store = new Ext.data.DirectStore({
			            fields:["id"]
						,root:"result.rows"
						// ,idProperty:"id"
						,autoLoad:true
						// ,baseParams:{table:"client"}
						,directFn:grid.read
						/*
						,api:{
							read:grid.read
							,create:grid.create
							,update:grid.update
							,destroy:grid.destroy
						}
						*/
						// ,paramsAsHash:false
						/*
						,paramOrder: ['sort','dir','start','limit', 'table']
						,paramNames:{
							start : 'start'
							,limit : 'limit'
							,sort : 'sort'
							,dir : 'dir'
							,table:"table"
						}
						*/
						/*
			            ,proxy:new Ext.data.HttpProxy({
			                api:{
			                    read:"controller/table/read.php"
			                    ,create:"controller/table/create.php"
			                    ,update:"controller/table/update.php"
			                    ,destroy:"controller/table/destroy.php"
			                }
			            })
						*/
						/*
			            ,writer:new Ext.data.JsonWriter({
			                encode: true,
			                writeAllFields: false
			            })
						*/
			            ,listeners:{
			                scope:this
							,beforeload:console.log
							,loadexception:console.log
							,load:console.log
			                // ,exception:this.onException
			                // ,load:this.onLoad
			                ,write:this.onWrite
			            }
			        });
/*
			        this.bbar = new Ext.PagingToolbar({
			            store:this.store
			            ,displayInfo:true
			            ,pageSize:this.pageSize
			            ,prependButtons:true
			            ,items:[{
			            }, "->", "-"]
			        });
*/
			        ExtMyAdmin.GridPanel.superclass.initComponent.apply(this, arguments);
			    }
			
				,onDataChange:function() { // scope is on grid.getView()
			        var columns = this.ds.reader.jsonData.columns;
					console.log("onDataChange", columns);
			        columns.unshift(this.grid.checkboxSelModel);
			        this.cm.setConfig(columns);
			        this.syncFocusEl(0);
			    }

				,onWrite:function(store, action, result, resp, record) {
					console.log("write", arguments);
			    }

			    ,onException:function(proxy, type, action, options, resp, arg) {
					console.log("exception", arguments);
			    }

			    ,onLoad:function(store, records, options) {
					console.log("load", arguments);
			    }

			});

			Ext.Direct.on( 'exception', function(e) { console.log("xxx"); } );
			
			Ext.data.DataProxy.on('beforload', function(proxy, action) {
			    console.log('beforeload: ', action);
			});
		
			Ext.onReady(function() {
				new ExtMyAdmin.GridPanel({
					height:200
					,width:500
				}).render(document.body);
			});
		</script>
	</body>

</html>