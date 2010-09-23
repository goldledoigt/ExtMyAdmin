Ext.ns("ExtMyAdmin.LogPanel");

ExtMyAdmin.LogPanel = Ext.extend(Ext.grid.GridPanel, {

    bodyStyle:"border-width:1px 0 0 0"

    ,initComponent:function() {

        this.columns = [
            {header:"", dataIndex:"status", fixed:true, width:20, renderer:this.statusRenderer}
            ,{header:"Message", dataIndex:"message", fixed:true, width:200}
            ,{header:"Query", dataIndex:"query"}
        ];

        this.store = new Ext.data.JsonStore({
            fields:["status", "message", "query"]
        });

        this.viewConfig = {
            forceFit:true
            ,getRowClass:function(record, rowIndex, rp, ds) {
                return record.get("status") === true ? "row-success" : "row-error"
            }
        };

        ExtMyAdmin.LogPanel.superclass.initComponent.apply(this, arguments);
    
        this.on({log:this.log});
    
    }

    ,statusRenderer:function(value, metaData) {
        var class = (value === true) ? "icon-success" : "icon-error";
        metaData.css = class;
        return "";
    }

    ,log:function(log) {
        var r = new this.store.recordType({
            query:log.query
            ,status:log.status
            ,message:log.message
        });
        this.stopEditing();
        this.store.insert(0, r);
    }

});

Ext.reg("logpanel", ExtMyAdmin.LogPanel);
