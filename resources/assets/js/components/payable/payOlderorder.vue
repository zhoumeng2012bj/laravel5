<template>
    <el-row>

        <table width="750" border="1" style="border-collapse:collapse!important">
            <caption><span style="font-size:24px;"> <b>支 出 凭 单</b></span>
                <p style="text-align:right;padding:0;margin:0;">业主支付方式：{{Payable.yezhuzhifufangshi}}</p></caption>
            <tbody>
            <tr>
                <td height="40">
                    <div align="left">用友编号：{{Payable.yongyouid}}</div>
                </td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp; {{changeDate(Payable.fukuandate)}}</td>
                <td height="40" colspan="2"><b>第&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号</b></td>
            </tr>
            <tr>
                <td height="110">
                    <div><b>付款摘要</b></div>
                </td>
                <td colspan="3">
                    <div align="left">
                        <b>项 目:</b>{{Payable.xiangmu}}<br>
                        <b>租 期:</b>{{Payable.zuqi}}<br>
                        <b>付款方式:</b>{{Payable.zhifufangshi}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>业主</b><u
                            style="text-decoration:none;border-bottom:1px solid #555;padding-bottom:2px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>个月房租<br>
                        <b>月租金:</b>{{toDecimal(Payable.monthmoney)}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;元<br>
                        <b>单价:</b>{{toDecimal(Payable.price)}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;元/m²/天<br>
                    </div>
                </td>
            </tr>
            <tr>
                <td height="30">
                    <div><b>计人民币</b></div>
                </td>
                <td height="30" colspan="3">
                    <div>{{daxie(Payable.tijiaomoney)}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;¥<u
                            style="text-decoration:none;border-bottom:1px solid #555;padding-bottom:2px;">{{toDecimal(Payable.tijiaomoney)}}元</u>
                    </div>
                </td>
            </tr>
            <tr>
                <td height="25">
                    <div><b>收款人/单位名称</b></div>
                </td>
                <td height="25" colspan="3">
                    <div>{{Payable.zhanghu}}</div>
                </td>
            </tr>

            <tr>
                <td height="25">
                    <div><b>银行账号</b></div>
                </td>
                <td height="25" colspan="3">
                    <div>{{Payable.fukuanzhanghao.replace(/\s/g, '').replace(/(.{4})/g, "$1 ")}}</div>
                </td>
            </tr>
            <tr>
                <td height="25">
                    <div><b>开户银行</b></div>
                </td>
                <td height="25" colspan="3">
                    <div>{{Payable.fukuanyinhang}}</div>
                </td>
            </tr>
            <tr>
                <td height="80">
                    <div><b>租户收款信息</b></div>
                </td>
                <td height="80" colspan="3">
                    <div align="left">
                        <b>租金交至:</b><br><br>
                        <b>月租金:</b><br><br>
                        <b>付款方式:</b><br><br>
                        <b>单价:</b><br>
                    </div>
                </td>
            </tr>

            </tbody>
        </table>
        <div align="left" class="printbottom">
            <b>财务主管：</b><span style="font-family:Times New Roman;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <b>记账：</b><span style="font-family:Times New Roman;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <b>出纳：</b><span style="font-family:Times New Roman;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <b>审核：</b><span style="font-family:Times New Roman;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <b>制单：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
            <b>主管审批:</b><span style="font-family:Times New Roman;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </div>
    </el-row>
</template>
<style>
    .printbottom {
        margin-bottom: 280px;
    }

    .printbottom:last-child {
        margin-bottom: 0px;
        margin-left: 130px;
    }

    table {
        margin-left: 130px;
    }

    div {
        margin-left: 10px;
    }

    * {
        margin-top: 0px;
    }
</style>
<script>

    import {
        getPayOrder,
    } from '../../api/api';
    import ElForm from "../../../../../node_modules/element-ui/packages/form/src/form";
    export default{
        components: {ElForm},
        data(){
            return {
                Payable: [],
                nowDate: '',
            }
        },
        methods: {
            daxie(money) {
                if (money == null) {
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
                if (money == '') {
                    return '';
                }
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

            //时间戳转日期格式
            changeDate(value){
                if (value != '' || value != null) {
                    var newDate = new Date();
                    newDate.setTime(value);
                    return newDate.toLocaleDateString();
                }
            },

            //获取应付款列表
            getPayable() {
                let para = {
                    id: this.$route.query.id
                };
                this.listLoading = true;
                getPayOrder(para).then((res) => {
                    this.Payable = res.data.data;
                    this.listLoading = false;
                });
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

                return  s.split('').reverse().join('').replace(/(\d{3}(?=\d)(?!\d+\.|$))/g, '$1,').split('').reverse().join('');

            },


        },
        mounted() {
            function hello() {
                window.print()
            }

            document.title = '支出凭证';
            this.getPayable();
            this.nowDate = new Date().toLocaleDateString();
            setTimeout(hello, 1000);
        }
    }
</script>
