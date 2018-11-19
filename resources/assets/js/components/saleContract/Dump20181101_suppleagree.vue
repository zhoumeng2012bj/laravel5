<template>
    <div class="whole">
        <h2 class="tc">补充协议</h2>
        <br>
        <!--<span class="tc f22">{{chengjiaoban}}</span>-->
        <p>甲方：<input type="text" style="width: 480px" disabled v-model="renter.chuzufang"/></p>
        <p>乙方：<input type="text" style="width: 450px" disabled value=""/></p>
        <p>丙方：<input type="text" style="width: 450px" disabled v-model="renter.jujianfang"/></p>

        <p>
            <span
                    v-for="(item,index) in property.xsOffice"
                    :key="item.chanquanzhenghao"
                    style="display:inline;"
            >
        <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;兹有甲、乙、丙三方于_______年____月____日就北京市<input type="text" v-model="item.quyu" style="width:50px;">区（县）<u><span style="display: initial;" v-if="item.weizhi">{{item.weizhi}}</span><span style="display: initial;" v-else><input type="text" style="width:90px;"><input type="text" style="width:260px;"></span></u> 房屋签署《北京市房屋租赁合同》（以下简称“合同”）
        </span>
            
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;现甲、乙、丙三方就合同履行达成如下事宜：

            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="display: inline;">一、甲方承诺在租赁合同期限内给予乙方<input type="text" v-model="da" style="width:70px;">天的免租期。</span>
            <span v-for="(item,index) in addDate.mianzuqiList"
                  style="display:inline"
            >自<u>&nbsp;&nbsp;{{year(item.startdate)?year(item.startdate):''}}&nbsp;&nbsp;</u>年
            <u>&nbsp;&nbsp;{{month(item.startdate)?month(item.startdate):''}}&nbsp;&nbsp;</u>月
            <u>&nbsp;&nbsp;{{day(item.startdate)?day(item.startdate):''}}&nbsp;&nbsp;</u>日至
            <u>&nbsp;&nbsp;{{year(item.enddate)?year(item.enddate):''}}&nbsp;&nbsp;</u>年
            <u>&nbsp;&nbsp;{{month(item.enddate)?month(item.enddate):''}}&nbsp;&nbsp;</u>月
            <u>&nbsp;&nbsp;{{day(item.enddate)?day(item.enddate):''}}&nbsp;&nbsp;</u>日止。免租期内乙方不支付租金，以便于乙方进行装饰装修及办理入住手续等事宜。免租期内物业管理费、供暖费由 □甲方 □乙方承担。如乙方提前终止合同，除承担违约责任外，另需赔付甲方给予的全部免租期租金。
            </span>
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;二、本协议自生效之日，属于合同不可分割的部分，与合同具有同等法律效力，本协议内容与合同内容冲突时，以本协议为准。
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;三、本协议一式叁份，甲、乙、丙三方签字或盖章后生效，甲、乙、丙三方各持一份，具有同等法律效力。
				</p>
				<p>
						<b> 甲方：<input type="text" name="" style="width:175px;font-size:10px" value=""></b>　<br>
						代理人：<input type="text" name="" style="width:217px;font-size:10px" value=""></span>
						<br>联系方式：<input type="text" name="" style="width:217px;" disabled value=""><br>

						<font class="datemar" style="margin-left: 148px;">_______年____月____日</font>&nbsp;&nbsp;
				</p>
				<p style="margin-top: 20px;line-height: 30px;height: 120px;">
						<b> <span style="display: inherit;">乙方：<input type="text" name="" style="width:175px;font-size:10px" value=""></span></b>　<br>
						<span style="display: initial;">代理人：<input type="text" name="" style="width:217px" value=""></span>
						<br><span style="display: initial;">联系方式：<input type="text" name="" style="width:217px;font-size:10px" value=""></span><br>

						<font style="margin-left: 149px;" >_______年____月____日</font><br>
						<br>
				</p>
				<p>
						<b> 丙方：<input type="text" name="" style="width:178px;font-size:10px" value=""></b><br>
				代理人：<input type="text" name="" style="width:149px;font-size:10px" value="">
						<br>联系方式：<input type="text" name="" style="width:218px;font-size:10px" value=""><br>
						<font class="datemar" style="margin-left: 148px;">_______年____月____日</font>
				</p>
    </div>
