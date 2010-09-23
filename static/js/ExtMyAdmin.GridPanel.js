Ext.ns("ExtMyAdmin.GridPanel");

ExtMyAdmin.GridPanel = Ext.extend(Ext.grid.EditorGridPanel, {

    pageSize:30

    ,columnLines:true

    ,initComponent:function() {

        this.columns = [];

        this.viewConfig = {onDataChange:this.onDataChange};

        this.sm = this.checkboxSelModel = new Ext.grid.CheckboxSelectionModel();

        this.store = new Ext.data.JsonStore({
            fields:[]
            ,proxy:new Ext.data.HttpProxy({
                api:{
                    read:"controller/table/read.php"
                    ,create:"controller/table/create.php"
                    ,update:"controller/table/update.php"
                    ,destroy:"controller/table/destroy.php"
                }
            })
            ,writer:new Ext.data.JsonWriter({
                encode: true,
                writeAllFields: false
            })
            ,listeners:{
                scope:this
                ,exception:this.onException
                ,load:this.onLoad
                ,write:this.onWrite
            }
        });

        this.bbar = new Ext.PagingToolbar({
            store:this.store
            ,displayInfo:true
            ,pageSize:this.pageSize
            ,prependButtons:true
            ,items:[{
                text:"Add"
                ,scope:this
                ,handler:this.addRow
            }, "-", {
                text:"Remove"
                ,scope:this
                ,handler:this.deleteRows
            }, "->", "-"]
        });

        ExtMyAdmin.GridPanel.superclass.initComponent.apply(this, arguments);
    }

    ,addRow:function() {
        var u = new this.store.recordType({});
        this.stopEditing();
        this.store.insert(0, u);
        this.startEditing(0, 1);
    }

    ,deleteRows:function() {
        var records = this.getSelectionModel().getSelections();
        this.store.remove(records);
    }

    ,onDataChange:function() { // scope is on grid.getView()
        var columns = this.ds.reader.jsonData.columns;
        columns.unshift(this.grid.checkboxSelModel);
        this.cm.setConfig(columns);
        this.syncFocusEl(0);
    }

    ,onWrite:function(store, action, result, resp, record) {
        var response = resp.message;
        this.fireEvent("log", Ext.apply(response, {status:resp.success}));
    }

    ,onException:function(proxy, type, action, options, resp, arg) {
        var response = resp.message;
        if (response) {
            this.fireEvent("log", Ext.apply(response, {status:resp.success}));
        }
    }

    ,onLoad:function(store, records, options) {
        var response = store.reader.jsonData;
        this.fireEvent("log", Ext.apply(response.log, {status:response.success}));
    }

});

Ext.reg("gridpanel", ExtMyAdmin.GridPanel);
