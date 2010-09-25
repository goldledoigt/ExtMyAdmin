Ext.ns("ExtMyAdmin.TableGrid");

ExtMyAdmin.TableGrid = Ext.extend(Ext.ux.DirectMetaGrid, {

    initComponent:function() {
        
        this.paramOrder = ["schema", "table", "start", "limit", "sort", "dir"];
        
        this.bbar = new Ext.PagingToolbar({
    		displayInfo:true
    		,pageSize:this.limit
    		,store:this.store
    		,prependButtons:true
    		,items:[{
    		    text:"Add"
    		    ,iconCls:"icon-add"
    		    ,scope:this
    		    ,handler:this.addRow
    		}, "-", {
    		    text:"Remove"
    		    ,iconCls:"icon-remove"
    		    ,scope:this
    		    ,handler:this.removeRows
    		}, "->", "-"]
        });
        
        ExtMyAdmin.TableGrid.superclass.initComponent.apply(this, arguments);
        
        this.on({
            scope:this
            ,tableselect:this.onTableSelect
        })
    }

    ,onTableSelect:function(tree, node) {
        this.store.setDefaultSort("");
		this.store.baseParams.table = node.id;
		this.store.baseParams.schema = node.parentNode.id;
		this.store.load({params:{start:0, limit:this.limit}});
    }

});

Ext.reg("tablegrid", ExtMyAdmin.TableGrid);
