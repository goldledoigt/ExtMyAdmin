Ext.ns('ExtMyAdmin');

ExtMyAdmin.Message = (function(config) {

	var el = false,
	
	floating = false,

	taskStart = false,

	lastItem = false,

	initialConfig = false,

	items = new Ext.util.MixedCollection(),

	task = {
		interval:3000
		,run:showNext
	},

	tpl = new Ext.Template(
		'<div>{text}</div>'
	,{compiled:true}),

	config = {
		baseCls:"ema-message"
	};

	function init(cfg) {
		if (Ext.isReady) {
			if (cfg) {
				if (cfg.id) el = Ext.get(cfg.id);
				Ext.apply(config, cfg);
			}
			if (!el) el = createEl();
		} else {
			Ext.onReady(init.createDelegate(this, [cfg]));
		}
	}

	function createEl() {
		floating = true;
		var el = Ext.DomHelper.append(Ext.getBody(), {
			cls:config.baseCls
		}, true);
		return el;
	}

	function showNext() {
		var needReset = false;
		if (lastItem) {
			remove(lastItem);
			needReset = true;
		}
		var item = items.first();
		if (item) {
			lastItem = item;
			show(item);
		} else {
			stopTask();
			lastItem = false;
			if (el) el.fadeOut();
		}
	}

	function startTask() {
		if (!taskStart) {
			Ext.TaskMgr.start(task);
			taskStart = true;
		}
	}

	function stopTask() {
		Ext.TaskMgr.stop(task);
		taskStart = false;
	}

	function add(data) {
	  	items.add(data);
		startTask();
	}

	function remove(item) {
		items.remove(item);
	}

	function show(item) {
		if (el) {
			el.fadeOut({
				callback:function() {
					if (lastItem && lastItem.type !== item.type)
						el.removeClass(config.baseCls + "-" + lastItem.type);
					el.update(tpl.apply(item));
					el.addClass(config.baseCls + "-" + item.type);
					if (floating) {
						el.anchorTo(Ext.getBody(), "tr", [-1*el.getWidth()-5, 5], false, true, function() {
							el.fadeIn();
						});
					}
				}
			});
		}
	}

	return {
		init:init
		,initialConfig:config
		,info:function(message) {
			add({
				type:"info"
				,text:message
			});
		}
		,error:function(message) {
			add({
				type:"error"
				,text:message
			});
		}
	};

})();
