define('FieldManager', ['FieldLoader', "base"], function(require, exports, module) {
    var $ = window.Zepto;
    var Base = require('base');
    var Fields = require('FieldLoader');
    var FieldManager = Base.extend({
        attrs: {
            container: 'body'
        },
        initialize: function(config, fieldsMap) {
            FieldManager.superclass.initialize.call(this, config);
            this.$container = $(this.get('container'))
            //this.store = store
            this.fieldsMap = this.structorData(fieldsMap)
            //this.layout = store.layout
            this.instances = {}
            this.initInstances()
        },
        initInstances: function() {
            var me = this;
            var fieldsMap = me.fieldsMap
            for(var i in fieldsMap){
                try {
                    var fieldCfg = fieldsMap[i]
                    var className = me.getFieldClassName(fieldCfg.type); //text => TextCtrl
                    fieldCfg.fieldManager = me;
                    var FieldClass = Fields[className]; //TextCtrl
                    fieldCfg.container = me.$container;
                    fieldCfg.template = $('#f-' + fieldCfg.type + '-tmpl').html();
                    fieldCfg.pure_id = fieldCfg.field_id.substring(1,fieldCfg.field_id.length-1)
					fieldCfg.tip = '';
					fieldCfg.color = '';
                    me.instances[fieldCfg.field_id] = new FieldClass(fieldCfg);
                } catch (e) {
                    console.log(fieldsMap[i].name+"�����˴��󣡣���name: " + e.name + "message: " + e.message);
                }
            }
        },
        getFieldClassName: function(type) {
            type = type.replace(/-/g, "");
            type = type.firstUpperCase();
            return type + 'Ctrl';
        },
        structorData: function(fieldsMap) {
            var firstPattern = /\[\{([0-9A-Z]|-){36}\}.\{([0-9A-Z]|-){36}\}\]/g;//ƥ��sum([{**}.{**}])
        	var secondPattern = /\{([0-9A-Z]|-){36}\}.\{([0-9A-Z]|-){36}\}/g;//ƥ��{**}.{**}
        	var thirdPattern = /\{([0-9A-Z]|-){36}\}/g;//{**}
            for(var i in fieldsMap){
        		var instance = fieldsMap[i]
        		var _math = instance.math
        		var type 	= instance.type
        		//���������ֶΣ��������б��ֶΣ�
        		if(_math != undefined) {
        				//ƥ���SUM[{**}]
        			_math = _math.replace(firstPattern, function(item){
        				//console.log(item)
        				var fieldArr = item.match(thirdPattern)
        				var list_id  = fieldArr[0]
        				var subfield_id  = fieldArr[1]
        				//console.log(list_id, subfield_id)
        				fieldsMap[list_id].subFields.forEach(function(subfield){
        					if(subfield.field_id == subfield_id){
        						if(subfield.effect != undefined){
        							if(subfield.effect.includes(i) === false){
        								subfield.effect.push(i)
        							}
        						}else{
        							subfield.effect = [i]
        						}
        					}
        				})
        				return ''
        			})
        			//ƥ���{**}
        			_math = _math.replace(thirdPattern, function(item){
        				if(fieldsMap[item].effect != undefined){
        					if(fieldsMap[item].effect.includes(i) === false){
        						fieldsMap[item].effect.push(i)
        					}
        				}else{
        					fieldsMap[item].effect = [i]
        				}
        				return ''
        			})
        			//console.log(_math)
        		}
        		if(type == 'list'){//���������б�ؼ�
        			instance.subFields.forEach(function(field){
        				var _math = field.math
        				if(_math != undefined) {
        					//console.log('start',_math)
        					//ƥ���SUM[{**}]
        					_math = _math.replace(firstPattern, function(item){
        							//console.log(item)
        						var fieldArr = item.match(thirdPattern)
        						var list_id  = fieldArr[0]
        						var subfield_id  = fieldArr[1]
        						//console.log(list_id, subfield_id)
        						fieldsMap[list_id].subFields.forEach(function(subfield){
        							if(subfield.field_id == subfield_id){
        								if(subfield.effect != undefined){
        									if(subfield.effect.includes(i+'.'+field.field_id) === false){
        										subfield.effect.push(i+'.'+field.field_id)
        									}
        								}else{
        									subfield.effect = [i+'.'+field.field_id]
        								}
        							}
        						})
        						return ''
        					})
        					//console.log(_math)
        					//ƥ���{**}.{**}
        					_math = _math.replace(secondPattern, function(item){
        							//console.log(field.name, item)
        						var fieldArr = item.match(thirdPattern)
        						var list_id  = fieldArr[0]
        						var subfield_id  = fieldArr[1]
        						//console.log(list_id, subfield_id)
        						fieldsMap[list_id].subFields.forEach(function(subfield){
        							if(subfield.field_id == subfield_id){
        								if(subfield.effect != undefined){
        									if(subfield.effect.includes(i+'.'+field.field_id) === false){
        										subfield.effect.push(i+'.'+field.field_id)
        									}
        								}else{
        									subfield.effect = [i+'.'+field.field_id]
        								}
        							}
        						})
        						return ''
        					})
        					//console.log(_math)
        					//ƥ���{**}
        					_math = _math.replace(thirdPattern, function(item){
        						if(fieldsMap[item].effect != undefined){
        							if(fieldsMap[item].effect.includes(i+'.'+field.field_id) === false){
        								fieldsMap[item].effect.push(i+'.'+field.field_id)
        							}
        						}else{
        							fieldsMap[item].effect = [i+'.'+field.field_id]
        						}
        						return ''
        					})
        					//console.log('end',_math)
        				}
        			})
        		}
        	}
            return fieldsMap
        },
        
        calc: function(field_id) {
            var self = this;
            console.log(field_id+"�����˼���")
            var firstPattern = /\[\{([0-9A-Z]|-){36}\}.\{([0-9A-Z]|-){36}\}\]/g;//ƥ��sum([{**}.{**}])
    		var secondPattern = /\{([0-9A-Z]|-){36}\}.\{([0-9A-Z]|-){36}\}/g;//ƥ��{**}.{**}
    		var thirdPattern = /\{([0-9A-Z]|-){36}\}/g;//{**}
    		var fieldsMap = this.fieldsMap
    		var translateMath = function(field) {
    			//��������ֶ�
    			var effect = field ? field.effect : []
    			effect.forEach(function(item){
    				//����������б����ֶ�
    				if(item.indexOf('.') != -1){
    					//�ҵ���Ӧ���ֶε�math
    					var id = item.split('.')
    					var list_id = id[0]
    					var subfield_id = id[1]
    					var arr = fieldsMap[list_id].subFields.filter(function(subfield){
    						return subfield.field_id == subfield_id && subfield
    					})
    					var subinstance = arr[0]
    					var _math = subinstance.math
    					var loop = false;
    					if(subinstance.effect != undefined && subinstance.effect instanceof Array && subinstance.effect.length>0){
    						loop = true
    					}
    					//console.log(_math)
    					//��mathִ�������滻
    					//�滻sum([{}])
    					_math = _math.replace(firstPattern, function(item){
							var fieldArr = item.match(thirdPattern)
							var list_id  = fieldArr[0]
							var subfield_id  = fieldArr[1]
							var sum = []
							fieldsMap[list_id].value.forEach(function(row){
                                if(row.data instanceof Array){
                                    row.data.forEach(function(field){
                                        if(field.field_id == subfield_id){
                                            var value = field.value
            								sum.push(value?value:0)
                                            return false;
                                        }
                                    })
                                }else{
                                    var value = row.data[subfield_id].value
    								sum.push(value?value:0)
                                }
							})
							sum = sum.join(',')
							sum = sum.substring(0,sum.length)
							return sum?sum:0
    					})
    					//�滻{}.{}
    					fieldsMap[list_id].value.forEach(function(row){
    						//console.log(_math)
    						var self_math = _math
    						self_math = self_math.replace(secondPattern, function(item){
    							var fieldArr = item.match(thirdPattern)
    							var list_id  = fieldArr[0]
    							var subfield_id  = fieldArr[1]

                                if(row.data instanceof Array){
                                    var value = 0
                                    row.data.forEach(function(field){
                                        if(field.field_id == subfield_id){
                                            value = field.value
                                            return false;
                                        }
                                    })
                                    return value
                                }else{
                                    return value = row.data[subfield_id].value
                                }
    						})
    						//�滻{}
    						var final_math = self_math
    						final_math = final_math.replace(thirdPattern, function(field){
    							return fieldsMap[field].value ? fieldsMap[field].value : 0
    						})
    						final_math = final_math.toLowerCase()
    						var res = math.eval(final_math)
                            if(row.data instanceof Array){
                                row.data.forEach(function(field){
                                    if(field.field_id == subfield_id){
                                        field.value = res
                                    }
                                })
                            }else{
                                row.data[subfield_id].value = res
                            }
                            //�������dom
                            $.isFunction(self.instances[list_id].reRender) && self.instances[list_id].reRender(fieldsMap[list_id].value)

    					})
    					if(loop == true){
    						translateMath(subinstance)
    					}
    				}else{//������������ֶ�
    					var _math = fieldsMap[item].math
    					var loop = false;
    					if(fieldsMap[item].effect != undefined && fieldsMap[item].effect instanceof Array && fieldsMap[item].effect.length>0){
    						loop = true
    					}
    					_math = _math.replace(firstPattern, function(item){
							var fieldArr = item.match(thirdPattern)
							var list_id  = fieldArr[0]
							var subfield_id  = fieldArr[1]
							var sum = []
							fieldsMap[list_id].value.forEach(function(row){
                                if(row.data instanceof Array){
                                    row.data.forEach(function(field){
                                        if(field.field_id == subfield_id){
                                            var value = field.value
            								sum.push(value?value:0)
                                            return false;
                                        }
                                    })
                                }else{
                                    var value = row.data[subfield_id].value
    								sum.push(value?value:0)
                                }
							})
							sum = sum.join(',')
							sum = sum.substring(0,sum.length)
							return sum?sum:0
    					})
    					_math = _math.replace(thirdPattern, function(item){
    						return fieldsMap[item].value ? fieldsMap[item].value : 0
    					})

                        var res = math.eval(_math)
                        //�������ֵ
                        fieldsMap[item].value = res
                        //�������dom
                        $.isFunction(self.instances[item].reRender) && self.instances[item].reRender(res)
    					if(loop == true){
    						translateMath(fieldsMap[item])
    					}
    				}
    			})
    		}
    		if(field_id.indexOf('.') != -1){//���б��ֶ����ֶδ���
				var id = field_id.split('.')
				var list_id = id[0]
				var subfield_id = id[1]
				var list_instance = fieldsMap[list_id]
				list_instance.subFields.forEach(function(field){
					if(field.field_id == subfield_id){
						translateMath(field)
					}
				})
    		}else{//�������ֶδ���
				var field = fieldsMap[field_id]
                //console.log(field)
				translateMath(field)
    		}
        },
        validate: function(){
            var me = this;
            var isOk = true;
            for(i in me.instances){
				if($.isFunction(me.instances[i].validate)){
					var isValidate = me.instances[i].validate()
					if(isValidate === false){
						isOk = false
						//console.log(me.instances[i]._config.name)
						return false;
					}
				}
            }
            return isOk
        },
        exec: function(data, callback){
			var self = this;
            //��ȡ����Ϣ
            var formInfo = store.formInfo
    		var formId = formInfo.formId,
                userid = formInfo.userid,
                time = formInfo.time,
                type = formInfo.type,
				did = formInfo.did,
                run_id = formInfo.run_id,
                flow_id = formInfo.flow_id,
                prcs_key_id = formInfo.prcs_key_id,
                prcs_id = formInfo.prcs_id,
                flow_prcs = formInfo.flow_prcs;
            $.ajax({
                url: '/general/appbuilder/web/appcenter/appdata/exec',
                type: "post",
                data: {
                    formId: formId,
                    userid: userid,
					did: did,
                    time: time,
                    flow_prcs: flow_prcs,
                    type: type,
                    apptime: 'BEFORESAVE',
                    data: data,
                    fields: null
                },
                success: function (response) {
                    if(response.status != 'ok'){
                        alert(response.message)
                        return false
                    }
                    if(response.checkError == true){
						if(response.notValidateSave){
							//��֤δͨ���Ա���
							callback()
						}else{
							//��֤δͨ��������
							alert('������֤δͨ�����������棡')
							var new_fieldsMap = response.data;
							//�������dom
							for(var i in new_fieldsMap){
								new_fieldsMap[i].pure_id = new_fieldsMap[i].field_id.substring(1,new_fieldsMap[i].field_id.length-1);
								new_fieldsMap[i].tip = new_fieldsMap[i].tip ? new_fieldsMap[i].tip : '';
								new_fieldsMap[i].color = new_fieldsMap[i].color ? new_fieldsMap[i].color : '';
								$.isFunction(self.instances[i].reRender) && self.instances[i].reRender(new_fieldsMap[i]);
							}
							if($('.ui-form-tips').length > 0){
								$('.ui-container').scrollTop($('.ui-form-tips').eq(0).offset().top-100)
							}
							setTimeout(function(){
								$('.ui-form-tips').hide()
							},3000)
					
						}
                    }else{
                        //��������
                        callback()
                    }
                },
                error: function (request, strError) {
                    alert('����ǰ������ִ��ʧ��');
                }
            });
        },
        triggerTrig:function(cfg){
			console.log(cfg.name+cfg.index+"��triggerTrig������");
            var self = this;
            var all_data = self.getData();
            var formInfo = store.formInfo;
    		var formId = formInfo.formId,
                userid = formInfo.userid,
                time = formInfo.time,
				did = formInfo.did,
                type = formInfo.type,
                flow_prcs = formInfo.flow_prcs;
            var data_arr = [];
			if(cfg.subFields || cfg.trig_field_id ){//������б�ؼ��ӿؼ�
				var list_linesdata = store.fieldsMap[cfg.field_id].value;
                var new_field_data = {};
                //�����б�ؼ�����һ�е����ֶ�
                $.each(cfg.data,function(i,data){
                    new_field_data[data.field_id] = {
                        field_id: data.field_id,
                        value: data.value
                    };
                })
				if(list_linesdata.length > 0){
					var b_edit = false;//�Ƿ����޸�
                    $.each(list_linesdata,function(i,line){
                    	if(line.index == cfg.index){
                    		b_edit = true;
                            list_linesdata[i]["data"] = new_field_data;
                            return false;
						}
                        // $.each(line.data,function(i,data){
                        //     old_list_data.push({
                        //         field_id: data.field_id,
                        //         value: data.value
                        //     })
                        // })
                        // line_data.push({
                        //     index: line.index,
                        //     flag: line.flag,
                        //     data: old_list_data
                        // })
                    })
					if(!b_edit){//���
                    	list_linesdata.push({
							index: cfg.index,
							flag: cfg.flag,
							data: new_field_data
						});
					}
                }else{
					list_linesdata = [{
                        index: cfg.index,
                        flag: cfg.flag,
                        data: new_field_data
					}];
				}
                data_arr.push({
                    field_id: cfg.field_id,
                    value:list_linesdata
                })
				/*var line_data =[];
				var old_list_data = [];
				if(cfg.flag == "new" && cfg.index != "0"){  //�½�������
					// �������е��б���
					if(list_linesdata.length > 0){
						$.each(list_linesdata,function(i,line){
							
							$.each(line.data,function(i,data){
								old_list_data.push({
									field_id: data.field_id,
									value: data.value             
								})
							})
							line_data.push({
								index: line.index,
								flag: line.flag,
								data: old_list_data    
							})
						})	
					}else{
						var new_field_data = [];
						//�����б�ؼ�����һ�е����ֶ�
						$.each(cfg.data,function(i,data){
							new_field_data.push({
								field_id: data.field_id,
								value: data.value
							})	
						})
						line_data.push({
							index: cfg.index,
							flag: cfg.flag,
							data: new_field_data            
						})
					}
				}else{ //�༭�����д�����
					$.each(list_linesdata,function(i,line){
						if(line.index == cfg.index){
							var edit_list_data = [];
							$.each(cfg.data,function(i,data){
								edit_list_data.push({
									field_id: data.field_id,
									value: data.value
								})	
							})
							line_data.push({
								index: line.index,
								flag: line.flag,
								data: edit_list_data    
							})
						}else{
							$.each(line.data,function(i,data){
								old_list_data.push({
									field_id: data.field_id,
									value: data.value             
								})
							})
							line_data.push({
								index: line.index,
								flag: line.flag,
								data: old_list_data    
							})
						}
					})	
				}
				
				data_arr.push({
					field_id: cfg.field_id,   
					value:line_data    
				})*/
			}else{//���������ؼ�
				for (var i in store.fieldsMap){
					data_arr.push({
						field_id: i,
						value: store.fieldsMap[i].value
					})
				}
			}
			$.each(all_data,function(i,field){
				if(field.field_id == data_arr[0].field_id){
					field.value = data_arr[0].value;
				}
			})
            var fields = [];
			if(cfg.subFields || cfg.trig_field_id){//������б�ؼ��ӿؼ�
				//for(var i=0; i<cfg.data.length;i++){
					fields.push(
						{
							field_id: cfg.trig_field_id,   
							index: cfg.index
						}
					)
				//}

			}else{//���������ؼ�
				fields = [
					 {
						 field_id: cfg.field_id,    //�����ֶ�id
						 index: 1                   //�����б�����
					 }
				]
			}
            $.ajax({
                url: '/general/appbuilder/web/appcenter/appdata/exec',
                type: "post",
                data: {
                    formId: formId,
                    userid: userid,
                    time: time,
					did: did,
                    flow_prcs: flow_prcs,
                    type: type,
                    apptime: 'CHANGE',
                    data: JSON.stringify(all_data),  	//�����ֶε�����
                    fields: JSON.stringify(fields)	    //�ٷ��ֶε�����
                },
                success: function (response) {
                    if(response.status != 'ok'){
                        alert(response.message)
                        return false
                    }
                    if(response.checkError == true){
                        alert('������֤δͨ�����������棡')
                       
                    }
					var new_fieldsMap = response.data;

					//�������dom
					for(var i in new_fieldsMap){
						new_fieldsMap[i].pure_id = new_fieldsMap[i].field_id.substring(1,new_fieldsMap[i].field_id.length-1);
						new_fieldsMap[i].tip = new_fieldsMap[i].tip ? new_fieldsMap[i].tip : '';
						new_fieldsMap[i].color = new_fieldsMap[i].color ? new_fieldsMap[i].color : '';
						$.isFunction(self.instances[i].reRender) && self.instances[i].reRender(new_fieldsMap[i]);
					}
					
					
					if($('.ui-form-tips').length > 0){
						$('.ui-container').scrollTop($('.ui-form-tips').eq(0).offset().top-100)
					}
					setTimeout(function(){
						$('.ui-form-tips').hide()
					},3000)
					
                },
                error: function (request, strError) {
                    alert('����ǰ������ִ��ʧ��');
                }
            });
        },
        getData: function(){
            var me = this;
            var data = [];
            var i_idx = 0;
            for(i in me.instances){
                // data.push({
                //     field_id: me.instances[i]._config.field_id,
                //     value: $.isFunction(me.instances[i].getValue) && me.instances[i].getValue()
                // })
                data[i_idx++] = {
                        field_id: me.instances[i]._config.field_id,
                        value: $.isFunction(me.instances[i].getValue) && me.instances[i].getValue()
                    }
            }
            return data
        },
        save: function(callback) {
            var self = this;
            //��֤δͨ������
            var isOk = this.validate();
			console.log(isOk)
            if(isOk === false){return;}
            //��ȡ������
            var data = JSON.stringify(this.getData());
            //var data = this.getData();


            //��ȡ����Ϣ
            var formInfo = store.formInfo
    		var formId = formInfo.formId,
                userid = formInfo.userid,
                time = formInfo.time,
                type = formInfo.type,
                run_id = formInfo.run_id,
                flow_id = formInfo.flow_id,
				did = formInfo.did,
                prcs_key_id = formInfo.prcs_key_id,
                prcs_id = formInfo.prcs_id,
                flow_prcs = formInfo.flow_prcs,
                triggerBeforeSave = formInfo.triggerBeforeSave,
                signcontent = store.signcontent;
            //���屣��ajax
            var ajaxSave = function(){
                if(store.debug){
                    console.log('save',data)
                }else{
                    $.ajax({
                        url: '/general/appbuilder/web/appcenter/appdata/save',
                        type: "post",
                        dataType: "json",
                        data: {
                            formId: formId,
    						userid: userid,
    						time: time,
    						type: type,
							did: did,
    						run_id: run_id,
    						prcs_key_id: prcs_key_id,
    						prcs_id: prcs_id,
    						flow_prcs: flow_prcs,
    						data: data,
    						signcontent: signcontent
                        },
                        beforeSend: function(){
                            $.ProLoading.show('���ڱ���')
                        },
                        success: function (response) {
                            $.ProLoading.hide()
                            if(response.status != 'ok'){
                                alert(response.message)
                                return false
                            }else{
                                var data = response.data;
                                var formInfo = data.formInfo;
                                store.setFormInfo(formInfo)
								store.setFieldsMap(data.fieldsMap)
								store.renderForm();

								$.ajax({
									type: 'GET',
									url: '/general/appbuilder/web/appcenter/appdata/fetchcomment',
									cache: false,
									// data: {'P': p, 'RUN_ID': q_run_id,'FLOW_ID': q_flow_id,'PRCS_ID': q_prcs_id,'FLOW_PRCS': q_flow_prcs, 'OP_FLAG': q_op_flag},
									data:{'formId':formId, 'run_id': formInfo.run_id,'prcs_id': formInfo.prcs_id,'flow_prcs': formInfo.flow_prcs},
									success: function(response)
									{
										store.signcontent = '';
										$("#signcontent").val("");
										$("#editSignBox").html('');										
										if(response.status != "ok"){
											console.log(response.message)
											return false
										}
										if(response.data.data instanceof Object == false){
											alert('��ȡ��ǩ���ݸ�ʽ����')
											return false
										}else{
											$('#editSignBox').html('')
											var signtmpl = $('#sign-tmpl').html();
											if(response.data.data.length > 0){
												var loop = function(data, $container){
													data.has_prc_id = data.prcs_id != undefined ?  true : false
													data['prcs_id'] = data.prcs_id != undefined ?  data.prcs_id : ''
													$container.append($.parseTpl(signtmpl, data));
													if(data.replys){
														var _$container = $("#sign-list-"+data.feed_id)
														data.replys.forEach(function(item){
															loop(item, _$container)
														})
													}
												}
												var $container = $('#editSignBox')
												response.data.data.forEach(function(item){
													loop(item, $container)
												})
											}
										}								
									}
								});

                                $.tips({
                                    content:'����ɹ�',
                                    stayTime:2000,
                                    type:"success"
                                });
                                /* setTimeout(function(){
                                    window.history.back()
                                },2000); */
                                callback && callback()
                            }
                          
                        },
                        error: function (request, strError) {
                            $.ProLoading.hide()
                            alert(strError);
                        }
                    });
                }
            }
            //����ǰ���triggerBeforeSaveΪtrue,�ȵ��� exec �ӿ� type Ϊ "beforesave"
    		if(triggerBeforeSave){
                self.exec(data, function(){
                    ajaxSave()
                })
            }else{
                ajaxSave()
            }
        },
        turn: function() {
            var turn = function(){
                //��ȡ����Ϣ
                var formInfo = store.formInfo
        		var formId = formInfo.formId,
                    userid = formInfo.userid,
                    time = formInfo.time,
                    type = formInfo.type,
                    run_id = formInfo.run_id,
					did = formInfo.did,
                    run_name = formInfo.run_name,
                    begin_user = formInfo.begin_user,
                    flow_id = formInfo.flow_id,
                    prcs_key_id = formInfo.prcs_key_id,
                    prcs_id = formInfo.prcs_id,
                    flow_prcs = formInfo.flow_prcs;

                $.ajax({
                    type: 'GET',
                    url: './../approve_center/turn.php',
                    cache: false,
                    data: {
                        'RUN_ID': run_id,
                        'FLOW_ID': flow_id,
						'did': did,
                        'PRCS_ID': prcs_id,
                        'FLOW_PRCS': flow_prcs,
                        'PRCS_KEY_ID':prcs_key_id,
                        'FLOW_TYPE': '1',
                        'RUN_NAME': run_name,
                        'BEGIN_USER': begin_user
                    },
                    success: function(data)
                    {
                        if (data == "NOEDITPRIV") {
                            showMessage(noeditpriv);
                            return;
                        } else if (data == "NOSIGNFLOWPRIV") {
                            showMessage(nosignflowpriv);
                            return;
                        } else if (data == "NORIGHTNEXTPRCS") {
                            showMessage(norightnextprcs);
                            return;
                        } else if (data == "NOSETNEWPRCS") {
                            showMessage(norightnextprcs);
                            return;
                        } else if (data.indexOf("noMeetCondition") >= 0) {
                            showMessage(data.substr(16));
                            return;
                        }
                        pages.to('turn');
                        console.log("turn :" + data)
                        $("#scroller_turn").empty().append(data);
                    },
                    error: function(data){
                        showMessage("��ȡʧ��");
                    }
                });
            }
            this.save(turn);
        },
        back: function() {
            var back = function(){
                var formInfo = store.formInfo
        		var formId = formInfo.formId,
                    userid = formInfo.userid,
                    time = formInfo.time,
                    type = formInfo.type,
                    run_id = formInfo.run_id,
                    flow_id = formInfo.flow_id,
                    prcs_key_id = formInfo.prcs_key_id,
                    prcs_id = formInfo.prcs_id,
                    flow_prcs = formInfo.flow_prcs;
                $.ajax({
            		type : "GET",
            		url : "/pda/approve_center/sel_back.php",
            		cache : false,
            		data : {
            			"RUN_ID" : run_id,
            			"FLOW_ID" : flow_id,
            			"PRCS_ID" : prcs_id,
            			"FLOW_PRCS" : flow_prcs,
                        "PRCS_KEY_ID" : prcs_key_id
            		},
            		beforeSend : function() {
            			$.ProLoading.show('�����˻�');
            		},
            		success : function(data) {
            			$.ProLoading.hide();
            			pages.to('back_work');
            			$("#scroller_back_work").empty().append(data);
            		},
            		error : function(data) {
            			$.ProLoading.hide();
            			showMessage("�˻�ʧ��");
            		}
            	});
            }
            this.save(back)
        },
		//��ǩ�������
        stopWorkFlow: function(type) {
			var stop = function(){
				var stopType = type;
				var feedback_content = store.signcontent;
				$.ajax({
					type: 'GET',
					url: '/pda/approve_center/stop.php',
					cache: false,
					data: { 'RUN_ID': store.formInfo.run_id, 'FLOW_ID': store.formInfo.flow_id, 'PRCS_ID': store.formInfo.prcs_id, 'FLOW_PRCS': store.formInfo.flow_prcs, 'PRCS_KEY_ID': store.formInfo.prcs_key_id, 'top_flag': store.formInfo.top_flag, 'feedback_content': feedback_content, 'stop_type': stopType },
					beforeSend: function() {
						$.ProLoading.show();
					},
					success: function(data) {
						$.ProLoading.hide();
						if (data == "NOSUBEDITPRIV") {
							showMessage(nosubeditpriv);
							return;
						} else if (data == "WORKDONECOMPLETE") {
							showMessage(workdonecomplete);
							setTimeout("back2list('" + workdonecomplete + "')", 1000);
							return;
						} else if (data == "SIGNISNOTEMPTY") {
							showMessage(signisnotempty);
							return;
						} else if (data == "TURNNEXT") {
							turnWorkFlow('stop');
							return;
						}
					},
					error: function(data) {
						$.ProLoading.hide();
						showMessage("��ȡʧ��");
					}
				});
			}
			this.save(stop);
		}
    });
    exports.FieldManager = window.FieldManager = FieldManager;
});
