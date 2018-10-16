tMobileSDK = window.tMobileSDK || {};

tMobileSDK.ready = function(func){
    td.ready(func)
};

tMobileSDK.confirm = function(opts) {
    td.confirm({
        title: opts.title || '提示',
        message: opts.message || '',
        buttonLabels: opts.buttonLabels || ['确定','取消'],
        onSuccess: opts.onSuccess,
        onFail: opts.onFail
    })
}

tMobileSDK.toast = function(opts) {
    td.toast({
        text: opts.text || '',
        duration: opts.duration || 5,
        delay: opts.delay || 0,
        onSuccess: opts.onSuccess,
        onFail: opts.onFail
    })
}

tMobileSDK.getLocation = function(opts) {
    td.getLocation({
        onSuccess: opts.onSuccess,
        onFail: opts.onFail
    })
}

tMobileSDK.previewImage = function(opts) {

    opts.urls.forEach(function(v, k){
        opts.urls[k] = tMobileSDK.addAuthCode(v);
    });
    opts.current = tMobileSDK.addAuthCode(opts.current);

    td.previewImage({
        urls: opts.urls || [],
        current: opts.current || 0,
		allowDownload: opts.allowDownload,
        onSuccess: opts.onSuccess,
        onFail: opts.onFail
    })
}

tMobileSDK.launchApp = function(opts) {
    td.launchApp({
        app: opts.app || '',
        onSuccess: opts.onSuccess,
        onFail: opts.onFail
    })
}

tMobileSDK.previewFile = function(opts) {
    td.previewFile({
        url: opts.url || '',
        onSuccess: opts.onSuccess,
        onFail: opts.onFail
    })
}

tMobileSDK.call = function(opts) {
    td.call({
        phoneNum: opts.phoneNum || '',
        onSuccess: opts.onSuccess,
        onFail: opts.onFail
    })
}

tMobileSDK.sendMessage = function(opts) {
    td.sendMessage({
        phoneNums: opts.phoneNums || [''],
        content: opts.content || '',
        onSuccess: opts.onSuccess,
        onFail: opts.onFail
    })
}

tMobileSDK.getNetworkType = function(opts) {
    td.getNetworkType({
        onSuccess: opts.onSuccess,
        onFail: opts.onFail
    })
}

tMobileSDK.chooseImage = function(opts) {
    td.chooseImage({
        multiple: opts.multiple,
        max: opts.max,
        fromCamera: opts.fromCamera || false,
        onSuccess: opts.onSuccess,
        onFail: opts.onFail
    })
}

tMobileSDK.uploadImage = function(opts) {
    opts.imgTpl = opts.imgTpl || '<li><div class="ui-grid-trisect-img"><img data-attachid="%id" data-attachname="%name" src="%s" /></div></li>'
    this.chooseImage({
        multiple: opts.multiple,
        max: opts.max,
        onSuccess: function(data) {
            var html = ''
            //alert(JSON.stringify(data))
            data.forEach(function(item) {
                html += opts.imgTpl.replace(/%s/g, item.url)
            })
            opts.imgList.append(html).show();
            opts.upSuccessCb && opts.upSuccessCb(data)
        },
        onFail: function(e) {
            opts.upErrorCb && opts.upErrorCb()
        }
    })
}

tMobileSDK.selectFile = function(opts) {
    td.selectFile({
        multiple: opts.multiple,
        onSuccess: opts.onSuccess,
        onFail: opts.onFail
    })
}

tMobileSDK.selectUser = function(opts) {
    td.selectUser({
		multiple: opts.multiple,
        users: opts.users,
		usableUids: opts.usableUids,
		checkedAll: opts.checkedAll,
        onSuccess: opts.onSuccess,
        onFail: opts.onFail
    })
}

tMobileSDK.selectDept = function(opts) {
    td.selectDept({
		multiple: opts.multiple,
        depts: opts.depts,
        onSuccess: opts.onSuccess,
        onFail: opts.onFail
    })
}

tMobileSDK.setLeft = function(opts) {
    td.setLeft({
        onSuccess: opts.onSuccess,
        onFail: opts.onFail
    })
}

tMobileSDK.setTitle = function(opts) {
    td.setTitle({
        title: opts.title || '标题',
        onSuccess: opts.onSuccess,
        onFail: opts.onFail
    })

}

tMobileSDK.setRight = function(opts) {
    td.setRight({
        show: opts.show || true,
        control: opts.control || false,
        text: opts.text || '',
        onSuccess: opts.onSuccess,
        onFail: opts.onFail
    })
}

tMobileSDK.actionSheet = function(opts) {
    td.actionSheet({
        title: opts.title || '标题',
        cancelButton: opts.cancelButton || '取消',
        otherButtons: opts.otherButtons || ['btn1','btn2'],
        onSuccess: opts.onSuccess,
        onFail: opts.onFail
    })
}

tMobileSDK.buildHeader = function(headerData) {

    var hash = {}
    var title = []

    if(headerData.c && headerData.c.push) {
        headerData.c.forEach(function(item, index) {
            title.push({
                title: item.title,
                active: item.active
            })
            //title.push(item.title)
            hash[index] = item.event
        })
    } else if(headerData.c) {
        title.push(headerData.c.title)
        //hash[0] = headerData.c.event
    }
    headerData.c && this.setTitle({
        title: title,
        onSuccess: function(results) {
            if(results) {
                tMobileSDK.util.eval(hash[results])
            }
        }
    })
    if(headerData.r === null) headerData.r = {}
    headerData.r && this.setRight({
        show: true,
        control: true,
        text: headerData.r.title,
        onSuccess: function(results) {
            if(results === 'clicked') {
                headerData.r.event && tMobileSDK.util.eval(headerData.r.event)
            }
        }
    })
    //headerData.l.event 使用方法，必须是全局的function,且方法名称必须包含setLeft
    if(headerData.l === null) headerData.l = {}
	if(headerData.l.event.indexOf("setLeft") >= 0) {
		headerData.l && headerData.l.event.indexOf("setLeft") !=-1 && 
		this.setLeft({
			onSuccess: function(results) {
				headerData.l.event && tMobileSDK.util.eval(headerData.l.event)
			}
		})   
	}
     
}

tMobileSDK.buildFunc = function(funcData) {
    var hash = {};
    var otherButtons = [];
    funcData.forEach(function(item, index) {
        hash[index] = item.event;
        otherButtons.push(item.title);
    })
    this.actionSheet({
        title: '选项',
        otherButtons: otherButtons,
        onSuccess: function(results) {
            if(results != -1) {
                tMobileSDK.util.eval(hash[results])
            }
        }
    })
}

tMobileSDK.closeWebview = function() {
    td.closeWebview()
}

tMobileSDK.dingBtn = function() {}

tMobileSDK.checkIn = function(opts) {
    td.checkIn({
        locales: opts.locales || [], // 可考勤地址，数组存地址对象，包含经纬度数据
        onSuccess: opts.onSuccess,
        onFail: opts.onFail
    })
}

tMobileSDK.getMacAddress = function(opts) {
    td.getMacAddress({
        onSuccess: opts.onSuccess,
        onFail: opts.onFail
    })
}

tMobileSDK.getLocationByMap = function(opts) {
    td.getLocationByMap({
        onSuccess: opts.onSuccess,
        onFail: opts.onFail
    })
}

/*
 * 增加授权地址
 * @param url 地址
 */

tMobileSDK.addAuthCode = function(url){
    return url;
}
