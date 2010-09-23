Ext.ns("ExtMyAdmin.QueryPanel");

ExtMyAdmin.QueryPanel = Ext.extend(Ext.Panel, {

    initComponent:function() {

        this.schema = null;

        Ext.apply(this, {
            layout:"border"
            ,items:[{
                region:"north"
                ,height:100
                ,split:true
                ,margins:"4 4 0 4"
                ,xtype:"queryeditor"
                ,listeners:{
                    scope:this
                    ,query:this.onQuery
                }
            }, {
                region:"center"
                ,ref:"grid"
                ,bodyStyle:"border-width:1px 0 0 0"
                ,border:false
                ,xtype:"querygrid"
            }]
        });

        ExtMyAdmin.QueryPanel.superclass.initComponent.apply(this, arguments);

    }

    ,onQuery:function(editor, query) {
        if (this.schema) {
            this.grid.load(this.schema, query);
        }
    }

    ,setSchema:function(tree, schema) {
        this.schema = schema;
    }

});

Ext.reg("querypanel", ExtMyAdmin.QueryPanel);
