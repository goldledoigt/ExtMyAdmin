Ext.ns("ExtMyAdmin.BrowsingTree");

ExtMyAdmin.BrowsingTree = Ext.extend(Ext.tree.TreePanel, {

    useArrows:true
    ,autoScroll:true
    ,animate:true
    ,rootVisible:false

    ,initComponent:function() {

        this.root = new Ext.tree.AsyncTreeNode({
            id:"host"
			,type:"host"
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
            text:"Add Database"
		    ,iconCls:"icon-add"
		    ,scope:this
		    ,handler:this.addSchema
        }];

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
        if (type === "database") {
            items = [{
                id:"addTable"
                ,text:"Add new table"
                ,iconCls:"icon-add"
            }, {
                id:"removeSchema"
                ,text:"Drop database"
                ,iconCls:"icon-remove"
            }];
        } else if (type === "table") {
            items = [{
                id:"editTable"
                ,text:"Edit Table"
                ,iconCls:"icon-edit"
            }, {
                id:"renameTable"
                ,text:"Rename Table"
                ,iconCls:"icon-rename"
            }, {
                id:"removeTable"
                ,text:"Drop table"
                ,iconCls:"icon-remove"
            }];
        }
        return items;
    }

    ,editTable:function(node) {
        this.fireEvent("tableedit", this, node);
    }

    ,addSchema:function(node) {
        var ajaxCallback = function(result, response) {
            this.getRootNode().appendChild([result]);
        };
        var promptCallback = function(response, value, options) {
            if (response === "ok" && value.length) {
                tree.create(value, "database", 'host', ajaxCallback.createDelegate(this));
            }
        };
        Ext.MessageBox.prompt(
            "Create Database", "Database name:"
            ,promptCallback, this
        );
    }

    ,addTable:function(node) {
        var ajaxCallback = function(result, response) {
            node.appendChild([result]);
        };
        var promptCallback = function(response, value, options) {
            if (response === "ok" && value.length) {
                node.expand();
                tree.create(value, "table", node.text, ajaxCallback.createDelegate(this));
            }
        };
        Ext.MessageBox.prompt(
            'Create New "'+node.text+'" Table', "Table name:"
            ,promptCallback, this
        );
    }

    ,renameTable:function(node) {
        var ajaxCallback = function(result, response) {
            if (result.success == true) {
                node.setId(result.id);
                node.setText(result.text);
            } else {
                Ext.MessageBox.alert('Failed to rename table', result.msg);
            }
        };
        var promptCallback = function(response, value, options) {
            if (response === "ok" && value.length && value !== node.text) {
                tree.update(node.text, node.type, node.parentNode.id, value, ajaxCallback.createDelegate(this));
            }
        };
        Ext.MessageBox.prompt(
            'Rename Table "'+node.text+'"', "New table name:"
            ,promptCallback, this
            ,false, node.text
        );
    }

    ,removeTable:function(node) {
        var ajaxCallback = function(result, response) {
            node.remove();
        };
        var confirmCallback = function(response, value, options) {
            if (response === "yes") {
                tree.destroy(node.id, "table", node.parentNode.id, ajaxCallback.createDelegate(this));
            }
        };
        Ext.MessageBox.confirm(
            "Drop Table"
            ,'Are you sure you want to drop "'+node.id+'" table ?'
            ,confirmCallback, this
        );
    }

    ,removeSchema:function(node) {
        var ajaxCallback = function(result, response) {
            node.remove();
        };
        var confirmCallback = function(response, value, options) {
            if (response === "yes") {
                tree.destroy(node.id, node.type, node.id, ajaxCallback.createDelegate(this));
            }
        };
        Ext.MessageBox.confirm(
            "Drop Database"
            ,'Are you sure you want to drop "'+node.id+'" database ?'
            ,confirmCallback, this
        );
    }

});

Ext.reg("browsingtree", ExtMyAdmin.BrowsingTree);
