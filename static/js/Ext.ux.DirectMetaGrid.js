Ext.ux.EditorGridPanel = Ext.extend(Ext.grid.EditorGridPanel, {

	limit:10

    ,initComponent: function() {

        this.columns = [];

    	this.viewConfig = {onDataChange:this.onDataChange};

        this.selModel = new Ext.grid.RowSelectionModel();

        this.store = new Ext.data.DirectStore({
    		fields:[]
    		,autoSave:true
            // ,batchSave:false
            // ,prettyUrls:false
    		,remoteSort: true
    		,baseParams:{table:"client"}
          	,sortInfo:{field:"id", direction:"ASC"}
          	,writer:new Ext.data.JsonWriter({
                // returnJson:false
                encode:false
                ,encodeDelete:true
                // ,writeAllFields:false
          	})
          	// PROXY: Ext.data.DirectProxy (paramOrder,paramsAsHash,directFn,api)
          	,paramsAsHash:false
            ,paramOrder:["table", "start", "limit", "sort", "dir"]
            // ,paramsNames:{start:'start', limit:'limit', sort:'sort', dir:'dir'}
          	,idProperty:"id"
          	,api:{
            	read:grid.read
            	,create:grid.create
            	,update:grid.update
            	,destroy:grid.destroy
          	}
        });

        this.bbar = new Ext.PagingToolbar({
    		displayInfo:true
    		,pageSize:this.limit
    		,store:this.store
    		,prependButtons:true
    		,items:[{
    		    text:"Add"
    		    ,scope:this
    		    ,handler:this.addRow
    		}, "-", {
    		    text:"Remove"
    		    ,scope:this
    		    ,handler:this.removeRows
    		}, "->"]
        });

        Ext.ux.EditorGridPanel.superclass.initComponent.apply( this, arguments );

        this.on("afterrender", function() {
             this.store.load({ params: { start: 0, limit:this.limit } })
        });
/*
        this.on("afteredit", function() {
            console.log("afteredit", arguments);
            this.store.save();
        }, this);
*/
    }

	,onDataChange:function() {
		var columns = this.ds.reader.jsonData.columns;
		Ext.each(columns, this.grid.setColumnEditor, this.grid);
        // columns.unshift(this.grid.checkboxSelModel);
        this.cm.setConfig(columns);
        this.syncFocusEl(0);
	}

    ,setColumnEditor:function(column) {
        // console.log("col", column);
        column.editor = new Ext.form.TextField();
    }

    ,addRow:function() {
        var u = new this.store.recordType({});
        this.stopEditing();
        this.store.insert(0, u);
        this.startEditing(0, 1);
    }

    ,removeRows:function() {
        var records = this.getSelectionModel().getSelections();
        this.store.remove(records);
    }

});