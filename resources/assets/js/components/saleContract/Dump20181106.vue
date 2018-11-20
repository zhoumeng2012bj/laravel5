<template>
    <div class="whole">
        <h2 class="tc">北京市房屋租赁合同</h2>
        <br>
        <p>甲方（出租方）：<input type="text" style="width: 480px" disabled v-model="renter.chuzufang"/></p>
        <p>乙方（承租方）：<input type="text" style="width: 450px" disabled value=""/></p>
        <p>丙方（资产管理方）：<input type="text" style="width: 450px" disabled/></p>

        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;依据《中华人民共和国合同法》及有关法律、法规的规定，甲、乙、丙三方在平等、自愿的基础上，就乙方承租甲方房屋，丙方对甲方房屋资产进行出租管理等事宜，经各方友好协商一致，签订本合同以资信守。</p>
        <p><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;第一条  房屋基本情况</b>
            <span
                    v-for="(item,index) in property.xsOffice"
                    :key="item.chanquanzhenghao"
                    style="display:inline;"
            >
        <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（{{daxie2(index)}}）房屋坐落于北京市<input type="text" v-model="item.quyu" style="width:50px;">区（县）<u><span style="display: initial;" v-if="item.weizhi">{{item.weizhi}}</span><span style="display: initial;" v-else><input type="text" style="width:280px;"><input type="text" style="width:80px;"></span></u>，承租区域建筑面积<input type="text"  style="width:50px;" v-model="item.jianzhumianji">平方米（最终以房屋所有权证标注的建筑面积为准），产权证编号： <u>{{item.chanquanzhenghao?item.chanquanzhenghao:'___________________________________'}}</u>  。
        </span>
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（{{daxie2(property.xsOffice.length)}}）甲方保证出租的房屋权属证明真实有效，房屋设施符合出租条件。<br>
            <b>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;第二条  房屋租赁情况
            </b>
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;租赁用途：
            <input type="text" style="width: 70px;" value="办公">，房屋规划用途：
            <input type="text" style="width: 70px;" value="">
            ，根据规划用途及政府相关规定可以进行工商注册的房屋，甲方向乙方提供其身份证及房屋所有权证复印件，乙方自行到相关部门办理工商注册事宜，并由乙方自行承担注册不成功之风险。

            <br>
            <b>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;第三条  租赁期限
            </b>

            <!-- <br v-if="addDate.mianzuqiList.length>0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="display: inline;" v-if="addDate.mianzuqiList.length>0">（一）甲方承诺在租赁合同期限内给予乙方<input type="text" v-model="da" style="width:70px;">天的免租期。</span>
            <span v-if="addDate.mianzuqiList" v-for="(item,index) in addDate.mianzuqiList"
                  style="display:inline"
            >自<u>&nbsp;&nbsp;{{year(item.startdate)?year(item.startdate):''}}&nbsp;&nbsp;</u>年
            <u>&nbsp;&nbsp;{{month(item.startdate)?month(item.startdate):''}}&nbsp;&nbsp;</u>月
            <u>&nbsp;&nbsp;{{day(item.startdate)?day(item.startdate):''}}&nbsp;&nbsp;</u>日至
            <u>&nbsp;&nbsp;{{year(item.enddate)?year(item.enddate):''}}&nbsp;&nbsp;</u>年
            <u>&nbsp;&nbsp;{{month(item.enddate)?month(item.enddate):''}}&nbsp;&nbsp;</u>月
            <u>&nbsp;&nbsp;{{day(item.enddate)?day(item.enddate):''}}&nbsp;&nbsp;</u>日止。免租期内乙方不支付租金，以便于乙方进行装饰装修及办理入住手续等事宜。免租期内物业管理费、供暖费由 □甲方 □乙方 承担。
            </span>
 -->
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（一）本合同房屋租赁期限为
            <u v-if="nian">{{nian?nian:'__'}}</u><span v-if="nian" style="display: inline">年</span>
            <u v-if="yue">{{yue?yue:'__'}}</u><span v-if="yue" style="display: inline">月</span>
            <u v-if="ri>1">{{ri>1?ri:'__'}}</u><span v-if="ri>1" style="display: inline">日</span>
            <span v-if="!nian&&!yue" style="display: inline">
                ____年__月__日
            </span>。<br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;自<u>&nbsp;&nbsp;{{year(addDate.startdate)}}&nbsp;&nbsp;</u>年
            <u>&nbsp;&nbsp;{{month(addDate.startdate)}}&nbsp;&nbsp;</u>月
            <u>&nbsp;&nbsp;{{day(addDate.startdate)}}&nbsp;&nbsp;</u>日至
            <u>&nbsp;&nbsp;{{year(addDate.enddate)}}&nbsp;&nbsp;</u>年
            <u>&nbsp;&nbsp;{{month(addDate.enddate)}}&nbsp;&nbsp;</u>月
            <u>&nbsp;&nbsp;{{day(addDate.enddate)}}&nbsp;&nbsp;</u>日止。
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（二）合同期满乙方仍使用该房屋，乙方应提前90天通知甲方，双方协商同意后另行签署新的租赁合同；若乙方未提前90日提出书面续租申请视为乙方放弃续租权，在此期间乙方应配合甲方带领未来租户看房。在同等市场条件下，乙方拥有优先承租权。
            <br><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;第四条  租金结算</b>
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（一）租金标准 <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;乙方按照下列标准向甲方支付租金（以人民币进行结算）：<span v-for="(item,index) in addDate.zujinList">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>&nbsp;{{year(item.startdate)?year(item.startdate):'&nbsp;&nbsp;'}}&nbsp;</u>年
            <u>&nbsp;{{month(item.startdate)?month(item.startdate):'&nbsp;&nbsp;'}}&nbsp;</u>月
            <u>&nbsp;{{day(item.startdate)?day(item.startdate):'&nbsp;&nbsp;'}}&nbsp;</u>日至
            <u>&nbsp;{{year(item.enddate)?year(item.enddate):'&nbsp;&nbsp;'}}&nbsp;</u>年
            <u>&nbsp;{{month(item.enddate)?month(item.enddate):'&nbsp;&nbsp;'}}&nbsp;</u>月
            <u>&nbsp;{{day(item.enddate)?day(item.enddate):'&nbsp;&nbsp;'}}&nbsp;</u>日，租金为￥<u>&nbsp;&nbsp;{{toDecimal(item.yuezujin)?toDecimal(item.yuezujin):'_____'}}&nbsp;&nbsp;</u>元/月（大写：<u>&nbsp;&nbsp;{{daxie(item.yuezujin)?daxie(item.yuezujin):'_____'}}&nbsp;&nbsp;</u>元/月）；
        </span>

            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（二）租金支付方式为：<br>
            <span v-for="(item,index) in addDate.fukuanFangshiList"
                  :key="index"
                  style="display: inline"
            >
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <u>&nbsp;{{year(item.startdate)?year(item.startdate):'&nbsp;&nbsp;'}}&nbsp;</u>年
            <u>&nbsp;{{month(item.startdate)?month(item.startdate):'&nbsp;&nbsp;'}}&nbsp;</u>月
            <u>&nbsp;{{day(item.startdate)?day(item.startdate):'&nbsp;&nbsp;'}}&nbsp;</u>日至
            <u>&nbsp;{{year(item.enddate)?year(item.enddate):'&nbsp;&nbsp;'}}&nbsp;</u>年
            <u>&nbsp;{{month(item.enddate)?month(item.enddate):'&nbsp;&nbsp;'}}&nbsp;</u>月
            <u>&nbsp;{{day(item.enddate)?day(item.enddate):'&nbsp;&nbsp;'}}&nbsp;</u>日，
            押 <u>{{item.yajinyue?intToChinese(item.yajinyue):'__'}}</u> 付 <u>{{item.zujinyue?intToChinese(item.zujinyue):'__'}}</u>;<br>
            </span>



            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1、房租押金：￥ <u>{{toDecimal(addDate.yajin)?toDecimal(addDate.yajin):'____'}}</u>元（大写：<u>&nbsp;&nbsp;{{daxie(addDate.yajin)?daxie(addDate.yajin):'________'}}&nbsp;&nbsp;</u>），支付时间为

            <u>&nbsp;&nbsp;{{year(addDate.yajinfukuanriqi)}}&nbsp;&nbsp;</u>年
            <u>&nbsp;&nbsp;{{month(addDate.yajinfukuanriqi)}}&nbsp;&nbsp;</u>月
            <u>&nbsp;&nbsp;{{day(addDate.yajinfukuanriqi)}}&nbsp;&nbsp;</u>日前。


            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2、押金是乙方向甲方交付的合法履约的保证金，如乙方在租赁期限届满之前违反本合同约定，押金作为违约金不予退还。租赁期满，乙方结清应承担的费用，并将工商注册地址迁离、税控机迁出此房后3个工作日内由甲方退还乙方剩余押金。
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3、首期租金：￥ <u>{{toDecimal(addDate.zujinList[0].yuezujin*addDate.fukuanFangshiList[0].zujinyue)>0?toDecimal(addDate.zujinList[0].yuezujin*addDate.fukuanFangshiList[0].zujinyue):'_____'}}</u> 元（大写：<u>&nbsp;&nbsp;{{daxie(addDate.zujinList[0].yuezujin*addDate.fukuanFangshiList[0].zujinyue)?daxie(addDate.zujinList[0].yuezujin*addDate.fukuanFangshiList[0].zujinyue):'_____'}}</u>），支付时间为

            <u>&nbsp;&nbsp;{{year(addDate.shouqifukuanri)}}&nbsp;&nbsp;</u>年
            <u>&nbsp;&nbsp;{{month(addDate.shouqifukuanri)}}&nbsp;&nbsp;</u>月
            <u>&nbsp;&nbsp;{{day(addDate.shouqifukuanri)}}&nbsp;&nbsp;</u>日前；
            <span style="display: inline;"><!-- v-if="addDate.erqifukuanri"-->
                租金每 <u>{{addDate.fukuanFangshiList[0].zujinyue?intToChinese(addDate.fukuanFangshiList[0].zujinyue):'__'}}</u>个月支付一次，
            于付款月起租日 <u>{{addDate.tiqianfukuantian?addDate.tiqianfukuantian:'__'}}</u> 日前支付下一次租金，即第二期租金的支付时间为<u>&nbsp;&nbsp;{{year(addDate.erqifukuanri)}}&nbsp;&nbsp;</u>年
            <u>&nbsp;&nbsp;{{month(addDate.erqifukuanri)}}&nbsp;&nbsp;</u>月
            <u>&nbsp;&nbsp;{{day(addDate.erqifukuanri)}}&nbsp;&nbsp;</u>日前，
            </span>
            <span style="display: inline;"><!-- v-if="addDate.sanqifukuanri"-->
                第三期租金的支付时间为<u>&nbsp;&nbsp;{{year(addDate.sanqifukuanri)}}&nbsp;&nbsp;</u>年
            <u>&nbsp;&nbsp;{{month(addDate.sanqifukuanri)}}&nbsp;&nbsp;</u>月
            <u>&nbsp;&nbsp;{{day(addDate.sanqifukuanri)}}&nbsp;&nbsp;</u>日前，
            </span>

            <span style="display: inline;"><!-- v-if="addDate.erqifukuanri"-->
                            合同期每期租金以此类推。
            </span>
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（三）租金的结算方式为：□ 以转账方式 ；□ 现金支付
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;甲方指定收款账户为：
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;户 &nbsp;&nbsp;名：<input type="text" style="width: 350px;" v-model="renter.shoukuanren">
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;开户行：<input type="text" style="width: 350px;" v-model="renter.kaihuhang">
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;账 &nbsp;&nbsp;号：<input type="text" style="width: 350px;" v-model="renter.zhanghao">
            <br><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;第五条  相关费用的承担方式</b>
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（一）甲方承担在承租期的物业管理费、供暖费。
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（二）在承租期内，乙方自行承担在使用期间的相关费用（水费、电费、燃气费、宽带费、停车费、能源押金、制冷费、冷暖转换费等），乙方按物业管理机构提供的交费通知单交费。
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（三）在承租期内，乙方需要在此房间内报装电话、宽带的，相关费用由乙方自行承担。
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（四）本合同租金仅含物业费、供暖费，不含出租房屋需缴纳的税费，出租房屋所产生的税费由乙方全部承担并缴纳。甲方向乙方提供身份证明及房屋所有权证复印件，由乙方自行到税务部门开具房租增值税普通发票。
            <br><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;第六条  甲方的权利义务</b>
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（一）甲方应保证出租房屋的主体建筑结构和基础设备设施能达到使用要求，不得影响乙方正常使用。
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（二）对于该房屋的主要结构、固定管道线路及固定设施（包括制热、制冷、排风、上下水等设施）发生自然损坏、故障或合理使用而导致的老化、耗损，由甲方承担维修责任，乙方可及时通知丙方代为处理。
            <br><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;第七条  乙方的权利义务
            </b>
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（一）承租期内，如乙方需要对承租房屋进行装修或改动前应获得甲方书面同意，且装修或改动应符合国家相关法律法规规定以及物业管理规定，但乙方不得拆除、破坏承租区域建筑的主要结构。
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（二）由于乙方装修或使用原因，导致房间的主要结构、固定管道线路及设施发生损坏、故障乙方须及时修复或照价赔偿。
            <br><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;第八条  丙方的权利义务</b>
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（一）甲方授权丙方代理甲方签署房屋租赁合同，对出租房屋进行管理；丙方接受甲方委托，代表甲方对承租人履约及使用房屋进行监督和管理；乙方违反本合同约定的，丙方有权以自身名义提出解除本合同及要求乙方承担违约责任。
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（二）租赁期满或因乙方违约致使本合同解除的，丙方有权代理甲方收回出租房屋，并办妥相关手续。
						<br><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;第九条  合同解除</b>
						<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（一）经甲乙双方协商一致，可以解除本合同。
						<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（二）因不可抗力导致本合同无法继续履行的，本合同自行解除。
						<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（三）甲方有下列情形之一的，乙方有权单方解除合同：
						<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1、交付的房屋存在重大安全问题，导致乙方无法正常使用达15个工作日。
						<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2、房屋主体固定设施严重损坏，致使乙方无法正常使用房屋。
						<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3、甲方不具备出租此房权利。
						<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4、甲方提前终止合同。
						<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（四）乙方有下列情形之一的，甲方有权单方解除合同并立即收回房屋：
						<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1、不按照约定支付租金达3日。
						<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2、未经甲方同意，将房屋私自转租、分租、转借给第三方。
						<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3、从事违法活动或危害公共安全，妨碍他人正常工作生活。
						<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4、擅自拆除或破坏房屋建筑主要结构或因乙方装修造成房屋重大安全隐患。
						<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5、乙方提前终止合同。
            <br><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;第十条  违约责任</b>
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（一）甲方有第九条第（三）款约定的情形之一的，应按2个月租金为标准向乙方支付违约金，同时退还乙方已支付押金及未使用租期租金；乙方有第九条第（四）款约定的情形之一的，应按2个月租金为标准向甲方支付违约金。
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（二）租赁期内，甲方收回房屋自用或乙方提前退租应提前3个月通知对方，如未提前通知对方，违约方应向守约方支付2个月房租作为未提前通知的租金补偿，同时乙方应配合甲方带领未来租户看房，本条款与违约责任条款第（一）款同时叠加适用。
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（三）乙方在租赁房屋内的工商注册等营业证照、税控机，应在租赁期满或合同解除后10日内迁出，逾期未迁出的，乙方须按日向甲方支付租金直至乙方实际将营业证照、税控机迁出之日止，甲方退还乙方剩余押金。
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（四）由乙方原因违约本合同提前终止（包含租期结束），乙方须在终止或解除日3日内腾空房屋并向甲方返还该房屋；如乙方未能在3日内自行腾出该房屋，甲方有权立即收回房屋及钥匙，有权采取换锁、停水停电、阻止人员在承租区域进出经营等自我救济行为，同时可将屋内乙方物品清出至室外，甲方不承担任何保管及赔偿责任，乙方同意自行承担由此产生的全部后果和责任。

            <br><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;第十一条  争议解决</b>
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;本合同项下发生的争议，由各方当事人协商解决；协商不成的，依法向有管辖权的人民法院起诉，或按照另行达成的仲裁条款或仲裁合同申请仲裁。
            <br><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;第十二条   其他</b>
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（一）各方一致同意，合同履行期间如需出具通知，应以书面形式（包括但不限于邮寄送达、电子邮件、手机短信、微信、以及通过丙方网站中为甲、乙双方分别开设的空间发送通知等方式）做出，具体送达日期为：邮寄送达以信函发出后的第 2 个工作日为准，电子邮件以到达对方服务器时间为准，手机短信及网站通知以到达对方设备时间为准。
						<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（二）各方在本合同中书写的地址即为本合同下任何书面通知的有效送达地址，若因接收方拒收或地址错误等情况致使无法送达的，均以付邮日（以邮戳为准）后3日即视为通知方已依本合同给予书面通知。若任何一方联络地址变更的，应及时通知对方。
						<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（三）本合同经甲乙丙三方签字或盖章后生效。丙方作为甲方房屋资产的管理方可代理甲方签署本合同。本合同一式叁份，甲、乙、丙三方各持一份。本合同生效后，各方对合同内容的变更或补充应采取书面形式，作为本合同的附件。
        <div v-if="historyBuchong">
            <h3>补充协议：</h3>
            <history-buchong></history-buchong>
        </div>
        <div v-if="listJieyue">
            <h3>解约协议：</h3>
            <list-jieyue></list-jieyue>
        </div>
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