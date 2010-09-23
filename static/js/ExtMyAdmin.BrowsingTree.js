Ext.ns("ExtMyAdmin.BrowsingTree");

ExtMyAdmin.BrowsingTree = Ext.extend(Ext.tree.TreePanel, {
    
    useArrows:true
    ,autoScroll:true
    ,animate:true
    ,rootVisible:false

    ,initComponent:function() {

        this.loader = new Ext.tree.TreeLoader({
            dataUrl:"controller/index.php"
            ,nodeParameter:"name"
            ,baseParams:{ui:"tree"}
            ,listeners:{beforeload:this.onBeforeLoad}
        });

        this.root = {
            expanded: true,
            nodeType:"async",
            text:"Localhost",
            id:"localhost",
            type:"database"
        };

        this.contextMenu = new Ext.menu.Menu({
            items:[{
                id:"removeDatabase"
                ,text:"Remove Database"
            }, {
                id:"removeTable"
                ,text:"Remove Table"
            }, {
                id:"truncateTable"
                ,text:"Truncate Table"
            }, {
                id:"renameTable"
                ,text:"Rename Table"
            }, {
                id:"duplicateTable"
                ,text:"Duplicate Table"
            }]
            ,listeners:{
                scope:this
                ,itemclick:this.onMenuItemClick
            }
        });

        ExtMyAdmin.BrowsingTree.superclass.initComponent.apply(this, arguments);
        
        this.on({
            click:this.onNodeClick
            ,contextmenu:this.onContextMenu
        });
    }

    ,onBeforeLoad:function(loader, node) {
        loader.baseParams.type = node.attributes.type;
    }

    ,onNodeClick:function(node) {
        var schema = node.getPath().split("/")[2];
        if (schema !== this.schema) {
            this.schema = schema;
            this.fireEvent("schemachange", this, schema);
            console.log("SCHEMA:", this.schema);
        }
        
    }

    ,onContextMenu:function(node, e) {
        this.contextMenu.node = node;
        this.contextMenu.showAt(e.getXY());
    }

    ,onMenuItemClick:function(item) {
        var me = this;
        me[item.id](this.contextMenu.node);
    }


    ,renameTable:function(node) {
        if (node.attributes.type !== "table") return false;
        var promptCallback = function(response, value, options) {
            if (response === "ok" && value.length && value !== node.text) {
                Ext.Ajax.request({
                    scope:this
                    ,url:"controller/index.php"
                    ,callback:ajaxCallback
                    ,params:{
                        cmd:"rename"
                        ,type:"schema"
                        ,params:'{"oldname":"'+node.text+'", "newname":"'+value+'"}'
                    }
                });
            }
        };
        Ext.MessageBox.prompt(
            "Rename Table", "Table name:"
            ,promptCallback, this
            ,false, node.text
        );
    }

    ,addDatabase:function() {
        var ajaxCallback = function(options, success, response) {
            if (success) {
                var node = Ext.decode(response.responseText)
                this.getRootNode().appendChild([node]);
            }
        };
        var promptCallback = function(response, value, options) {
            if (response === "ok" && value.length) {
                Ext.Ajax.request({
                    scope:this
                    ,url:"controller/index.php"
                    ,callback:ajaxCallback
                    ,params:{cmd:"create", type:"database", params:'{"name":"'+value+'"}'}
                });
            }
        };
        Ext.MessageBox.prompt(
            "Create Database", "Database name:"
            ,promptCallback, this
        );
    }

});

Ext.reg("browsingtree", ExtMyAdmin.BrowsingTree);
