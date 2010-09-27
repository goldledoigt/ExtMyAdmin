Ext.ns("ExtMyAdmin.EditTableGrid");

ExtMyAdmin.EditTableGrid = Ext.extend(Ext.ux.DirectMetaGrid, {

    columnLines:false

    ,initComponent:function() {

        this.viewConfig = {forceFit:true};

        this.paramOrder = ["schema", "table", "start", "limit", "sort", "dir"];

        this.bbar = new Ext.PagingToolbar({
    		displayInfo:true
            ,hidden:true
    		,pageSize:this.limit
    		,prependButtons:true
    		,items:[{
    		    text:"Add Row"
    		    ,iconCls:"icon-add"
    		    ,scope:this
    		    ,handler:this.addRow
    		}, "-", {
    		    text:"Remove Row"
    		    ,iconCls:"icon-remove"
    		    ,scope:this
    		    ,handler:this.removeRows
    		}, "->", "-"]
        });
        
        ExtMyAdmin.EditTableGrid.superclass.initComponent.apply(this, arguments);

        this.getBottomToolbar().bindStore(this.store);

        this.on({
            scope:this
            ,tableedit:this.onTableSelect
        })
    }

    ,onTableSelect:function(tree, node) {
        var bbar = this.getBottomToolbar();
        if (bbar.hidden) bbar.show();
        this.store.setDefaultSort("");
		this.store.baseParams.table = node.id;
		this.store.baseParams.schema = node.parentNode.id;
		this.store.load({params:{start:0, limit:this.limit}});
    }

});

Ext.reg("edittablegrid", ExtMyAdmin.EditTableGrid);
