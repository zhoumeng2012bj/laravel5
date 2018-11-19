<template>
    <el-row class="container">
        <el-form :model="addDate" ref="addDateForm" :rules="editDateRules"  label-width="100px" class="demo-dynamic">
            <el-row>
                <el-col :span="8">
                    <el-form-item label="签约日" prop="qianyuedate" >
                        <el-date-picker :disabled="lydisabled" type="date" placeholder="选择日期" v-model="addDate.qianyuedate" style="width: 100%;"></el-date-picker>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-form-item label="免租期" v-for="(item, index) in addDate.mianzuqiList"
                          :key="item.key"
                          >
                <el-col  style="width:410px;">

                <el-col :span="12">
                    <el-date-picker :disabled="lydisabled" type = "date" placeholder="开始时间" v-model="item.startdate">
                    </el-date-picker>
                </el-col>
                <el-date-picker :disabled="lydisabled" type = "date" placeholder="结束时间" v-model="item.enddate">
                </el-date-picker>
                   <!-- </el-form-item>-->
                </el-col>
                <el-radio-group v-model="item.mianzufangshi" :disabled="lydisabled">
                    <el-radio :label="1">期内免租</el-radio>
                    <el-radio :label="2">期外免租</el-radio>
                </el-radio-group>
                <el-button :disabled="lydisabled" v-show="editVisible" @click.prevent="removeFreeItem(item)">删除</el-button>

            </el-form-item>
            <el-form-item>
                <el-button :disabled="lydisabled" v-show="editVisible"  @click="addFreeItem">新增免租期</el-button>
            </el-form-item>
            <!--租期-->
            <el-form-item label="总租期" required>
                <el-col  style="width:410px;">
                    <el-col :span="12">
                        <el-form-item prop="startdate">
                            <el-date-picker
                                    v-model="addDate.startdate"
                                    type="date"
                                    placeholder="开始时间"
                                    @change="zqchange1()"
                                    :disabled="lydisabled"
                            >
                            </el-date-picker>
                        </el-form-item>
                    </el-col>
                <el-col :span="12">
                        <el-form-item
                                :rules="[
                                        {  required: true,validator:
                                        (rule,value,callback)=>{
                                            var d1= new Date( addDate.zong.enddate);
                                            var d2= new Date(value);
                                            if(value==null){
                                                callback('不能为空');
                                            }
                                            if(d2<d1){
                                                callback('结束日期不能小于开始日期');
                                            }else{
                                            callback();
                                            };
                                                }, trigger:'blur'}
                                        ]">
                            <el-date-picker
                                    v-model="addDate.enddate"
                                    type="date"
                                    placeholder="结束时间"
                                    @change="zqchange2()"
                                    :disabled="lydisabled"
                            >
                            </el-date-picker>
                        </el-form-item>
                </el-col>
                </el-col>
            </el-form-item>
            <!--租金-->
            <div v-for="(item, index) in addDate.zujinList">
                <el-row>
                    <el-col :span="9"style="width:550px;">
                        <el-form-item :label="'租期' + (index + 1)"
                                      :key="item.key"
                                      required
                        >
                            <el-col :span="11">
                                <el-form-item  :prop="'zujinList.' + index + '.startdate' " :rules="[
                                {
                                    required: true, message: '不能为空'
                                }
                                ]"
                                >
                                    <el-date-picker type = "date"
                                                    placeholder="开始时间"
                                                    :disabled="lydisabled"
                                                    v-model="item.startdate">
                                    </el-date-picker>
                                </el-form-item>
                            </el-col>
                            <el-col :span="11">
                                <el-form-item :prop="'zujinList.' + index + '.enddate' " :rules="[
                                {  required: true,validator:
                                (rule,value,callback)=>{
                                    var d1= new Date( addDate.zujinList[index].startdate);
                                    var d2= new Date(value);
                                    if(value==null){
                                        callback('不能为空');
                                    }
                                    if(d2<d1){
                                        callback('结束日期不能小于开始日期');
                                    }else{
                                    callback();
                                    };
                                        }, trigger:'blur'}
                                ]"
                                >
                                    <el-date-picker  type = "date"
                                                     :disabled="lydisabled"
                                                     placeholder="结束时间" v-model="item.enddate">
                                    </el-date-picker>
                                </el-form-item>
                            </el-col>
                        </el-form-item>
                    </el-col>
                    <el-col :span="2" :pull="1" style="width: 130px;margin-left:-20px;">
                        <el-form-item label="月租金" label-width="55px"  :prop="'zujinList.' + index + '.yuezujin'" :rules="[{
                                    required: true, message: '不能为空'
                                },{ type: 'number', message: '必须为数字'}]"
                        >
                            <el-input v-model.number="item.yuezujin"
                                      :disabled="lydisabled"
                                      class="pulll10" placeholder="租金/元"
                                      @change="perPrice(index,item.yuezujin)"
                            ></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="2" :pull="1" style="width: 94px;margin-left:-10px;">
                        <el-form-item label="单价" label-width="40px"  :prop="'zujinList.' + index + '.price' " :rules="[{
                                    required: true, message: '不能为空'
                                },{ type: 'number', message: '必须为数字'}]">
                            <el-input v-model.number="item.price"
                                      :disabled="lydisabled"
                                      class="pulll10" placeholder="单价"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="3" :pull="1" style="margin-left:-10px;">
                        <el-form-item label="递增方式" label-width="70px">
                            <el-input v-model="item.dizengliang" class="pulll10" placeholder=""></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="2"  style="width: 70px;margin-left:-50px;">
                        <el-select v-model="addDate.zujinList[index].dizengfangshi"   placeholder="">
                            <el-option
                                    v-for="item in options"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                            </el-option>
                        </el-select>
                    </el-col>
                    <el-col :span="2">
                        <el-button style="margin-left:6px;" v-show="editVisible" @click.prevent="removeRentItem(item)">删除</el-button>
                    </el-col>
                </el-row>
            </div>
            <el-form-item>
                <el-button v-show="editVisible"  @click="addRentItem">新增租期</el-button>
            </el-form-item>
            <!--付款方式-->
            <div  v-for="(item, index) in addDate.fukuanFangshiList">
                <el-row :gutter="5">
                    <el-col :span="14" style="width:550px;">
                        <el-form-item label="付款方式"
                                      :key="item.key"
                                      required
                        >
                            <el-col :span="11">
                                <el-form-item  :prop="'fukuanFangshiList.' + index + '.startdate'" :rules="{
                                            required: true, message: '不能为空'
                                        }" >
                                    <el-date-picker type = "date" placeholder="开始时间" v-model="item.startdate" :disabled="lydisabled">
                                    </el-date-picker>
                                </el-form-item>
                            </el-col>
                            <el-col :span="11">
                                <el-form-item
                                        :prop="'fukuanFangshiList.' + index + '.enddate'"
                                        :rules="[
                                            {  required: true,validator:
                                            (rule,value,callback)=>{
                                                var d1= new Date( addDate.fukuanFangshiList[index].startdate);
                                                var d2= new Date(value);
                                                if(value==null){
                                                    callback('不能为空');
                                                }
                                                if(d2<d1){
                                                    callback('结束日期不能小于开始日期');
                                                }else{
                                                callback();
                                                };
                                                    }, trigger:'blur'}
                                            ]">
                                    <el-date-picker type = "date" placeholder="结束时间" v-model="item.enddate" :disabled="lydisabled">
                                    </el-date-picker>
                                </el-form-item>
                            </el-col>
                        </el-form-item>
                    </el-col>
                    <el-col :span="1" :pull="1" style="width: 90px;">
                        <el-form-item label="押" label-width="20px" :prop="'fukuanFangshiList.' + index + '.yajinyue'" :rules="[{
                                    required: true, message: '不能为空'
                                },{ type: 'number', message: '必须为数字'}]">
                            <el-input v-model.number="item.yajinyue"
                                      @change="yajin(index,item.yajinyue)"
                                      :disabled="lydisabled"
                                      placeholder="押几"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="1" :pull="1" style="margin-left: 15px;width: 90px;">
                        <el-form-item label="付" label-width="20px" :prop="'fukuanFangshiList.' + index + '.zujinyue'" :rules="[{
                                    required: true, message: '不能为空'
                                },{ type: 'number', message: '必须为数字'}]">
                            <el-input v-model.number="item.zujinyue"
                                      :disabled="lydisabled"
                                      placeholder="付几"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="2">
                        <el-button v-show="editVisible"  @click.prevent="removePayItem(item)">删除</el-button>
                    </el-col>
                </el-row>
            </div>
            <el-form-item>
                <el-button v-show="editVisible"  @click="addPayItem">新增付款方式</el-button>
            </el-form-item>
            <!--押金 总应付租金 合同佣金-->
            <el-row>
                <el-col :span="8">
                    <el-form-item label="押金" prop="yajin" required>
                        <el-input v-model.number="addDate.yajin"
                                  :disabled="lydisabled"
                                  placeholder="押金"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="合同佣金" prop="hetongyongjin" >
                        <el-input v-model.number="addDate.hetongyongjin"
                                  placeholder="合同佣金" :disabled="lydisabled" required></el-input>
                    </el-form-item>
                </el-col>
            </el-row>
            <!--提前几天付款 押金付款日期-->
            <el-row>
                <el-col :span="8">
                    <el-form-item label="提前几天付款" prop="tiqianfukuantian" required>
                        <el-input placeholder="提前几天付款"
                                  :disabled="lydisabled"
                                  v-model.number="addDate.tiqianfukuantian" style="width: 100%;"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="押金付款日期" prop="yajinfukuanriqi" required>
                        <el-date-picker
                                type="date"
                                placeholder="选择日期"
                                :disabled="lydisabled"
                                v-model="addDate.yajinfukuanriqi"
                                style="width: 100%;"
                        >
                        </el-date-picker>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="实际月租金" prop="actualrent" >
                        <el-input v-model.number="addDate.actualrent"
                                  :disabled="lydisabled"
                                  placeholder="实际月租金"></el-input>
                    </el-form-item>
                </el-col>
            </el-row>
            <!--提前几天付款 押金付款日期-->
            <el-row>
                <el-col :span="8">
                    <el-form-item label="水、电能源押金" prop="ispaydeposit" required>
                        <el-radio-group v-model="addDate.ispaydeposit" @change="isPayDepositChange"  :disabled="lydisabled">
                            <el-radio :label="1">需缴纳</el-radio>
                            <el-radio :label="2">无需缴纳</el-radio>
                        </el-radio-group>
                    </el-form-item>
                </el-col>
                <el-col :span="8" v-if="addDate.ispaydeposit==1">
                    <el-form-item label="能源押金金额" prop="nengyuanyajin"  :rules=" [
                        { required: true, message: '不能为空' },
                        { type: 'number', message: '必须为数字'},
                    ]"     required>
                        <el-input v-model.number="addDate.nengyuanyajin"
                                  :disabled="lydisabled"
                                  placeholder="能源押金金额"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="税金" prop="housetax" required>
                        <el-radio-group v-model="addDate.housetax"    :disabled="lydisabled">
                            <el-radio :label="1">含</el-radio>
                            <el-radio :label="2">不含</el-radio>
                        </el-radio-group>
                    </el-form-item>
                </el-col>
            </el-row>
            <!--首期支付 二期支付 三期支付-->
            <el-row>
                <el-col :span="8">
                    <el-form-item label="首期租金日期" prop="shouqifukuanri" required>
                        <el-date-picker
                                type="date"
                                v-model="addDate.shouqifukuanri"
                                :disabled="lydisabled"
                                placeholder="首期租金日期"
                                style="width: 100%"
                        ></el-date-picker>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="二期租金日期" prop="erqifukuanri">
                        <el-date-picker
                                type="date"
                                v-model="addDate.erqifukuanri"
                                :disabled="lydisabled"
                                placeholder="二期租金日期"
                                style="width: 100%"
                        ></el-date-picker>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="三期租金日期" prop="sanqifukuanri" >
                        <el-date-picker
                                tpye="date"
                                v-model="addDate.sanqifukuanri"
                                :disabled="lydisabled"
                                placeholder="三期租金日期"
                                style="width: 100%"
                        ></el-date-picker>
                    </el-form-item>
                </el-col>
            </el-row>
            <!--各种费用-->
            <!--<el-form-item label="费用" prop="jiafangfeiyong" >
                <el-checkbox-group  v-model="addDate.jiafangfeiyong">
                    <el-checkbox label="（一）物业费"></el-checkbox>
                    <el-checkbox label="（二）取暖费"></el-checkbox>
                    <el-checkbox label="（三）制冷"></el-checkbox>
                    <el-checkbox label="（四）发票"></el-checkbox>
                    <el-checkbox label="（五）其它"></el-checkbox>
                </el-checkbox-group>
            </el-form-item>-->
            <!--补充条款1111-->
            <el-col :span="24">
                <el-form-item label="补充条款"  prop="jiafangfeiyong">
                    <el-input type="textarea" :rows="12" :disabled="lydisabled"  v-model="addDate.jiafangfeiyong"></el-input>
                </el-form-item>
            </el-col>
        </el-form>
    </el-row>
