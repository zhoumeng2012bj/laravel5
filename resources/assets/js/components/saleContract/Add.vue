<template>
    <div>
        <el-row>
            <el-col :span="20">
                <add-property ref="property" :property="property"
                              v-on:getshoufanghetong="getChuzuren"
                              v-on:changeArea="computeArea" v-show="stepNum==1"></add-property>
                <!--标签中v-on:getshoufanghetong="getChuzuren"是监听事件，getshoufanghetong自己定义的监听的名字，getChuzuren是监听到这个事件之后发生操作的名字，v-on监听事件且是监听谁就放的谁上面-->
                <add-renter ref="renter" :renter="renter" v-show="stepNum==2" ></add-renter>
                <add-date ref="date" :property="property" :addDate="addDate" v-show="stepNum==3"></add-date>
                <history-buchong v-show="stepNum==4"></history-buchong>
                <list-jieyue v-show="stepNum==5"></list-jieyue>
                <!--<add-tiaokuan ref="tiaokuan" v-show="stepNum==4"></add-tiaokuan>-->
            </el-col>
            <div style="margin-bottom:51px;"></div>
            <el-col :span="4">
                <el-form>
                    <el-form-item label="合同版本：" style="margin-left:auto;width: 70%;">
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
                <div style="margin-left: 30%;">
                    <el-steps :space="100" direction="vertical" :active="stepNum">
                        <a href="javascript:;" @click="stepNum=1"><el-step  title="房间信息"></el-step></a>
                        <a href="javascript:;" @click="stepNum=2"><el-step  title="租户信息"></el-step></a>
                        <a href="javascript:;" @click="stepNum=3"><el-step  title="租期信息"></el-step></a>
                        <a href="javascript:;" v-if="this.$route.path=='/saleContract/see'" @click="stepNum=4"><el-step  title="补充协议"></el-step></a>
                        <a href="javascript:;" v-if="this.$route.path=='/saleContract/see'" @click="stepNum=5"><el-step  title="解约协议"></el-step></a>
                    </el-steps>
                    <el-input type="hidden" prop="id"  auto-complete="off"></el-input>
                    <el-button type="primary" :disabled="saveBtn" v-show="!reviewVisible" @click="save" style="margin-top:10px;">保存</el-button>
                    <el-button style="margin:0px;" type="primary" v-show="!reviewVisible" :disabled="btnType" @click="submit" >{{submsg}}</el-button>
                    <div style="margin-top:10px;">
                        <el-button type="primary" @click="preview" >打印预览</el-button>
                    </div>
                    <el-button type="primary" v-show="tonguo" @click="review(1)" style="margin-top:100px;">通&nbsp;&nbsp;&nbsp;过</el-button>
                    <el-button type="warning" v-show="tonguo" @click="review(0)" style="margin-top:100px;">不通过</el-button>
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
    import {saleEditable } from '../../global'
    import AddProperty from './AddProperty.vue'
    import AddRenter from './AddRenter.vue'
    import AddDate from './AddDate.vue'
    import AddTiaokuan from './AddTiaoKuan.vue'
    import HistoryBuchong from './HistoryBuchong.vue';
    import ListJieyue from './ListJieyue.vue';
    import {addSaleContractInfo,getSaleContractInfo,submitSaleContract,reviewSaleContract,getContractVersionList,getContractChuzuren} from '../../api/api';
    export default{
        data(){
            return {
                options:[
                ],
                contractVersion:null,
                btnType:true,
                saveBtn:false,
                submsg:'提交',
                shenhe:null,//审核数据
                reviewVisible:false,//审核显示
                tonguo:false,
                content:'',//审核批注
                dialogFormVisible:false,
                stepNum:1,
                id:'',
                //fangyuanId:'', 
                bianhao:'',
                zhuangtai:'',
                property:{
                    editableTabs2:[
                        {
                            title: '房间1',
                            name: '1',
                            content: 'New Tab content'
                        }
                    ],
                    tabIndex:1,
                    syczmj:'',
                    subleaseno:null,
                    flag:null,
                    xsOffice: [{
                        omcId:null,
                        loupanOmcId:null,
                        loudongOmcId:null,
                        loupanName:null,
                        loudongName:null,
                        fanghao:null,
                        fanghao2:null,
                        quyu:'',
                        weizhi: null,
                        chanquanzhenghao: null,
                        jianzhumianji: null,
                        qianyuemianji: null,
                        leixing: null,
                        hetongid:null,
                        syczmj:null,
                    }],
                },
                renter:{
                    flag:null,
                    options1:[
                        {
                            value:null,
                            label:null,
                        }
                    ],
                    optionsyslxr1:[
                        {
                            value:null,
                            label:null,
                            phone:null,
                        }
                    ],
                    options2:[
                        {
                            value:null,
                            label:null,
                        }
                    ],
                    chengzufang:'',
                    jujianfangtype:1,
                    jujianfangtype2:1,
                    jujianfang:'',
                    jujianfangid:null,
                    jujianfangid2:null,
                    qudaorenid:null,
                    qudaoren:'',
                    zuhuleixing:1,
                    zuhuleixing2:1,
                    //产权人
                    chengzuren:[
                        {
                            faren:'',
                            name:'',
                            idNo:'',
                            tel:'',
                            sex:1,
                            hetongid:null,
                        },
                    ],
                    //销售人
                    salesmanList:[
                        {
                            salesmanid:null,
                            salesmanname:null,
                            salesmanphone:null,
                            hetongtid:1,
                            hetongid:null,
                        },
                    ],
                    //收款人
                    shoukuanren:'',
                    zhanghao:'',
                    kaihuhang:'',
                    //代理人
                    dailirenName:'',
                    dailirenTel:'',
                    dailirenSex:1,
                    dailirenId:'',
                    //签约人
                    qianyuerenName:'',
                    qianyuerenTel:'',
                    qianyuerenSex:1,
                    qianyuerenId:'',
                    hetongtype:1,//合同类型
                    hetongtype2:1,//合同类型
                    oldBianhao:'',
                    oldID:'',
                },
                addDate: {
                    dikoujine:'',//合同金额
                    startdate:'',//租期开始时间
                    enddate:'',//租期结束时间
                    //xinjianshoufangdate: '',//收房日期
                    //xinjianqianyuedate: '',//签约日期
                    shoufangdate: '',//收房日期
                    qianyuedate: '',//签约日期

                    /*mianzuqiList: [{
                        startdate:'',//免租开始
                        enddate:'',//免租结束
                        mianzufangshi:'',//免租方式
                    }],*/
                    mianzuqiList:[],
                    fukuanFangshiList:[{
                        startdate:'',//开始
                        enddate:'',//结束
                        yajinyue:'',
                        zujinyue:'',
                    }],
                    yajin:'',//押金
                    yingfuzongzujin:'',//总租金
                    hetongyongjin:'',//佣金
                    tiqianfukuantian:'',//提前付款天数
                    yajinfukuanriqi:'',//押金付款日
                    shouqifukuanri:'',//首期租金付款日
                    erqifukuanri:'',//二期付款
                    sanqifukuanri:'',//三期付款
                    /*buchongTiaokuanList:[
                        {
                            content:'',
                        }
                    ],//补充条款*/
                    iscompletefrzj:'',//补充条款
                    actualrent:'',
                    zujinList:[
                        {
                            startdate:'',
                            enddate:'',
                            yuezujin:'',
                            price:'',
                            dizengfangshi:'',
                            dizengliang:'',
                        },
                    ],
                    checkList: [],
                    jiafangfeiyong:'',
                    ispaydeposit:2,
                    nengyuanyajin:'',
                    ispaydeposit2:2,
                    housetax:2,
                },
            }
        },
        components:{
            AddProperty,
            AddRenter,
            AddDate,
            AddTiaokuan,
            HistoryBuchong,
            ListJieyue,
        },
        methods:{
            submit(){
                this.$refs.renter.valid();
                this.$refs.property.valid();
                this.$refs.date.valid();
                if(this.property.flag && this.renter.flag && this.addDate.flag) {
                    let  para = {
                        id: this.id,
                    }
                submitSaleContract(para).then((res)=>{
                    if(res.data.code == 200){
                        history.go(-1);
                        this.btnType = true;
                        this.submsg  = '已提交';
                    }else{
                        this.$message({
                            message:res.data.msg,
                            type:'error'
                        })
                    }
                })
                }else{
                    if(this.property.flag==false){this.stepNum = 3;}
                    if(this.renter.flag==false){this.stepNum = 2;}
                    if(this.addDate.flag==false){this.stepNum = 1;}
                    this.btnType = true;
                    this.$message({
                        message: '数据格式有问题，请检查',
                        type:'error'
                    })
                }
            },
            save:function () {
                if (this.renter.oldBianhao != '' && this.renter.oldID == '') {
                    this.$message({
                        message: '原合同编号对应的合同不存在',
                        type: 'error'
                    });
                    return false;
                }
                this.saveBtn = true;
                this.submsg  = '提交';
                    var child_property = this.$refs.property.property;
                    var child_renter  = this.$refs.renter.renter;
                    this.addDate.jiafangfeiyong = this.addDate.jiafangfeiyong.replace(/\n|\r\n/g,"<br>");
                    var child_date = this.$refs.date.addDate;
                    var id = {
                        id: this.id
                    };
                    var bianhao = {
                        bianhao: this.bianhao,
                    };
                    var version ={
                        version:this.contractVersion,
                    }
                    let para = Object.assign({}, child_property,child_renter,child_date,id,bianhao,version);
                    addSaleContractInfo(para).then((res) => {
                        if(res.data.code == 200)　{
                            if(saleEditable.indexOf(parseInt(this.$route.query.status))>=0||this.$route.path=='/saleContract/add'){
                                if(this.$route.query.zt!=1){
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
                //审核
                this.shenhe = {
                    hetongid:this.id,
                    result:result,
                    shenheFlg:this.$route.query.flag
                };
            },
            //审核通过
            review2(){
                let para = Object.assign({},{content:this.content},this.shenhe);
                    reviewSaleContract(para).then((res) => {
                    if(res.data.code == 200)　{
                        history.go(-1);
                        this.dialogFormVisible = false;
                    }else{
                        this.$message({
                            message:res.data.msg,
                            type:'error'
                        })
                    }
                });
            },
            //根据url得到的合同ID，来获取数据
            getSaleContract(id){
                getSaleContractInfo(id).then((res)=>{
                    if(res.data.code=='200'){
                        //把数据分别赋值给三个组件的变量
                        this.fuzhi(res);
                    }else {
                        this.$message({
                            message: '获取数据失败',
                            type: 'error'
                        });
                    }
                })
            },
            computeArea(){
                //1.先计算面积综合
                var areaToal = 0;
                this.property.xsOffice.forEach((property,index)=>{
                    areaToal += property.qianyuemianji;
                })
                //2.计算单价
                this.addDate.zujinList.forEach((item,index)=>{
                    var perPrice = (item.yuezujin*12/365/areaToal).toFixed(2);
                    this.addDate.zujinList[index].price = parseFloat(perPrice);
                })
            },
            //获取当前启用的合同版本
            getVersion(){
                let para = {
                    category: 1,
                    status:1,
                };
                this.listLoading = true;
                getContractVersionList(para).then((res) => {
                    this.options = res.data.data;
                    if(this.contractVersion==null){
                        this.contractVersion = this.options[0].version;
                    }
                });
            },
            preview(){
                var version = this.contractVersion;
                let _this = this;
                let para = {
                    id:_this.id,
                }
                if(version == 'x20180907' || version == 'x20180927' || version == 'x20181101' || version == 'x20181106'|| version == 'x20181119'){
                    window.open('/#/saleContract/dump'+version+'homepage?id='+_this.id+'&isdump=2&bianhao='+this.bianhao);
                }
                window.open('/#/saleContract/dump'+version+'?id='+_this.id+'&isdump=2&bianhao='+this.bianhao);
                window.open('/#/saleContract/dump'+version+'pub?id='+_this.id+'&isdump=2&bianhao='+this.bianhao);
                if(version == 'x20180927'){
                    window.open('/#/saleContract/dump'+version+'enclosure?id='+_this.id+'&isdump=2&bianhao='+this.bianhao);
                }
								if(version == 'x20181101' && this.addDate.mianzuqiList.length >0){
										window.open('/#/saleContract/dump'+version+'suppleagree?id='+_this.id+'&isdump=2&bianhao='+this.bianhao);
								}
            },
            fuzhi(res){
                this.id = res.data.data.id;
                this.zhuangtai = res.data.data.zhuangtai;
                this.bianhao = res.data.data.bianhao;
                if(this.zhuangtai==4||this.zhuangtai==17){
                    this.$notify({
                        title: '提示',
                        message: res.data.data.xsHetongshenhe[res.data.data.xsHetongshenhe.length-1].content==null?'审核拒绝：无':'审核拒绝：'+res.data.data.xsHetongshenhe[res.data.data.xsHetongshenhe.length-1].content,
                        duration: 0,
                        type: 'warning',
                    });
                }
                this.contractVersion = res.data.data.version;
                if(res.data.data.xsOffice.length>0){
                    this.property.xsOffice = res.data.data.xsOffice;
                }
                this.property.editableTabs2 = [];
                this.property.xsOffice.forEach((property,index)=>{
                    index ++;
                    this.property.tabIndex = index;
                    this.property.xsOffice[index-1].fanghao2 = this.property.xsOffice[index-1].fanghao;
                    this.property.editableTabs2.push({
                        title: '房间'+index,
                        name: index+'',
                        content: 'New Tab content'
                    })
                })
                this.property.subleaseno = res.data.data.subleaseno;
                if(res.data.data.chengzuren.length>0){
                    this.renter.chengzuren = res.data.data.chengzuren;
                }
                /*if(res.data.data.chengzuren.length>0){
                    this.renter.chengzuren = res.data.data.chengzuren;
                }*/
                this.renter.chengzufang = res.data.data.chengzufang;
                this.renter.chengzufang2 = res.data.data.chengzufang;
                this.renter.shoukuanren = res.data.data.shoukuanren;
                this.renter.kaihuhang = res.data.data.kaihuhang;
                this.renter.zhanghao = res.data.data.zhanghao;
                this.renter.jujianfangtype = res.data.data.jujianfangtype;
                this.renter.jujianfangtype2 = res.data.data.jujianfangtype;
                this.renter.jujianfangid = res.data.data.jujianfangid;
                this.renter.jujianfang = res.data.data.jujianfang;
                this.renter.jujianfangid2 = res.data.data.jujianfangid;
                this.renter.qudaoren = res.data.data.qudaoren;
                this.renter.qudaorenid = res.data.data.qudaorenid;
               if(res.data.data.salesmanList.length>0){
                   this.renter.salesmanList = res.data.data.salesmanList;
                   this.renter.optionsyslxr1[0].value = res.data.data.salesmanList[0].salesmanid;
                   this.renter.optionsyslxr1[0].label =  res.data.data.salesmanList[0].salesmanname;
               }
                this.renter.options1[0].value = res.data.data.jujianfangid;
                this.renter.options1[0].label = res.data.data.jujianfang;
                this.renter.options2[0].value = res.data.data.qudaorenid;
                this.renter.options2[0].label = res.data.data.qudaoren;

                /*for (let x in res.data.data.jujianfangid){
                    this.renter.options1[x].value = res.data.data.jujianfangid;
                    this.renter.options1[x].label = res.data.data.jujianfang;
                }*/
                this.renter.zuhuleixing = res.data.data.zuhuleixing;
                this.renter.zuhuleixing2 = res.data.data.zuhuleixing;
                this.renter.shoukuanren = res.data.data.shoukuanren;
                this.renter.zhanghao = res.data.data.zhanghao;
                this.renter.qianyuerenName = res.data.data.qianyuerenName;
                this.renter.qianyuerenTel = res.data.data.qianyuerenTel;
                this.renter.qianyuerenSex = res.data.data.qianyuerenSex;
                this.renter.qianyuerenId = res.data.data.qianyuerenId;
                this.renter.hetongtype = res.data.data.hetongtype;
                this.renter.hetongtype2 = res.data.data.hetongtype;
                this.renter.oldBianhao= res.data.data.oldBianhao;
                this.renter.oldID= res.data.data.oldID;
                this.addDate.startdate = res.data.data.startdate;
                this.addDate.enddate = res.data.data.enddate;
                this.addDate.shoufangdate = res.data.data.shoufangdate;
                this.addDate.qianyuedate = res.data.data.qianyuedate;
                this.addDate.mianzufangshi = res.data.data.mianzufangshi;
                this.addDate.mianzuqiList = res.data.data.mianzuqiList;
                this.addDate.fukuanFangshiList = res.data.data.fukuanFangshiList;
                this.addDate.fukuanFangshiList.sort(function(a,b){
                    return a.startdate-b.startdate})
                this.addDate.yajin = res.data.data.yajin;
                this.addDate.yingfuzongzujin = res.data.data.yingfuzongzujin;
                this.addDate.hetongyongjin = res.data.data.hetongyongjin;
                this.addDate.tiqianfukuantian = res.data.data.tiqianfukuantian;
                this.addDate.yajinfukuanriqi = res.data.data.yajinfukuanriqi;
                this.addDate.shouqifukuanri = res.data.data.shouqifukuanri;
                this.addDate.erqifukuanri = res.data.data.erqifukuanri;
                this.addDate.sanqifukuanri = res.data.data.sanqifukuanri;
                this.addDate.actualrent = res.data.data.actualrent;
                this.addDate.zujinList = res.data.data.zujinList;
                this.addDate.zujinList.sort(function(a,b){
                    return a.startdate-b.startdate})
                this.addDate.checkList = res.data.data.checkList;
                this.addDate.iscompletefrzj = res.data.data.iscompletefrzj;
                var reg=new RegExp("<br>","g"); //创建正则RegExp对象
                this.addDate.jiafangfeiyong = res.data.data.jiafangfeiyong.replace(reg,"\n");
                this.addDate.ispaydeposit = res.data.data.ispaydeposit;
                this.addDate.ispaydeposit2 = res.data.data.ispaydeposit;
                this.addDate.nengyuanyajin = res.data.data.nengyuanyajin;
                this.addDate.housetax = res.data.data.housetax;
            },
            disabledInput(){
                this.reviewVisible = true;
                this.tonguo = true;
                var allInputs = document.getElementsByTagName('input');
                var textArea = document.getElementsByTagName('textarea');
                for (let i=0; i<allInputs.length; i++){
                    allInputs[i].disabled="true";
                    allInputs[i].parentNode.className += " is-disabled";
                }
                for (let i=0; i<textArea.length; i++){
                    textArea[i].disabled="true";
                    textArea[i].parentNode.className += " is-disabled";
                }

            },
            seeDisabledInput(){

                var allInputs = document.getElementsByTagName('input');
                var textArea = document.getElementsByTagName('textarea');
                for (let i=0; i<allInputs.length; i++){
                    allInputs[i].disabled="true";
                    allInputs[i].parentNode.className += " is-disabled";
                }
                for (let i=0; i<textArea.length; i++){
                    textArea[i].disabled="true";
//                    textArea[i].parentNode.className += " is-disabled";
                }

            },
            //获取收房合同出租人
            getChuzuren(){
                let para = {
                    fangyuanId:this.property.xsOffice[0].omcId,
                };
                getContractChuzuren(para).then((res) => {
                    if(res.data.code=='102001'){
                        this.renter.chengzufang = '无';
                    }else{
                        if(res.data.code=='200'){
                            this.renter.chengzufang = res.data.data.diyaren;
                        }else{
                            this.$message({
                                message: '获取不了收房合同的承租人',
                                type: 'error'
                            });
                        }
                    }

                });
            },
        },
        mounted() {
            //根据url得到的合同ID，来获取数据
            if(this.$route.query.id!=null){
                this.getSaleContract(this.$route.query);
            }
            //审核页面input禁用
            if(this.$route.path=='/saleContract/review'){
                this.reviewVisible =true;
                this.tonguo   =false;
                var _this = this;
                function  hello() {
                    _this.disabledInput();
                }
                setTimeout(hello,500);
            }
            //查看页面input禁用
            if(this.$route.path=='/saleContract/see'){
                this.reviewVisible = true;
                this.tonguo = false;
                var _this = this;
                function  hello() {
                    _this.seeDisabledInput();
                }
                setTimeout(hello,500);
            }
            //新增页面获取版本
            this.getVersion();
            /*fadsfadf*/
        },

    }
</script>
