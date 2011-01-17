Ext.ns("ExtMyAdmin");

ExtMyAdmin.App = Ext.extend(Ext.util.Observable, {
	
	constructor:function() {
		
		ExtMyAdmin.App.superclass.constructor.apply(this, arguments);
		
		Ext.Direct.addProvider(API);
		
		Ext.Direct.on({
			exception:this.onServerException
			,message:this.onServerMessage
		});

		ExtMyAdmin.Message.init();

		Ext.QuickTips.init();
		
		this.viewport = this.getViewport();
	}

	,onServerException:function(e) {
		ExtMyAdmin.Message.error(e.data);
	}

	,onServerMessage:function(e) {
		ExtMyAdmin.Message.info(e.data);
	}

	,getViewport:function() {
		return new Ext.Viewport({
			layout:"border"
			,items:[{
				layout:"card"
				,border:false
				,activeItem:0
				,ref:"cardLayer"
				,region:"center"
				,margins:"4 4 4 0"
				,items:[{
					xtype:"tablegrid"
					,ref:"../tableGrid"
					,limit:28
					,api:{
		            	read:grid.read
		            	,create:grid.create
		            	,update:grid.update
		            	,destroy:grid.destroy
		          	}
				}, {
					xtype:"edittablegrid"
					,ref:"../editTableGrid"
					,limit:28
					,api:{
		            	read:editgrid.read
		            	,create:editgrid.create
		            	,update:editgrid.update
		            	,destroy:editgrid.destroy
		          	}
				}]
				,listeners:{
					tableselect:function() {
						this.getLayout().setActiveItem(0);
					}
					,tableedit:function() {
						this.getLayout().setActiveItem(1);
					}
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
					this.cardLayer.relayEvents(this.browsingTree, ["tableselect"]);
					this.cardLayer.relayEvents(this.browsingTree, ["tableedit"]);
					this.tableGrid.relayEvents(this.browsingTree, ["tableselect"]);
					this.editTableGrid.relayEvents(this.browsingTree, ["tableedit"]);
				}
			}
		});
	}

});


Ext.onReady( function() {

	new ExtMyAdmin.App();

});
