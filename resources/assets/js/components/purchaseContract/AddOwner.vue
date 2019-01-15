<template>
    <div>
        <el-form :label-position="labelPosition" ref="ownerForm" :rules="editOwnerRules" label-width="100px" :model="owner">
        <el-col :span="24">
            <el-form-item label="承租人">
                <el-radio-group v-model="owner.chengzufang">
                    <el-radio label="华溯商贸"></el-radio>
                    <el-radio label="幼狮科技"></el-radio>
                    <el-radio label="航远房地产"></el-radio>
                    <el-radio label="航远投资管理"></el-radio>
                    <el-radio label="彭昆"></el-radio>
                    <el-radio label="彭亮"></el-radio>
                    <el-radio  label="无"></el-radio>
                </el-radio-group>
            </el-form-item>
            <el-form-item label="合同类型" prop="farenzhengjian" >
                <el-radio-group v-model="owner.farenzhengjian" @change="leixingChange" >
                    <el-radio label="l">两方</el-radio>
                    <el-radio label="s">三方</el-radio>
                </el-radio-group>
            </el-form-item>
            <el-row v-if="owner.farenzhengjian=='l'">
                <el-col :span="8">
                    <el-form-item label="原合同编号" prop="oldBianhao" >
                        <el-input v-model="owner.oldBianhao" :disabled="lydisabled" @blur="checkbianhao"></el-input>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row v-if="owner.farenzhengjian=='s'">
                <el-col  :span="8">
                    <el-form-item label="居间方">
                        <el-select
                                v-model="owner.jujianfangid"
                                filterable
                                remote
                                @change="changeOnSelect1"
                                placeholder="渠道公司名称"
                                :remote-method="remoteMethod1"
                                :loading="bkNameloading"
                            >
                            <el-option
                                    v-for="item in owner.options1"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col  :span="8">
                    <el-form-item label="渠道人员">
                        <el-select
                            v-model="owner.qudaorenid"
                            filterable
                            remote
                            @change="changeOnSelect2"
                            placeholder="渠道人员"
                            :remote-method="remoteMethod2"
                            :loading="bkryNameloading">
                            <el-option
                                v-for="item in owner.options2"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-form-item label="账户类型" prop="duizhangType" required>
                <el-radio-group v-model="owner.duizhangType" >
                    <el-radio label="1">公户</el-radio>
                    <el-radio label="2">私户</el-radio>
                </el-radio-group>
            </el-form-item>
            <el-row>
                <el-col :span="8">
                    <el-form-item label="户名" prop="shoukuanren" required>
                        <el-input v-model="owner.shoukuanren" :disabled="lydisabled"></el-input>
                    </el-form-item>
                </el-col>
								<el-col :span="10">
										<el-form-item label="银行" required prop="shoufangzonghang">
												<el-select
																v-model="owner.shoufangzonghangid"
																filterable
																default-first-option
																remote
																@change="change1"
																placeholder=""
																:remote-method="bankCompany"
																:loading="bankCNameloading"
																:disabled="lydisabled"
												>
														<el-option
																		v-for="item in owner.options3"
																		:key="item.value"
																		:label="item.label"
																		:value="item.value">
														</el-option>
												</el-select>
										</el-form-item>
								</el-col>
            </el-row>
            <el-row>
                <el-col :span="8">
                    <el-form-item label="开户行" prop="kaihuhang" required>
                        <el-input v-model="owner.kaihuhang" :disabled="lydisabled"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="10">
                    <el-form-item label="账号" prop="zhanghao" required>
                        <input v-model="owner.zhanghao" :disabled="lydisabled"
                               style="-webkit-appearance: none;
                                    -moz-appearance: none;
                                    appearance: none;
                                    background-color: #fff;
                                    background-image: none;
                                    border-radius: 4px;
                                    border: 1px solid #bfcbd9;
                                    box-sizing: border-box;
                                    color: #1f2d3d;
                                    font-size: inherit;
                                    height: 36px;
                                    line-height: 1;
                                    outline: 0;
                                    padding: 3px 10px;
                                    transition: border-color .2s cubic-bezier(.645,.045,.355,1);
                                    width: 100%;" onkeyup="this.value=this.value.replace(/\D/g,'').replace(/....(?!$)/g,'$& ')"
                               >
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row>
								<el-col :span="8">
										<el-form-item label="开户省" required prop="shengfen">
												<el-select
																v-model="owner.shengfenid"
																filterable
																default-first-option
																remote
																@change="change2"
																placeholder=""
																:remote-method="bankCompany2"
																:loading="bankCName2loading"
																:disabled="lydisabled"
												>
														<el-option
																		v-for="item in owner.options4"
																		:key="item.value"
																		:label="item.label"
																		:value="item.value">
														</el-option>
												</el-select>
										</el-form-item>
								</el-col>
                <el-col :span="10">
                    <el-form-item label="市" prop="shi" required>
                        <el-input v-model="owner.shi" :disabled="lydisabled" ></el-input>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-form-item label="业主类型" prop="yezhuleixing" >
                <el-radio-group v-model="owner.yezhuleixing" @change="yezhuleixingChange">
                    <el-radio :label="1">个人</el-radio>
                    <el-radio :label="2">公司</el-radio>
                </el-radio-group>
            </el-form-item>

            <div v-if="owner.yezhuleixing==1">
                <div v-for="(item, index) in owner.chanquanrenList"
                     :key="index"
                >
                <el-row>
                    <el-col :span="8">
                        <el-form-item label="产权人" :prop="'chanquanrenList.' + index + '.name'" >
                                      <!--:rules="[-->
                                      <!--{ required: true, message: '不能为空' }-->
                                        <!--]"-->

                            <el-input v-model="owner.chanquanrenList[index].name"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="10">
                        <el-form-item label="身份证号">
                            <el-input v-model="owner.chanquanrenList[index].zhengjian"></el-input>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row>
                    <el-col :span="8">
                        <el-form-item label="联系方式">
                            <el-input v-model="owner.chanquanrenList[index].tel"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="性别">
                            <el-radio-group v-model="owner.chanquanrenList[index].sex">
                                <el-radio :label="1">男</el-radio>
                                <el-radio :label="2">女</el-radio>
                            </el-radio-group>
                        </el-form-item>
                    </el-col>
                    <el-col :span="2">
                        <el-button v-if="index>0" v-show="editVisible" style="margin-left:6px;" @click.prevent="removeRentItem(item)">删除</el-button>
                        <el-button v-if="index==0" v-show="editVisible"  @click="addRentItem" style="margin-left:-30px">新增产权人</el-button>
                    </el-col>
                </el-row>
                </div>
                <el-row>
                    <el-col :span="8">
                        <el-form-item label="代理人" >
                            <el-input v-model="owner.dailirenName"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="10">
                        <el-form-item label="身份证号" >
                            <el-input v-model="owner.dailirenId"></el-input>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row>
                    <el-col :span="8">
                        <el-form-item label="联系方式" >
                            <el-input v-model="owner.dailirenTel"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="性别">
                            <el-radio-group v-model="owner.dailirenSex">
                                <el-radio :label="1">男</el-radio>
                                <el-radio :label="2">女</el-radio>
                            </el-radio-group>
                        </el-form-item>
                    </el-col>
                </el-row>
            </div>
            <div v-if="owner.yezhuleixing==2">
                <div v-for="(item, index) in owner.chanquanrenList"
                     :key="index"
                >
                    <el-row>
                     <el-col :span="18">
                    <el-form-item label="公司名称" :prop="'chanquanrenList.' + index + '.name'" >
                                  <!--:rules="[-->
                         <!--{ required: true, message: '不能为空' }-->
                    <!--]"-->
                        <el-input v-model="owner.chanquanrenList[index].name"></el-input>
                    </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="8">
                            <el-form-item label="法人" >
                                <el-input v-model="owner.chanquanrenList[index].faren"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="10">
                            <el-form-item label="身份证号" >
                                <el-input v-model="owner.chanquanrenList[index].zhengjian"></el-input>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="8">
                            <el-form-item label="联系方式" >
                                <el-input v-model="owner.chanquanrenList[index].tel"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="性别">
                                <el-radio-group v-model="owner.chanquanrenList[index].sex">
                                    <el-radio :label="1">男</el-radio>
                                    <el-radio :label="2">女</el-radio>
                                </el-radio-group>
                            </el-form-item>
                        </el-col>
                    </el-row>
                </div>
                <el-row>
                        <el-col :span="8">
                            <el-form-item label="签约人" >
                                <el-input v-model="owner.qianyuerenName"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="10">
                            <el-form-item label="身份证号" >
                                <el-input v-model="owner.qianyuerenId"></el-input>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="8">
                            <el-form-item label="联系方式" >
                                <el-input v-model="owner.qianyuerenTel"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="性别">
                                <el-radio-group v-model="owner.qianyuerenSex">
                                    <el-radio :label="1">男</el-radio>
                                    <el-radio :label="2">女</el-radio>
                                </el-radio-group>
                            </el-form-item>
                        </el-col>
                    </el-row>
            </div>


        </el-col>
    </el-form>
    </div>