</template>
<style>
    .pulll10{
        margin-left: -10px;
    }
</style>
<script>
    import {saleEditable} from '../../global'
    export default {
        data() {
            return {
                editVisible:true,
                lydisabled:false,

                options:[
                    {
                        value: '1',
                        label: '%'
                    }, {
                        value: '2',
                        label: '元'
                    },
                ],
                editDateRules :{
                    startdate: [
                        { required: true, message: '不能为空' }
                    ],zuqistartddate: [
                        { required: true, message: '不能为空' }
                    ],zuqienddate: [
                        { required: true, message: '不能为空' }
                    ],yuezujin: [
                        { required: true, message: '不能为空' },
                    ],price: [
                        { required: true, message: '不能为空' }
                    ],fukuanstartdate: [
                        { required: true, message: '不能为空' }
                    ],fukuanenddate: [
                        { required: true, message: '不能为空' }
                    ],yajinyue: [
                        { required: true, message: '不能为空' },
                        { type: 'number', message: '必须为数字'},
                    ],zujinyue: [
                        { required: true, message: '不能为空' },
                        { type: 'number', message: '必须为数字'},
                    ],yajin: [
                        { required: true, message: '不能为空' },
                        { type: 'number', message: '必须为数字'},
                    ],
//                    yingfuzongzujin: [
//                        { required: true, message: '不能为空' },
//                        { type: 'number', message: '必须为数字'},
//                    ],
                    hetongyongjin: [
//                        { required: true, message: '不能为空' },
                        { type: 'number', message: '必须为数字'},
                    ],tiqianfukuantian: [
                        { required: true, message: '不能为空' },
                        { type: 'number', message: '必须为数字'},
                    ],yajinfukuanriqi: [
                        { required: true, message: '不能为空' },
                    ],
//                    shoufangdate: [
//                        { required: true, message: '不能为空' }
//                    ],
//                    qianyuedate: [
//                        { required: true, message: '不能为空' }
//                    ],
                    shouqifukuanri: [
                        { required: true, message: '不能为空' }
                    ],
//                    erqifukuanri: [
//                        { required: true, message: '不能为空' }
//                    ],
//                    sanqifukuanri: [
//                        { required: true, message: '不能为空' }
//                    ],
//                    jiafangfeiyong: [
//                        { required: true, message: '不能为空' }
//                    ],
//                    actualrent: [
//                    { required: true, message: '不能为空' }
//                ],
                },
            }
        },
        props:['addDate','property'],   //这块是父页面和子页面出数据时声明（定义的）
        methods: {
            onSubmit() {

            },
            zqchange1(){
                if(this.$route.path=='/saleContract/add'){
                    //时间一改变就对zujinList的日期进行填充
                    //先获取的开始和结束时间
                    var d1=new Date(this.addDate.startdate);
                    var d2=new Date(this.addDate.enddate);
                    var cha = parseInt(d2.getFullYear())-parseInt(d1.getFullYear());
                    if(cha<1){
                        this.addRentItem();
                        return
                    }
                    //每次变数据清空当前的列表
                    this.addDate.zujinList.splice(0,this.addDate.zujinList.length);//清空数组
                    for(var i =0;i<cha;i++){
                        this.addRentItem();
                        if(i==0){
                            this.addDate.zujinList[i].startdate = d1.setFullYear(d1.getFullYear());
                            let dx = new Date(this.addDate.zujinList[i].startdate);//将时间复制，然后加一年
                            this.addDate.zujinList[i].enddate = dx.setFullYear(dx.getFullYear()+1);
                            let dy = new Date(this.addDate.zujinList[i].enddate);//将时间复制，然后减一天
                            this.addDate.zujinList[i].enddate = dy.setDate(dx.getDate()-1);
                        }
                        if(i>0){
                            this.addDate.zujinList[i].startdate = d1.setFullYear(d1.getFullYear()+1);
                            let dx = new Date(this.addDate.zujinList[i].startdate);//将时间复制，然后加一年
                            this.addDate.zujinList[i].enddate = dx.setFullYear(dx.getFullYear()+1);
                            let dy = new Date(this.addDate.zujinList[i].enddate);//将时间复制，然后减一天
                            this.addDate.zujinList[i].enddate = dy.setDate(dx.getDate()-1);
                        }
                    }
                }
            },
            zqchange2(){
                if(this.$route.path=='/saleContract/add') {
                    //时间一改变就对zujinList的日期进行填充
                    //先获取的开始和结束时间
                    var d1 = new Date(this.addDate.startdate);
                    var d2 = new Date(this.addDate.enddate);
                    var cha = parseInt(d2.getFullYear()) - parseInt(d1.getFullYear());
                    if(cha<1){
                        this.addRentItem();
                        return
                    }
                    //每次变数据清空当前的列表
                    this.addDate.zujinList.splice(0, this.addDate.zujinList.length);//清空数组
                    for (var i = 0; i < cha; i++) {
                        this.addRentItem();
                        if (i == 0) {
                            this.addDate.zujinList[i].startdate = d1.setFullYear(d1.getFullYear());
                            let dx = new Date(this.addDate.zujinList[i].startdate);//将时间复制，然后加一年
                            this.addDate.zujinList[i].enddate = dx.setFullYear(dx.getFullYear() + 1);
                            let dy = new Date(this.addDate.zujinList[i].enddate);//将时间复制，然后减一天
                            this.addDate.zujinList[i].enddate = dy.setDate(dx.getDate() - 1);
                        }
                        if (i > 0) {
                            this.addDate.zujinList[i].startdate = d1.setFullYear(d1.getFullYear() + 1);
                            let dx = new Date(this.addDate.zujinList[i].startdate);//将时间复制，然后加一年
                            this.addDate.zujinList[i].enddate = dx.setFullYear(dx.getFullYear() + 1);
                            let dy = new Date(this.addDate.zujinList[i].enddate);//将时间复制，然后减一天
                            this.addDate.zujinList[i].enddate = dy.setDate(dx.getDate() - 1);
                        }
                    }
                }
            },
            isPayDepositChange(){
                //只要业主类型发生改变，那么我就将变量初始化
                if(this.$route.path=='/saleContract/add'||this.addDate.ispaydeposit!=this.addDate.ispaydeposit2){
                    this.addDate.nengyuanyajin = '';
                }
            },
            //计算单价
            perPrice(index,value){
                //1.先计算面积综合
                var areaToal = 0;
                this.property.xsOffice.forEach((property,index)=>{
                    areaToal += property.qianyuemianji;
                })
                //2.计算单价
                var perPrice = (value*12/365/areaToal).toFixed(2);
                this.addDate.zujinList[index].price = parseFloat(perPrice);
                this.yajin();
            },
            yajin(index,value){
                this.addDate.yajin = this.addDate.fukuanFangshiList[0].yajinyue*(this.addDate.zujinList[0].yuezujin)
            },
            //增加免租期
            addFreeItem() {
                this.addDate.mianzuqiList.push({
                    startdate:'',//免租开始
                    enddate:'',//免租结束
                    key: Date.now()
                });
            },
            valid(){
                this.$refs['addDateForm'].validate((valid) => {
                    this.addDate.flag = valid;
                });
            },
            //移除免租期
            removeFreeItem(item) {
                var index = this.addDate.mianzuqiList.indexOf(item)
                if (index !== -1) {
                    this.addDate.mianzuqiList.splice(index, 1)
                }
            },
            //增加租期租金
            addRentItem() {
                this.addDate.zujinList.push({
                    startdate:'',
                    enddate:'',
                    yuezujin:'',
                    price:'',
                    dizengfangshi:'',
                    dizengliang:'',
                    key: Date.now()
                });
            },
            //移除租期租金
            removeRentItem(item) {
                var index = this.addDate.zujinList.indexOf(item)
                if (index !== -1) {
                    this.addDate.zujinList.splice(index, 1)
                }
            },
            //新增付款方式
            addPayItem() {
                this.addDate.fukuanFangshiList.push({
                    startdate:'',//开始
                    enddate:'',//结束
                    yajinyue:'',
                    zujinyue:'',
                });
            },
            //移除付款方式
            removePayItem(item) {
                var index = this.addDate.fukuanFangshiList.indexOf(item)
                if (index !== -1) {
                    this.addDate.fukuanFangshiList.splice(index, 1)
                }
            },
            disabledInput(){
                this.editVisible=false;
            },
        },
        mounted(){
            //审核页面input禁用
            if(this.$route.path=='/saleContract/review'){
                this.editVisible   =false;
            }
            if(this.$route.path=='/saleContract/see'){
               this.editVisible   =false;
            }
            if(this.$route.path=='/saleContract/edit'&&saleEditable.indexOf(parseInt(this.$route.query.status))<0){
                this.lydisabled = true;
                this.editVisible   =false;
            }
        }
    }
</script>
