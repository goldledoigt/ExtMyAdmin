Ext.onReady( function() {

	Ext.Direct.addProvider(API);

	Ext.QuickTips.init();

	new Ext.Viewport({
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

});
