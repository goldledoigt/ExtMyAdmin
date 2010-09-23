// Ext.ns("ExtMyAdmin");

ExtMyAdmin = (function() {
    
    var viewport = null;

    function init() {
        render();
    }

    function render() {
        Ext.QuickTips.init();
        viewport = new Ext.Viewport({
            layout:"border"
            ,items:[{
                region:"west"
                ,width:200
                ,split:true
                ,title:"Localhost"
                ,ref:"browsingTree"
                ,xtype:"browsingtree"
            }, {
                region:"center"
                ,layout:"border"
                // ,border:false
                ,items:[{
                    region:"center"
                    ,border:false
                    ,bodyStyle:"border-width:0 0 1px 0"
                    ,xtype:"tabpanel"
                    ,activeTab:0
                    ,items:[{
                        title:"View"
                        ,ref:"../../tableGrid"
                        ,xtype:"tablegrid"
                    }, {
                        title:"Query"
                        ,ref:"../../queryPanel"
                        ,xtype:"querypanel"
                        ,border:false
                    }]
                }, {
                    region:"south"
                    ,split:true
                    ,height:100
                    ,ref:"../logPanel"
                    ,xtype:"logpanel"
                }]
            }]
            ,listeners:{afterrender:onRender}
        });
    }

    function onRender() {
        this.logPanel.relayEvents(this.tableGrid, ["log"]);
        this.logPanel.relayEvents(this.queryPanel.grid, ["log"]);
        this.browsingTree.on({
            dblclick:{scope:this.tableGrid, fn:this.tableGrid.load}
            ,schemachange:{scope:this.queryPanel, fn:this.queryPanel.setSchema}
        });
    }

    return {
        init:init
        ,viewport:viewport
    };

})();

Ext.onReady(ExtMyAdmin.init);
/*
    new Ext.grid.GridPanel({
        width:200
        ,height:200
        ,store:new Ext.data.JsonStore({
            root:"data"
            ,autoLoad:true
            ,fields:["Database"]
            ,baseParams:{scope:'database'}
            ,proxy:new Ext.data.HttpProxy({
                api:{
                    read:"controller/read.php"
                } 
            })
        })
        ,columns:[
            {header:"label", dataIndex:"Database"}
        ]
    }).render(document.body);

    var tree = new Ext.tree.TreePanel({
        width:200,
        height:200,
        useArrows: true,
        autoScroll: true,
        animate: true,
        // containerScroll: true,
        // border: false,
        // auto create TreeLoader
        loader:new Ext.tree.TreeLoader({
            dataUrl:"controller/read.php",
            nodeParameter:"scope"
        }),
        root:{
            nodeType:'async',
            text: 'Ext JS',
            id:'database'
        }
    }).render(document.body);
*/