</template>
<script>
    import {purchaseEditable} from '../../global'
    import {
        getbkNameList,
				getbankCNameList,
				getbankCName2List,
				
				
				
				
				
        getBrokerCompanyUserListPage,
        checkSFbianhao,
    } from '../../api/api';;
    export default{
        data(){
            return {
                labelPosition:'right',
                bkNameloading:false,
								bankCNameloading:false,
								bankCName2loading:false,
                bkryNameloading:false,
                lydisabled:false,
                estate: [],//服务器搜索的渠道公司数据放入这个数组中
								esbanktate: [],//服务器搜索的银行数据放入这个数组中
								esbank2tate: [],//服务器搜索的开户省数据放入这个数组中
                editVisible:true,
                editOwnerRules :{
                    shoukuanren: [
                        { required: true, message: '不能为空' }
                    ],
                    kaihuhang: [
                        { required: true, message: '不能为空' }
                    ],
                    zhanghao: [
                        { required: true, message: '不能为空' }
                    ],
										shoufangzonghang: [
												{ required: true, message: '不能为空' }
										],
										shengfen: [
												{ required: true, message: '不能为空' }
										],
										shi: [
												{ required: true, message: '不能为空' }
										],

                },
            }
        },
        props:['owner'],
        methods: {
            yezhuleixingChange(){
                //只要业主类型发生改变，那么我就将变量初始化
                if(this.$route.path=='/purchaseContract/add'||this.owner.yezhuleixing!=this.owner.yezhuleixing2){
                    this.owner.chanquanrenList = [{
                        name:'',
                        faren:'',
                        zhengjian:'',
                        tel:'',
                        sex:1,
                        hetongid:null,
                    },]
                }
            },
            leixingChange(){
                //只要类型发生改变，那么我就将变量初始化
                if(this.$route.path=='/purchaseContract/add'||this.owner.farenzhengjian!=this.owner.farenzhengjian2){
                     this.owner.oldBianhao='';
                     this.owner.jujianfangid =null;
                     this.owner.qudaorenid=null;
                     this.owner.qudaoren='';
                }
            },
            valid(){
                this.$refs.ownerForm.validate((valid) => {
                    this.owner.flag = valid;
                });
            },
						//银行的chage事件
						change1(){
								var arr = this.owner.options3;
								for (let i=0;i<arr.length;i++ ){
										if(arr[i].value==this.owner.shoufangzonghangid){
												this.owner.shoufangzonghang = arr[i].label;
												this.owner.lianhanghao = arr[i].lianhao;
										}
								}

						},
						//获取银行公司名称
						bankCompany(query) {
								let para = {
										name: query,
										id:this.owner.shoufangzonghangid!=null?this.owner.shoufangzonghangid:'',
								};
								this.bankCNameloading = true;
								getbankCNameList(para).then((res) => {
										let arr = [];
										arr[0] = '';
										for ( var i in res.data.data ){
												arr[i]=res.data.data[i]
										}
										this.esbanktate = arr;
										this.bankCNameloading = false;
										this.list = this.esbanktate.map((item,index) => {
												return { value: item.id, label: item.yinhangname , lianhao: item.yinhanglianhao };
										});
										if (query !== '') {
												this.bankCNameloading = true;
												setTimeout(() => {
														this.bankCNameloading = false;
														this.owner.options3 = this.list;
												}, 200);
										} else {
												this.owner.options3 = [];
										}
								});

						},
						//开户省的chage事件
						change2(){
								var arr = this.owner.options4;
								for (let i=0;i<arr.length;i++ ){
										if(arr[i].value==this.owner.shengfenid){
												this.owner.shengfen = arr[i].label;
										}
								}
						},
						//获取开户省公司名称
						bankCompany2(query) {
								let para = {
										name: query,
										id:this.owner.shengfenid!=null?this.owner.shengfenid:'',
								};
								this.bankCName2loading = true;
								getbankCName2List(para).then((res) => {
										let arr = [];
										arr[0] = '';
										for ( var i in res.data.data ){
												arr[i]=res.data.data[i]
										}
										this.esbank2tate = arr;
										this.bankCName2loading = false;
										this.list = this.esbank2tate.map((item,index) => {
												return { value: item.id, label: item.sname };
										});
										if (query !== '') {
												this.bankCName2loading = true;
												setTimeout(() => {
														this.bankCName2loading = false;
														this.owner.options4 = this.list;
												}, 200);
										} else {
												this.owner.options4 = [];
										}
								});

						},
            changeOnSelect1(){
                var arr = this.owner.options1;
                for (let i=0;i<arr.length;i++ ){
                    if(arr[i].value==this.owner.jujianfangid){
                        this.owner.jujianfang = arr[i].label;
                        if(this.owner.jujianfangid!=this.owner.jujianfangid2){
                            this.owner.qudaoren=null;
                            this.owner.qudaorenid=null;
                        }

                    }
                }

            },
            changeOnSelect2(){
                var arr = this.owner.options2;
                for (let i=0;i<arr.length;i++ ){
                    if(arr[i].value==this.owner.qudaorenid){
                        this.owner.qudaoren = arr[i].label;
                    }
                }
            },
            //获取渠道公司名称
            remoteMethod1(query) {
                let para = {
                    name: query,
                    parentid:0,
                };
                this.bkNameloading = true;
                getbkNameList(para).then((res) => {
                    let arr = [];
                    arr[0] = '';
                    for ( var i in res.data.data ){
                        arr[i]=res.data.data[i]
                    }
                    this.estate = arr;
                    this.bkNameloading = false;
                    this.list = this.estate.map((item,index) => {
                        return { value: item.tQdCompayId, label: item.compayname };
                    });
                    if (query !== '') {
                        this.bkNameloading = true;
                        setTimeout(() => {
                            this.bkNameloading = false;
                            this.owner.options1 = this.list;
                        }, 200);
                    } else {
                        this.owner.options1 = [];
                    }
                });

            },
            //获取渠道人员
            remoteMethod2(query) {
                let para = {
                    username: query,
                    id:this.owner.jujianfangid!=null?this.owner.jujianfangid:'',
                };
                this.bkryNameloading = true;
                getBrokerCompanyUserListPage(para).then((res) => {
                    let arr = [];
                    arr[0] = '';
                    for ( var i in res.data.data ){
                        arr[i]=res.data.data[i]
                    }
                    this.estate = arr;
                    this.bkryNameloading = false;
                    this.list = this.estate.map((item,index) => {
                        return { value: item.tQdPersonId, label: item.qdPername };
                    });
                    if (query !== '') {
                        this.bkryNameloading = true;
                        setTimeout(() => {
                            this.bkryNameloading = false;
                            this.owner.options2 = this.list;
                        }, 200);
                    } else {
                        this.owner.options2 = [];
                    }
                });

            },
            //新增产权人
            addRentItem() {
                this.owner.chanquanrenList.push({
                    name:'',
                    faren:'',
                    zhengjian:'',
                    tel:'',
                    sex:1,
                    hetongid:null,
                });
            },
            //移除产权人
            removeRentItem(item) {
                var index = this.owner.chanquanrenList.indexOf(item)
                if (index !== -1) {
                    this.owner.chanquanrenList.splice(index, 1)
                }
            },
            checkbianhao(){
                var value=this.owner.oldBianhao;
                if (value != ''&&value != null) {
                    let para = {
                        bianhao: value,
                    };
                    checkSFbianhao(para).then((res) => {
                        if (res.data.code != '200') {
                            this.$message({
                                message: res.data.msg,
                                type: 'error'
                            });
                        }else{
                            this.owner.oldID=res.data.data.id;
                        }
                    })
                }
            },
        },
        mounted(){
            //审核页面input禁用
            if(this.$route.path=='/purchaseContract/review'){
                this.editVisible   =false;
            }
            if(this.$route.path=='/purchaseContract/view'){
                this.editVisible   =false;
            }
            if(this.$route.path=='/purchaseContract/edit'&&purchaseEditable.indexOf(parseInt(this.$route.query.status))<0){
                this.lydisabled = true;
            }

        }
    }
</script>
