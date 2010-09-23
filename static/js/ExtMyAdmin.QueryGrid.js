Ext.ns("ExtMyAdmin.QueryGrid");

ExtMyAdmin.QueryGrid = Ext.extend(ExtMyAdmin.GridPanel, {

    initComponent:function() {
        ExtMyAdmin.QueryGrid.superclass.initComponent.apply(this, arguments);
    }

    ,load:function(schema, query) {
        this.store.baseParams.query = query;
        this.store.baseParams.schema = schema;
        this.store.load({params:{start:0, limit:this.pageSize}});
    }

});

Ext.reg("querygrid", ExtMyAdmin.QueryGrid);