</template>
<style>

    .whole{
        margin: auto;
        top: 0;
        right: 0;
        left:0;
        bottom: 0;
        width: 92%;
        height: 20%;
    }
    .tc{text-align:center;}
    .whole h1{
        font-size:30px;
    }
    .whole span {
        display: block;}
    .f22{
        font-size: 14px;
    }
    p{
        font-size: 14px; text-align:left;
        line-height: 2;
        display: block;
    }
    .whole input{border: none;border-bottom: 1px solid#333333;outline: none; font-size:14px!important;text-align:center}
    .whole u{
        font-size:14px!important;
    }
    .whole input[disabled]{
        background-color:white;
    }

</style>
<script>
    import {getSaleContractInfo} from '../../api/api';;
    import HistoryBuchong from './HistoryBuchong.vue';
    import ListJieyue from './ListJieyue.vue';
    export default{
        components:{
            HistoryBuchong,
            ListJieyue,
        },
        data(){
            return {
                chengjiaoban:'',
                msg01:'、丙三',
                msg02:'丙方提供居间服务等事宜，',
                msg03:'、丙',
                msg04:'叁',
                free01:'及免租期',
                free02:'二',
                free03:'三',

                historyBuchong:false,
                listJieyue:false,
                hetongtype:1,//合同类型
                free:0,
                property:{
                    flag:null,
                    xsOffice: [{
                        omcId:null,
                        loupanOmcId:null,
                        loudongOmcId:null,
                        loupanName:null,
                        loudongName:null,
                        fanghao:null,
                        weizhi: null,
                        chanquanzhenghao: null,
                        jianzhumianji: null,
                        qianyuemianji: null,

                        leixing: null,
                        hetongid:null,
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
                    chengzufang:'',
                    jujianfang:'',
                    jujianfangtype:'',
                    zuhuleixing:1,
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
                },
                addDate: {
                    iscompletefrzj:'',
                    buchongTiaokuanList:[
                        {
                            content:'',
                        }
                    ],//补充条款
                    dikoujine:'',//合同金额
                    startdate:'',//租期开始时间
                    enddate:'',//租期结束时间
                    xinjianshoufangdate: '',//收房日期
                    xinjianqianyuedate: '',//签约日期
                    shoufangdate: '',//收房日期
                    qianyuedate: '',//签约日期
                    mianzufangshi: [],//免租方式
                    mianzuqiList: [{
                        startdate:'',//免租开始
                        enddate:'',//免租结束
                    }],
                    fukuanFangshiList:[{
                        startdate:'',//开始
                        enddate:'',//结束
                        yajinyue:'',
                        zujinyue:'',
                    }],
                    actualrent:'',
                    yajin:'',//押金
                    yingfuzongzujin:'',//总租金
                    hetongyongjin:'',//佣金
                    tiqianfukuantian:'',//提前付款天数
                    yajinfukuanriqi:'',//押金付款日
                    shouqifukuanri:'',//首期租金付款日
                    erqifukuanri:'',//二期付款
                    sanqifukuanri:'',//三期付款
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
                    jiafangfeiyong:[],
                    ispaydeposit:1,
                    nengyuanyajin:'',
                },
                quyu:null,
                nian:null,
                yue:null,
                ri:null,
                da:null,
            }
        },
        methods:{
            contractTitle(){
                switch (this.renter.chengzufang){
                    case '华溯商贸':
                        this.chengjiaoban = '华溯商贸成交版'
                        break;
                    case '幼狮科技':
                        this.chengjiaoban = '幼狮空间成交版'
                        break;
                    case '彭亮':
                        this.chengjiaoban = '幼狮空间成交版'
                        break;
                }
            },
            toDecimal(x) {
                var f = parseFloat(x);
                if (isNaN(f)) {
                    return false;
                }
                var f = Math.round(x * 100) / 100;
                var s = f.toString();
                var rs = s.indexOf('.');
                if (rs < 0) {
                    rs = s.length;
                    s += '.';
                }
                while (s.length <= rs + 2) {
                    s += '0';
                }
                return s;
            },
            daxie(money) {
                if(!money){
                    return '';
                }
                //汉字的数字
                var cnNums = new Array('零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖');
                //基本单位
                var cnIntRadice = new Array('', '拾', '佰', '仟');
                //对应整数部分扩展单位
                var cnIntUnits = new Array('', '万', '亿', '兆');
                //对应小数部分单位
                var cnDecUnits = new Array('角', '分', '毫', '厘');
                //整数金额时后面跟的字符
                var cnInteger = '整';
                //整型完以后的单位
                var cnIntLast = '元';
                //最大处理的数字
                var maxNum = 999999999999999.9999;
                //金额整数部分
                var integerNum;
                //金额小数部分
                var decimalNum;
                //输出的中文金额字符串
                var chineseStr = '';
                //分离金额后用的数组，预定义
                var parts;
                if (money == '') { return ''; }
                money = parseFloat(money);
                if (money >= maxNum) {
                    //超出最大处理数字
                    return '';
                }
                if (money == 0) {
                    chineseStr = cnNums[0] + cnIntLast + cnInteger;
                    return chineseStr;
                }
                //转换为字符串
                money = money.toString();
                if (money.indexOf('.') == -1) {
                    integerNum = money;
                    decimalNum = '';
                } else {
                    parts = money.split('.');
                    integerNum = parts[0];
                    decimalNum = parts[1].substr(0, 4);
                }
                //获取整型部分转换
                if (parseInt(integerNum, 10) > 0) {
                    var zeroCount = 0;
                    var IntLen = integerNum.length;
                    for (var i = 0; i < IntLen; i++) {
                        var n = integerNum.substr(i, 1);
                        var p = IntLen - i - 1;
                        var q = p / 4;
                        var m = p % 4;
                        if (n == '0') {
                            zeroCount++;
                        } else {
                            if (zeroCount > 0) {
                                chineseStr += cnNums[0];
                            }
                            //归零
                            zeroCount = 0;
                            chineseStr += cnNums[parseInt(n)] + cnIntRadice[m];
                        }
                        if (m == 0 && zeroCount < 4) {
                            chineseStr += cnIntUnits[q];
                        }
                    }
                    chineseStr += cnIntLast;
                }
                //小数部分
                if (decimalNum != '') {
                    var decLen = decimalNum.length;
                    for (var i = 0; i < decLen; i++) {
                        var n = decimalNum.substr(i, 1);
                        if (n != '0') {
                            chineseStr += cnNums[Number(n)] + cnDecUnits[i];
                        }
                    }
                }
                if (chineseStr == '') {
                    chineseStr += cnNums[0] + cnIntLast + cnInteger;
                } else if (decimalNum == '') {
                    chineseStr += cnInteger;
                }
                return chineseStr;
            },
            daxie2(number){
                let arr = [ '一', '二', '三', '四', '五', '六', '七', '八', '九'];
                return arr[number];
            },
            year(riqi){
                if(riqi!=null){
                    return new Date(riqi).getFullYear();
                }else{
                    return '';
                }
            },
            month(riqi){
                if(riqi!=null){
                    if(new Date(riqi).getMonth()+1 <10 ){
                        return '0'+(new Date(riqi).getMonth()+1);
                    }else{
                        return new Date(riqi).getMonth()+1
                    }
                }else{
                    return '';
                }
            },
            day(riqi){
                if(riqi!=null){
                    if(new Date(riqi).getDate()+1 <10 ){
                        return '0'+(new Date(riqi).getDate());
                    }else{
                        return new Date(riqi).getDate()
                    }
                }else{
                    return '';
                }

            },
            //根据url得到的合同ID，来获取数据
            getSaleContract(id){
                getSaleContractInfo(id).then((res)=>{
                    if(res.data.code=='200'){
                        //把数据分别赋值给三个组件的变量
                        //console.log(res.data)
                        this.fuzhi(res);

                    }else {
                        this.$message({
                            message: '获取数据失败',
                            type: 'error'
                        });
                    }
                })
            },
            intToChinese( str ) {
                str = str+'';
                var len = str.length-1;
                var idxs = ['','十','百','千','万','十','百','千','亿','十','百','千','万','十','百','千','亿'];
                var num = ['零','壹','贰','叁','肆','伍','陆','柒','捌','玖'];
                return str.replace(/([1-9]|0+)/g,function( $, $1, idx, full) {
                    var pos = 0;
                    if( $1[0] != '0' ){
                        pos = len-idx;
                        if( idx == 0 && $1[0] == 1 && idxs[len-idx] == '十'){
                            return idxs[len-idx];
                        }
                        return num[$1[0]] + idxs[len-idx];
                    } else {
                        var left = len - idx;
                        var right = len - idx + $1.length;
                        if( Math.floor(right/4) - Math.floor(left/4) > 0 ){
                            pos = left - left%4;
                        }
                        if( pos ){
                            return idxs[pos] + num[$1[0]];
                        } else if( idx + $1.length >= len ){
                            return '';
                        }else {
                            return num[$1[0]]
                        }
                    }
                });
            },
            //获取区域的中
            getquyu(str1){
                var str1 = str1;
                var str2 = "区";
                var s = str1.indexOf(str2);
                var str3 = str1.substr(3,s);
                //alert(111);
                //alert(str3);
                return(str3);
            },
            fuzhi(res){
                //console.log(2222);
                //console.log(res.data.data);
                this.id = res.data.data.id;
                this.property.xsOffice = res.data.data.xsOffice;
                if(res.data.data.chengzuren.length>0){
                    this.renter.chengzuren = res.data.data.chengzuren;
                }
                //this.renter.chengzuren = res.data.data.chengzuren;
                this.renter.chengzufang = res.data.data.chengzufang;
                this.contractTitle();
                this.renter.chuzufang = res.data.data.chuzufang?res.data.data.chuzufang+'有限公司':'';
                this.renter.jujianfangtype = res.data.data.jujianfangtype;
                this.renter.jujianfang = res.data.data.jujianfang;
                this.renter.shoukuanren = res.data.data.shoukuanren;
                this.renter.kaihuhang = res.data.data.kaihuhang;
                this.renter.zhanghao = res.data.data.zhanghao;
                this.renter.zuhuleixing = res.data.data.zuhuleixing;
                this.renter.shoukuanren = res.data.data.shoukuanren;
                this.renter.zhanghao = res.data.data.zhanghao;
                this.renter.qianyuerenName = res.data.data.qianyuerenName;
                this.renter.qianyuerenTel = res.data.data.qianyuerenTel;
                this.renter.qianyuerenSex = res.data.data.qianyuerenSex;
                this.renter.qianyuerenId = res.data.data.qianyuerenId;
                this.hetongtype = res.data.data.hetongtype;
                if(this.hetongtype==1){
                    this.msg01='两';
                    this.msg02='';
                    this.msg03='';
                    this.msg04='两';
                }
                this.addDate.actualrent = res.data.data.actualrent;
                if(res.data.data.startdate != ''){
                    this.addDate.startdate = res.data.data.startdate;
                }
                if(res.data.data.enddate != ''){
                    this.addDate.enddate = res.data.data.enddate;
                }
                if(res.data.data.shoufangdate != ''){
                    this.addDate.shoufangdate = res.data.data.shoufangdate;
                }
                if(res.data.data.qianyueDate != ''){
                    this.addDate.qianyueDate = res.data.data.qianyueDate;
                }
                this.addDate.mianzufangshi = res.data.data.mianzufangshi;
                this.addDate.mianzuqiList = res.data.data.mianzuqiList;
                if(this.addDate.mianzuqiList.length==0){
                    this.free01 = '';
                    this.free02 = '一';
                    this.free03 = '二';
                }

                this.addDate.fukuanFangshiList = res.data.data.fukuanFangshiList;
                this.addDate.fukuanFangshiList.sort(function(a,b){
                    return a.startdate-b.startdate})
                this.addDate.yajin = res.data.data.yajin;
                this.addDate.yingfuzongzujin = res.data.data.yingfuzongzujin;
                this.addDate.hetongyongjin = res.data.data.hetongyongjin;
                this.addDate.tiqianfukuantian = res.data.data.tiqianfukuantian;
                if(res.data.data.yajinfukuanriqi != ''){
                    this.addDate.yajinfukuanriqi = res.data.data.yajinfukuanriqi;
                }
                this.addDate.shouqifukuanri = res.data.data.shouqifukuanri;
                this.addDate.erqifukuanri = res.data.data.erqifukuanri;
                this.addDate.sanqifukuanri = res.data.data.sanqifukuanri;
                this.addDate.iscompletefrzj = res.data.data.iscompletefrzj;
                if(res.data.data.zujinList != ''){
                    this.addDate.zujinList = res.data.data.zujinList;
                    this.addDate.zujinList.sort(function(a,b){
                        return a.startdate-b.startdate})
                }
                this.addDate.checkList = res.data.data.checkList;
                this.addDate.jiafangfeiyong = res.data.data.jiafangfeiyong;
                this.addDate.ispaydeposit = res.data.data.ispaydeposit;
                this.addDate.nengyuanyajin = res.data.data.nengyuanyajin;
                this.nian = res.data.data.nian;
                this.yue = res.data.data.yue;
                this.ri = res.data.data.ri;
                this.da = res.data.data.da;
                //alert(res.data.data.xsOffice[0].weizhi);
                var str = res.data.data.xsOffice[0].weizhi;
                this.quyu = this.getquyu(str);
            },
        },
        mounted(){
            //
            //获取合同的详细信息
            this.getSaleContract(this.$route.query);
            document.title = '合同编号：'+this.$route.query.bianhao;
            function  hello() {
                window.print()
            }
            if(this.$route.query.isdump==1){
                this.historyBuchong = false;
                setTimeout(hello,1000);
            }else if(this.$route.query.isdump==2){
                this.historyBuchong = false;
            }else{
                this.historyBuchong = true;
                this.listJieyue = true;
            }
        }
    }
</script>