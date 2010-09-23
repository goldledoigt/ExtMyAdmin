Ext.ns("ExtMyAdmin.QueryEditor");

ExtMyAdmin.QueryEditor = Ext.extend(Ext.Panel, {

    initComponent:function() {

        this.editor = new Ext.form.TextArea();

        this.bbar = ["->", {
            text:"Run"
            ,scope:this
            ,handler:this.query
        }, "-", {
            text:"Clear"
        }]

        Ext.apply(this, {
            layout:"fit"
            ,items:this.editor 
        });

        ExtMyAdmin.QueryEditor.superclass.initComponent.apply(this, arguments);

    }

    ,query:function() {
        var value = this.editor.getValue();
        this.fireEvent("query", this, value);
    }

});

Ext.reg("queryeditor", ExtMyAdmin.QueryEditor);
