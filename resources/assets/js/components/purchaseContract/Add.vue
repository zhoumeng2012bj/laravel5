<template>
    <div>
        <el-row>
            <el-col :span="20">
                <add-property ref="property" v-on:changeArea="computeArea()" :property="property"
                              v-show="stepNum==1"></add-property>
                <add-owner ref="owner" :owner="owner" v-show="stepNum==2"></add-owner>
                <add-date ref="date" :addDate="addDate" :property="property" v-show="stepNum==3"></add-date>
                <history-optimize v-show="stepNum==4"></history-optimize>
                <!--<add-tiaokuan ref="tiaokuan" :tiaoList="tiaoList" v-show="stepNum==4"></add-tiaokuan>-->
            </el-col>
            <div style="margin-bottom:51px;">
            </div>
            <el-col :span="4" style="top:15%;right:0%;">
                <el-form style="margin-left:auto;width: 70%;">
                    <el-form-item label="合同版本：" style="">
                        <el-select v-model="contractVersion" placeholder="合同版本">
                            <el-option
                                    v-for="item in options"
                                    :key="item.id"
                                    :label="item.version"
                                    :value="item.version">
                            </el-option>
                        </el-select>
                    </el-form-item>
                </el-form>
                <div style="margin-left: 30%">
                    <el-steps :space="80" direction="vertical" :active="stepNum">
                        <a href="javascript:;" onfocus="this.blur();" @click="stepNum=1">
                            <el-step title="房间信息"></el-step>
                        </a>
                        <a href="javascript:;" onfocus="this.blur();" @click="stepNum=2">
                            <el-step title="业主信息"></el-step>
                        </a>
                        <a href="javascript:;" onfocus="this.blur();" @click="stepNum=3">
                            <el-step title="租期信息"></el-step>
                        </a>
                        <!--<a href="javascript:;"  onfocus="this.blur();" @click="stepNum=4"><el-step title="补充协议"></el-step></a>-->
                        <a href="javascript:" v-if="this.$route.path=='/purchaseContract/view'" onfocus="this.blur();"
                           @click="stepNum=4">
                            <el-step title="条款信息"></el-step>
                        </a>

                    </el-steps>
                    <el-button type="primary" :disabled="saveBtn" v-show="editVisible" @click="save"
                               style="margin-top:10px;">保存
                    </el-button>
                    <el-button type="primary" v-show="editVisible" :disabled="btnType" @click="submit"
                               style="margin:0px;">{{submsg}}
                    </el-button>
                    <div style="margin-top:10px;">
                        <el-button type="primary" @click="preview" style="">打印预览</el-button>
                    </div>
                    <div style="margin-left:-50px;">

                        <el-button type="primary" v-show="reviewVisible" @click="review(1)" style="margin-top:100px;">
                            通&nbsp;&nbsp;&nbsp;过
                        </el-button>
                        <el-button type="warning" v-show="reviewVisible" @click="review(0)" style="margin-top:100px;">
                            不通过
                        </el-button>
                    </div>
                </div>
            </el-col>
        </el-row>
        <el-dialog title="审核批注" :visible.sync="dialogFormVisible">
            <el-form>
                <el-form-item label="批注" label-width="50px">
                    <el-input type="textarea" v-model="content" auto-complete="off"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogFormVisible = false">取 消</el-button>
                <el-button type="primary" @click="review2">确 定</el-button>
            </div>
        </el-dialog>
    </div>
