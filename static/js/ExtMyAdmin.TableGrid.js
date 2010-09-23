Ext.ns("ExtMyAdmin.TableGrid");

ExtMyAdmin.TableGrid = Ext.extend(ExtMyAdmin.GridPanel, {

    initComponent:function() {
        ExtMyAdmin.TableGrid.superclass.initComponent.apply(this, arguments);
    }

    ,load:function(node) {
        if (node.attributes.type === "table") {
            this.store.baseParams.schema = node.parentNode.id;
            this.store.baseParams.table = node.id;
            this.store.load({params:{start:0, limit:this.pageSize}});
        }
    }

});

Ext.reg("tablegrid", ExtMyAdmin.TableGrid);
