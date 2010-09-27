Ext.ux.DirectMetaGrid = Ext.extend(Ext.grid.EditorGridPanel, {

	limit:10
	,loadMask:true
	,columnLines:true
	,enableHdMenu:false

    ,initComponent: function() {

        this.columns = [];

    	this.viewConfig = Ext.apply(this.viewConfig || {}, {onDataChange:this.onDataChange});

        this.selModel = new Ext.grid.RowSelectionModel({
            moveEditorOnEnter:false
            ,singleSelect:true
        });

        this.store = new Ext.data.DirectStore({
    		fields:[]
    		,autoSave:true
    		,remoteSort: true
    		,baseParams:{schema:"accelrh", table:"client"}
          	,sortInfo:{field:"", direction:""}
          	,writer:new Ext.data.JsonWriter({
                encode:false
                ,encodeDelete:true
          	})
          	,paramsAsHash:false
            ,paramOrder:this.paramOrder
          	,api:this.api
        });

        Ext.ux.DirectMetaGrid.superclass.initComponent.apply( this, arguments );

    }

	,onDataChange:function() {
		var columns = this.ds.reader.jsonData.columns;
        this.cm.setConfig(columns);
        this.syncFocusEl(0);
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