</template>
<script>
    import {purchaseEditable} from '../../global'
    import AddProperty from './AddProperty.vue'
    import AddOwner from './AddOwner.vue'
    import AddDate from './AddDate.vue'
    import HistoryOptimize from './HistoryOptimize.vue'
    import AddTiaokuan from './AddTiaoKuan.vue'
    import {
        addPurchaseContractInfo,
        reviewPurchaseContract,
        getPurchaseContractInfo,
        submitPurchaseContract,
        getPurchaseContractTiaoKuan,
        getContractVersionList
    } from '../../api/api';
    export default{
        data(){
            return {
                options: [],
                contractVersion: null,
                btnType: true,
                btnView: false,
                saveBtn: false,
                submsg: '提交',
                shenhe: null,//审核数据
                reviewVisible: false,//审核显示
                editVisible: true,
                content: '',//审核批注
                dialogFormVisible: false,
                stepNum: 1,
                id: '',
                bianhao: '',
                zhuangtai: '',
                property: {
                    editableTabs2: [
                        {
                            title: '房间1',
                            name: '1',
                            content: 'New Tab content'
                        }
                    ],
                    tabIndex: 1,
                    flag: null,
                    officeList: [{
                        chanquanzhenghao: null,
                        diyaren: null,
                        fanghao: null,
                        hetongid: null,
                        isdiya: null,
                        jianzhumianji: null,
                        leixing: null,
                        loudongName: null,
                        loudongOmcId: null,
                        loupanName: null,
                        loupanOmcId: null,
                        omcId: null,
                        qianyuemianji: null,
                        quyu: null,
                    },],
                },
                owner: {
                    flag: null,
										options3: [
												{
														value: null,
														label: null,
														lianhao: null,
												}
										],
										options4: [
												{
														value: null,
														label: null,
												}
										],
                    options1: [
                        {
                            value: null,
                            label: null,
                        }
                    ],
                    options2: [
                        {
                            value: null,
                            label: null,
                        }
                    ],
                    chengzufang: '',
                    jujianfang: '',
                    jujianfangid: null,
                    jujianfangid2: null,
                    qudaorenid: null,
                    qudaoren: '',
                    yezhuleixing: 1,
                    yezhuleixing2: 1,
                    farenzhengjian: 'l',
                    farenzhengjian2: 'l',
                    //产权人
                    chanquanrenList: [
                        {
                            name: '',
                            faren: '',
                            zhengjian: '',
                            tel: '',
                            sex: 1,
                            hetongid: null,
                        },
                    ],
                    //收款人
                    duizhangType: '',
                    shoufangzonghang: '',
                    shengfen: '',
                    shi: '',
                    lianhanghao: '',
                    shoufangzonghangid: '',
										shoufangzonghanglainghas:'',
                    shengfenid: '',
                    shoukuanren: '',
                    zhanghao: '',
                    kaihuhang: '',
                    //代理人
                    dailirenName: '',
                    dailirenTel: '',
                    dailirenSex: 1,
                    dailirenId: '',
                    //签约人
                    qianyuerenName: '',
                    qianyuerenTel: '',
                    qianyuerenSex: 1,
                    qianyuerenId: '',
                    oldBianhao: '',
                    oldID: '',
                },
                addDate: {
                    actualrent: null,
                    flag: null,
                    startdate: '',//租期开始时间
                    enddate: '',//租期结束时间
                    shoufangdate: '',//收房日期
                    qianyuedate: '',//签约日期
                    mianzufangshi: [],//免租方式
                    mianzuqiList: [{
                        startdate: '',//免租开始
                        enddate: '',//免租结束
                        mianzufangshi: '',
                    }],
                    fukuanFangshiList: [{
                        startdate: '',//开始
                        enddate: '',//结束
                        yajinyue: '',
                        zujinyue: '',
                    }],
                    yajin: '',//押金
                    zongyingfuzujin: '',//总租金
                    yongjin: '',//佣金
                    tiqianfukuantian: '',//提前付款天数
                    beianqixian: '',
                    yajinfukuanri: '',//押金付款日
                    shouqifukuanri: '',//首期租金付款日
                    erqifukuanri: '',//二期付款
                    sanqifukuanri: '',//三期付款
                    buchongtiaokuan: '',//补充条款
                    yanqizujin: '',
                    zujinList: [{
                        startdate: '',
                        enddate: '',
                        yuezujin: '',
                        price: '',
                        dizengfangshi: '',
                        dizengliang: '',
                    }],
                    jiafangfeiyong: [],
                    yifangfeiyong: [],
                    yingyezhizhao: '',
                },
                tiaoList: [],

            }
        },
        components: {
            AddProperty,
            AddOwner,
            AddDate,
            AddTiaokuan,
            HistoryOptimize
        },
        methods: {
            submit(){
                this.$refs.owner.valid();
                this.$refs.property.valid();
                this.$refs.date.valid();
                if (this.property.flag && this.owner.flag && this.addDate.flag) {
                    let para = {
                        id: this.id,
                        bianhao: this.bianhao,
                        zd: this.property.officeList[0].loudongOmcId,
                    };
                    submitPurchaseContract(para).then((res) => {
                        if (res.data.code == 200) {
                            this.$message({
                                message: '提交成功',
                                type: 'success'
                            });
                            history.go(-1);
                            this.btnType = true;
                            this.submsg = '已提交';
                        } else {
                            this.$message({
                                message: res.data.msg,
                                type: 'error'
                            })
                        }
                    })
                }
                else {
                    if (this.property.flag == false) {
                        this.stepNum = 3;
                    }
                    if (this.owner.flag == false) {
                        this.stepNum = 2;
                    }
                    if (this.addDate.flag == false) {
                        this.stepNum = 1;
                    }
                    this.btnType = true;
                    this.$message({
                        message: '数据格式有问题，请检查',
                        type: 'error'
                    });
                }
            },
            save() {
                if (this.owner.oldBianhao != '' && this.owner.oldID == '') {
                    this.$message({
                        message: '原合同编号对应的合同不存在',
                        type: 'error'
                    });
                    return false;
                }
                this.submsg = '提交';
                this.saveBtn = true;
                var child_property = this.$refs.property.property;//
                var child_owner = this.$refs.owner.owner;//业主信息
                this.addDate.yingyezhizhao = this.addDate.yingyezhizhao.replace(/\n|\r\n/g, "<br>");
                var child_date = this.$refs.date.addDate;//日期
                var id = {
                    id: this.id
                };
                var bianhao = {
                    bianhao: this.bianhao,
                }
                var version = {
                    version: this.contractVersion,
                }
                let para = Object.assign({}, child_property, child_owner, child_date, id, bianhao, version);
                addPurchaseContractInfo(para).then((res) => {
                    if (res.data.code == 200) {
                        //保存完以后可以得到一个返回的ID
                        //把数据分别赋值给三个组件的变量

                        if (purchaseEditable.indexOf(parseInt(this.$route.query.status)) >= 0 || this.$route.path == '/purchaseContract/add') {
                            if (this.$route.query.zt != 1) {
                                this.btnType = false;
                            }

                        }
                        this.fuzhi(res);
                        this.saveBtn = false;
                        this.$message({
                            message: '保存成功',
                            type: 'success'
                        });
                    } else {
                        this.$message({
                            message: res.data.msg,
                            type: 'error'
                        })
                    }
                });
            },
            review(result){
                this.dialogFormVisible = true;
                var flag = this.$route.query.flag;
                //审核
                this.shenhe = {
                    hetongid: this.id,
                    result: result,
                    shenheFlg: flag
                };
            },
            review2(){
                let para = Object.assign({}, {content: this.content}, this.shenhe);
                reviewPurchaseContract(para).then((res) => {
                    if (res.data.code == 200) {
                        history.go(-1);
                        this.dialogFormVisible = false;
                    } else {
                        this.$message({
                            message: res.data.msg,
                            type: 'error'
                        })
                    }
                });
            },
            //根据url得到的合同ID，来获取数据
            getPurchaseContract(id){
                getPurchaseContractInfo(id).then((res) => {
                    if (res.data.code == '200') {
                        //把数据分别赋值给三个组件的变量
                        this.fuzhi(res);
                    } else {
                        this.$message({
                            message: '获取数据失败',
                            type: 'error'
                        });
                    }
                })
            },
            //获取条款信息
//            getTiaokuan(){
//                getPurchaseContractTiaoKuan().then((res)=>{
//                    for (let x in res.data.data.tiaoList){
//                        res.data.data.tiaoList[x].show = false;
//                        for (let y in res.data.data.tiaoList[x].kuanList){
//                            res.data.data.tiaoList[x].kuanList[y].show = false;
//                            for (let z in res.data.data.tiaoList[x].kuanList[y].xiangList){
//                                res.data.data.tiaoList[x].kuanList[y].xiangList[z].show = false;
//                            }
//                        }
//                    }
//                    this.tiaoList = res.data.data.tiaoList;
//                    //console.log(this.tiaoList);
//
//                })
//            },
            //获取当前启用的合同版本
            getVersion(){
                let para = {
                    category: 0,
                    status: 1,
                };
                this.listLoading = true;
                getContractVersionList(para).then((res) => {
                    this.options = res.data.data;
                    if (this.contractVersion == null) {
                        this.contractVersion = this.options[0].version;
                    }
                });
            },
            preview(){
                var version = this.contractVersion;
                let _this = this;
                let para = {
                    id: _this.id,
                }
                if (version == 's20180907' || version == 's20180927') {
                    window.open('/#/purchaseContract/dump' + version + 'homepage?id=' + _this.id + '&isdump=2&bianhao=' + this.bianhao)
                }
                window.open('/#/purchaseContract/dump' + version + '?id=' + _this.id + '&isdump=2&bianhao=' + _this.bianhao)
                window.open('/#/purchaseContract/dump' + version + 'pub?id=' + _this.id + '&isdump=2&bianhao=' + _this.bianhao)
                if (version == 's20180927') {
                    window.open('/#/purchaseContract/dump' + version + 'enclosure?id=' + _this.id + '&isdump=2&bianhao=' + _this.bianhao)
                }

            },
            fuzhi(res){
                //console.log(res.data.data);
                this.id = res.data.data.id;
                this.zhuangtai = res.data.data.zhuangtai;
                if (this.zhuangtai == 4 || this.zhuangtai == 15) {
                    this.$notify({
                        title: '提示',
                        message: res.data.data.shenheJiluList[res.data.data.shenheJiluList.length - 1].content == null ? '审核拒绝：无' : '审核拒绝：' + res.data.data.shenheJiluList[res.data.data.shenheJiluList.length - 1].content,
                        duration: 0,
                        type: 'warning',
                    });
                }
                this.bianhao = res.data.data.bianhao;
                this.contractVersion = res.data.data.version;
                this.property.officeList = res.data.data.officeList;
                this.property.editableTabs2 = [];
                this.property.officeList.forEach((property, index) => {
                    index++;
                    this.property.tabIndex = index;
                    this.property.editableTabs2.push({
                        title: '房间' + index,
                        name: index + '',
                        content: 'New Tab content'
                    })
                })
                if (res.data.data.chanquanrenList.length > 0) {
                    this.owner.chanquanrenList = res.data.data.chanquanrenList;
                }
                this.owner.chengzufang = res.data.data.chengzufang;
								
								this.owner.duizhangType = res.data.data.duizhangType;
								this.owner.shoufangzonghang = res.data.data.shoufangzonghang;//
								this.owner.shoufangzonghangid = res.data.data.shoufangzonghangid;
								this.owner.lianhanghao = res.data.data.lianhanghao;
								this.owner.shengfen = res.data.data.shengfen;//
								this.owner.shengfenid = res.data.data.shengfenid;
								this.owner.options3[0].value = res.data.data.shoufangzonghangid;
								this.owner.options3[0].label = res.data.data.shoufangzonghang;
								this.owner.options3[0].lianhao = res.data.data.lianhanghao;
								this.owner.options4[0].value = res.data.data.shengfenid;
								this.owner.options4[0].label = res.data.data.shengfen;
								this.owner.shoukuanren = res.data.data.shoukuanren;//
								this.owner.kaihuhang = res.data.data.kaihuhang;//
								this.owner.zhanghao = res.data.data.zhanghao;//
								this.owner.shi = res.data.data.shi;//
								
								
                this.owner.jujianfang = res.data.data.jujianfang;//
                this.owner.jujianfangid = res.data.data.jujianfangid;
                this.owner.jujianfangid2 = res.data.data.jujianfangid;
                this.owner.qudaoren = res.data.data.qudaoren;//
                this.owner.qudaorenid = res.data.data.qudaorenid;

                this.owner.options1[0].value = res.data.data.jujianfangid;
                this.owner.options1[0].label = res.data.data.jujianfang;
                this.owner.options2[0].value = res.data.data.qudaorenid;
                this.owner.options2[0].label = res.data.data.qudaoren;
                this.owner.yezhuleixing = res.data.data.yezhuleixing;
                this.owner.farenzhengjian = res.data.data.farenzhengjian;
                this.owner.yezhuleixing2 = res.data.data.yezhuleixing;
                this.owner.farenzhengjian2 = res.data.data.farenzhengjian;
                // this.owner.shoukuanren = res.data.data.shoukuanren;
//                 this.owner.kaihuhang = res.data.data.kaihuhang;
//                 this.owner.zhanghao = res.data.data.zhanghao;
                this.owner.dailirenTel = res.data.data.dailirenTel;
                this.owner.dailirenSex = res.data.data.dailirenSex;
                this.owner.dailirenId = res.data.data.dailirenId;
                this.owner.dailirenName = res.data.data.dailirenName;
                this.owner.qianyuerenName = res.data.data.qianyuerenName;
                this.owner.qianyuerenTel = res.data.data.qianyuerenTel;
                this.owner.qianyuerenSex = res.data.data.qianyuerenSex;
                this.owner.qianyuerenId = res.data.data.qianyuerenId;
                this.owner.oldBianhao = res.data.data.oldBianhao;
                this.owner.oldID = res.data.data.oldID;
//                 this.owner.duizhangType = res.data.data.duizhangType;
//                 this.owner.shoufangzonghang = res.data.data.shoufangzonghang;
//                 this.owner.shengfen = res.data.data.shengfen;
//                 this.owner.shi = res.data.data.shi;
//                 this.owner.lianhanghao = res.data.data.lianhanghao;
//                 this.owner.shoufangzonghangid = res.data.data.shoufangzonghangid;
//                 this.owner.shengfenid = res.data.data.shengfenid;
                this.addDate.startdate = res.data.data.startdate;
                this.addDate.enddate = res.data.data.enddate;
                this.addDate.shoufangdate = res.data.data.shoufangdate;
                this.addDate.qianyuedate = res.data.data.qianyuedate;
                this.addDate.mianzufangshi = res.data.data.mianzufangshi;
                this.addDate.mianzuqiList = res.data.data.mianzuqiList;
                this.addDate.fukuanFangshiList = res.data.data.fukuanFangshiList;
                this.addDate.fukuanFangshiList.sort(function (a, b) {
                    return a.startdate - b.startdate
                })
                this.addDate.yajin = res.data.data.yajin;
                this.addDate.zongyingfuzujin = res.data.data.zongyingfuzujin;
                this.addDate.yongjin = res.data.data.yongjin;
                this.addDate.actualrent = res.data.data.zujinList[0].yuezujin;
                this.addDate.tiqianfukuantian = res.data.data.tiqianfukuantian;
                this.addDate.beianqixian = res.data.data.beianqixian;
                this.addDate.yajinfukuanri = res.data.data.yajinfukuanri;
                this.addDate.shouqifukuanri = res.data.data.shouqifukuanri;
                this.addDate.erqifukuanri = res.data.data.erqifukuanri;
                this.addDate.sanqifukuanri = res.data.data.sanqifukuanri;
                this.addDate.buchongtiaokuan = res.data.data.buchongtiaokuan;
                this.addDate.zujinList = res.data.data.zujinList;
                this.addDate.zujinList.sort(function (a, b) {
                    return a.startdate - b.startdate
                })
                this.addDate.yanqizujin = res.data.data.yanqizujin;
                //this.addDate.checkList = res.data.data.checkList;
                this.addDate.jiafangfeiyong = res.data.data.jiafangfeiyong;
                this.addDate.yifangfeiyong = res.data.data.yifangfeiyong;
                var reg = new RegExp("<br>", "g"); //创建正则RegExp对象
                this.addDate.yingyezhizhao = res.data.data.yingyezhizhao.replace(reg, "\n");


                //给条款的每一条数据都添加一个属性字段show
                for (let x in res.data.data.tiaoList) {
                    res.data.data.tiaoList[x].show = false;
                    for (let y in res.data.data.tiaoList[x].kuanList) {
                        res.data.data.tiaoList[x].kuanList[y].show = false;
                        for (let z in res.data.data.tiaoList[x].kuanList[y].xiangList) {
                            res.data.data.tiaoList[x].kuanList[y].xiangList[z].show = false;
                        }
                    }
                }
                this.tiaoList = res.data.data.tiaoList;
            },
            disabledInput(){
                var allInputs = document.getElementsByTagName('input');
                var textArea = document.getElementsByTagName('textarea');
                for (let i = 0; i < allInputs.length; i++) {
                    allInputs[i].disabled = "true";
                    allInputs[i].parentNode.className += " is-disabled";
                }
                for (let i = 0; i < textArea.length; i++) {
                    textArea[i].disabled = "true";
                    //textArea[i].parentNode.className += " is-disabled";
                }

            },
            computeArea(){
                //1.先计算面积综合
                var areaToal = 0;
                this.property.officeList.forEach((property, index) => {
                    areaToal += property.qianyuemianji;
                })
                //2.计算单价
                this.addDate.zujinList.forEach((item, index) => {
                    var perPrice = (item.yuezujin * 12 / 365 / areaToal).toFixed(2);
                    this.addDate.zujinList[index].price = parseFloat(perPrice);
                })
            }
        },
        mounted() {
            var flag = this.$route.query.flag;
            //根据url得到的合同ID，来获取数据
            if (this.$route.query.id != null) {
                this.getPurchaseContract(this.$route.query);
            }
            //审核页面input禁用
            if (this.$route.path == '/purchaseContract/review') {
                this.reviewVisible = true;
                this.editVisible = false;
                var _this = this;

                function hello() {
                    _this.disabledInput();
                }

                setTimeout(hello, 500);
            }
            if (this.$route.path == '/purchaseContract/view') {
                this.editVisible = false;
                var _this = this;

                function hello() {
                    _this.disabledInput();
                }

                setTimeout(hello, 500);
            }
            //新增页面获取默认条款
            if (this.$route.path == '/purchaseContract/add') {
                //this.getTiaokuan();
            }

            this.getVersion();
        },

    }
</script>
