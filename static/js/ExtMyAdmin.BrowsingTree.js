Ext.ns("ExtMyAdmin.BrowsingTree");

ExtMyAdmin.BrowsingTree = Ext.extend(Ext.tree.TreePanel, {
    
    useArrows:true
    ,autoScroll:true
    ,animate:true
    ,rootVisible:false

    ,initComponent:function() {

        this.root = new Ext.tree.AsyncTreeNode({
            id:"host"
			,type:"database"
            ,text:"Host"
			,expanded:true
        });

        this.loader = new Ext.tree.TreeLoader({
            directFn:tree.read
			,paramOrder:["node", "type", "schema"]
			,listeners:{
				beforeload:function(loader, node) {
					loader.baseParams.type = node.attributes.type;
					if (node.attributes.type === "table") {
						loader.baseParams.schema = node.parentNode.id;
					} else {
						loader.baseParams.schema = "";
					}
				}
			}
        });

        this.bbar = [{
            text:"Add"
		    ,iconCls:"icon-add"
		    ,scope:this
		    ,handler:this.addSchema
        }];

        // this.contextMenu = new Ext.menu.Menu({
        //     items:[{
        //         id:"removeDatabase"
        //         ,text:"Remove Database"
        //     }, {
        //         id:"removeTable"
        //         ,text:"Remove Table"
        //     }, {
        //         id:"truncateTable"
        //         ,text:"Truncate Table"
        //     }, {
        //         id:"renameTable"
        //         ,text:"Rename Table"
        //     }, {
        //         id:"duplicateTable"
        //         ,text:"Duplicate Table"
        //     }]
            // ,listeners:{
            //     scope:this
            //     ,itemclick:this.onMenuItemClick
            // }
        // });

        ExtMyAdmin.BrowsingTree.superclass.initComponent.apply(this, arguments);
        
        new Ext.tree.TreeSorter(this, {dir:"ASC"});
        
        this.on({
            click:this.onNodeClick
            ,contextmenu:this.onContextMenu
        });
    }

    ,onNodeClick:function(node) {
        if (node.attributes.type === "table") {
			this.fireEvent("tableselect", this, node);
		}
    }

    ,onContextMenu:function(node, e) {
        if (this.contextMenu) this.contextMenu.destroy();
        this.contextMenu = this.getContextMenu(node);
        this.contextMenu.showAt(e.getXY());
    }

    ,onMenuItemClick:function(item) {
        var me = this;
        me[item.id](this.contextMenu.node);
    }

    ,getContextMenu:function(node) {
        return new Ext.menu.Menu({
            items:this.getContextMenuItems(node)
            ,node:node
            ,listeners:{
                scope:this
                ,itemclick:this.onMenuItemClick
            }
        });
    }

    ,getContextMenuItems:function(node) {
        var items, type = node.attributes.type;
        if (type === "schema") {
            items = [{
                id:"removeSchema"
                ,text:"Drop database"
                ,iconCls:"icon-remove"
            }];
        } else if (type === "table") {
            items = [{
                id:"renameTable"
                ,text:"Rename Table"
                ,iconCls:"icon-rename"
            }];
        }
        return items;
    }

    ,addSchema:function() {
        var ajaxCallback = function(result, response) {
            this.getRootNode().appendChild([result]);
        };
        var promptCallback = function(response, value, options) {
            if (response === "ok" && value.length) {
                tree.create(value, "schema", ajaxCallback.createDelegate(this));
            }
        };
        Ext.MessageBox.prompt(
            "Create Database", "Database name:"
            ,promptCallback, this
        );
    }

    ,renameTable:function(node) {
        var ajaxCallback = function(result, response) {
            node.setId(result);
            node.setText(result);
        };
        var promptCallback = function(response, value, options) {
            if (response === "ok" && value.length && value !== node.text) {
                tree.update(node.text, "table", node.parentNode.id, value, ajaxCallback.createDelegate(this));
            }
        };
        Ext.MessageBox.prompt(
            'Rename Table "'+node.text+'"', "New table name:"
            ,promptCallback, this
            ,false, node.text
        );
    }

    ,removeSchema:function() {
        var node = this.contextMenu.node;
        var ajaxCallback = function(result, response) {
            node.remove();
        };
        var confirmCallback = function(response, value, options) {
            if (response === "yes") {
                tree.destroy(node.id, "schema", ajaxCallback.createDelegate(this));
            }
        };
        Ext.MessageBox.confirm(
            "Drop Database"
            ,'Are you sure you want to drop"'+node.id+'" database ?'
            ,confirmCallback, this
        );
    }

});

Ext.reg("browsingtree", ExtMyAdmin.BrowsingTree);
