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
					table:[
						{name:"read", len:2}
						,{name:"update", len:2}
						,{name:"create", len:2}
						,{name:"destroy", len:2}
					]
				}
			};

			Ext.Direct.addProvider(ExtMyAdmin.API);

			ExtMyAdmin.GridPanel = Ext.extend(Ext.grid.EditorGridPanel, {

			    initComponent:function() {

			        this.columns = [];

			        this.store = new Ext.data.DirectStore({
			            fields:[]
						,autoLoad:true
						,baseParams:{name:"client"}
						,api:{
							read:table.read
							,create:table.create
							,update:table.update
							,destroy:table.destroy
						}
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
			            ,writer:new Ext.data.JsonWriter({
			                encode: true,
			                writeAllFields: false
			            })
			            ,listeners:{
			                scope:this
			            }
			        });

			        this.bbar = new Ext.PagingToolbar({
			            store:this.store
			            ,displayInfo:true
			            ,pageSize:this.pageSize
			            ,prependButtons:true
			            ,items:[{
			            }, "->", "-"]
			        });

			        ExtMyAdmin.GridPanel.superclass.initComponent.apply(this, arguments);
			    }

			});
			
		
			Ext.onReady(function() {
				new ExtMyAdmin.GridPanel({
					
				}).render(document.body);
			});
		</script>
	</body>

</html>