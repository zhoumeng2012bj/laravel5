<template>
    <div style="font-size: 12px;" class="summary">
        <p style="margin-left: 15%;font-size:17px;" v-if="data.fangwuzichanguanlifuwuhetong"> <u>房屋资产管理服务合同     管理方： 幼狮科技  </u> <span style="font-size:20px; ">【 &nbsp;&nbsp;&nbsp; 】</span> 级 </p>
        <p style="margin-left: 35%;font-size:17px;" v-if="!data.fangwuzichanguanlifuwuhetong"> <u>
            <input type="checkbox" v-model="data.youshikeji">
            幼狮科技
            <input type="checkbox" v-model="data.huashuoshangmao">
            华溯商贸</u></p>
        <p style="margin-left: 6%;"> <b>项目房间号</b> <u style="font-size: 24px;">{{data.xiangmufangjianhao}}</u> <b style="position:absolute;left:60%;">面积 <u>{{data.mianji}}m²</u></b> </p>
        <div style="margin-left:18px;float: left">
            <input type="checkbox" v-model="data.fangwuzichanguanlifuwuhetong" ><b>房屋资产管理服务合同</b>
            <input type="checkbox"style="margin-left:43px;" v-model="data.budongchanshouquanweituoshu"><b>不动产授权委托书</b>
            <input type="checkbox"style="margin-left:43px;" v-model="data.chanquanzhengfuyinjian"><b>产权证复印件（全套，无缺页）</b>
            <br>
            <input type="checkbox" v-model="data.yezhushengfenzhengfuyinjian"><b>业主身份证复印件</b>
            <input type="checkbox" style="margin-left:67px;" v-model="data.yezhushouquandailirenweituoshu"><b>业主授权代理人委托书</b>
            <input type="checkbox"style="margin-left:19px;" v-model="data.weituorenshenfenzhengfuyinjian"><b>委托人身份证复印件</b>
            <br>
            <table  style="border-collapse: collapse;">
                <tr >
                    <th width="75" height="23" align="left">业主</th>
                    <td width="240" align="center">{{data.yezhu}}</td>
                    <th width="100" align="left">收款人</th>
                    <td width="220" align="center">{{data.shoukuanren}}</td>
                </tr>
                <tr>
                    <th  height="23" align="left">联系方式</th>
                    <td  align="center">{{data.lianxifangshi}}</td>
                    <th  align="left">开户行</th>
                    <td  align="center">{{data.kaihuhang}}</td>
                </tr>
                <tr>
                    <th height="23" align="left"></th>
                    <td  align="center"></td>
                    <th  align="left">账号</th>
                    <td  align="center">{{data.zhanghao}}</td>
                </tr>
                <tr>
                    <th  height="23" align="left">委托管理期限</th>
                    <td  align="center">{{data.weituoguanliqixian}}</td>
                    <th  align="left"  rowspan="2">招商装修期</th>
                    <td  align="center" rowspan="2" width="120">{{data.zhaoshangzhuangxiuqi}}</td>
                </tr>
                <tr >
                    <th  height="23" align="left" rowspan="5">租金</th>
                    <td  align="center" height="23" ><p style="display: inline;">{{data.zujins[0].split(' ')[0]}}</p> <p style="display: inline;margin-left:15px;">{{data.zujins[0].split(' ')[1]}}</p> </td>
                </tr>
                <tr>
                    <td  align="center" height="23" ><p style="display: inline;">{{data.zujins[1]?data.zujins[1].split(' ')[0]:''}}</p> <p style="display: inline;margin-left:15px;">{{data.zujins[1]?data.zujins[1].split(' ')[1]:''}}</p> </td>
                    <th  align="left">保证金</th>
                    <td  align="center">¥{{toDecimal(data.baozhengjin)}}</td>
                </tr>
                <tr>
                    <td  align="center" height="23" ><p style="display: inline;">{{data.zujins[2]?data.zujins[2].split(' ')[0]:''}}</p> <p style="display: inline;margin-left:15px;">{{data.zujins[2]?data.zujins[2].split(' ')[1]:''}}</p> </td>
                    <th align="left">租金支付方式</th>
                    <td align="center">{{data.zujingzhifufangshi}}</td>
                </tr>
                <tr>
                    <td  align="center" height="23"></td>
                    <th  align="left" colspan="2">提前【{{data.tiqianfukuantian}}】天付款</th>
                </tr>
                <tr>
                    <td  align="center" height="23"></td>
                    <th  align="left" colspan="2">
                        <input type="checkbox" v-model="data.shifouhanwuye">是否含物业
                        <input type="checkbox" v-model="data.qvnuan">取暖
                        <input type="checkbox" v-model="data.zhileng">制冷
                        <input type="checkbox" v-model="data.fapiao">发票
                    </th>
                </tr>
                <tr>
                    <td  height="100" colspan="4"  ></td>
                </tr>
            </table>
            <table   style="border-collapse: collapse;margin-left:-15px; ">
            <tr>
                <td style="border:0 "></td>
                <td width="75" height="23">付款日</td>
                <td width="75">付款科目</td>
                <td width="200" align="center">付款周期</td>
                <td width="80">付款金额</td>
                <td width="202">备注</td>
            </tr>

            <tr v-for="(item,index) in data.yjProofs">
                <td style="border:0 "></td>
                <td height="23" align="center">{{changeDate(item.fukuanri)}}</td>
                <td align="center">{{item.fukuankemu}}</td>
                <td align="center" >{{item.fukuanzhouqi}}</td>
                <td align="center">¥{{toDecimal(item.fukuanjine)}}</td>
                <td>{{item.beizhu}}</td>
            </tr>
            <tr>
                <td style="border:0 "></td>

                <td height="23"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr v-for="(item,index) in data.zjProofs">
                <td style="border:0 ">{{index+1}}</td>
                <td height="23" align="center">{{changeDate(item.fukuanri)}}</td>
                <td align="center">{{item.fukuankemu}}</td>
                <td align="center">{{item.fukuanzhouqi}}</td>
                <td align="center">¥{{toDecimal(item.fukuanjine)}}</td>
                <td>{{item.beizhu}}</td>
            </tr><tr  v-for="x in (15-(data.zjProofs.length<15?data.zjProofs.length:15))">
            <td style="border:0 "></td>
            <td height="23"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>



        </table>
            <b>规划用途</b>:{{yongtu(data.guihuayongtu)}}
            <b style="margin-left:35px;">佣金</b>:¥{{toDecimal(data.yongjin)}}
            <b style="margin-left:35px;">业务</b>：{{data.yewu}}
            <b style="margin-left:35px;">签约日</b>:{{changeDate(data.qianyueri)}}
        </div>
    </div>


</template>
<style>
    .summary table td{
        border: 1px solid;
    }
    .summary table th{
        border: 1px solid;
    }
</style>
<script>
    import {summaryPurchaseContract} from '../../api/api.js';
    export default {
        data(){
            return{
                data:{}
            }
        },
        methods:{
            yongtu(use){
                var apply=[];
                apply[1] = '公寓';
                apply[2] = '写字楼';
                apply[3] = '商铺';
                apply[4] = '住宅';
                return apply[use];
            },
            changeDate(riqi){
                var newDate = new Date();
                newDate.setTime(riqi);
                if(riqi!=null){
                    return newDate.toLocaleDateString()
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
            getInfo(){
                let para ={
                    id:this.$route.query.id,
                    httype:0,
                }
                summaryPurchaseContract(para).then((res)=>{
                    this.data = res.data.data;
                    console.log(this.data)

                });
            },

        },
        mounted(){
            this.getInfo();
        }
    }
</script>