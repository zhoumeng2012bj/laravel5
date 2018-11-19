<template>
    <div>
        <p>
            <b>&nbsp;&nbsp;第十三条  补充条款</b> <br>
            &nbsp; 以下条款内容与本合同其它各条款具备同等法律效力，若补充条款与本合同不一致或发生冲突时，应以补充条款为准。<br>
            <u v-html="buchongtiaokuan"></u>
            <br>
            <input v-if="buchongtiaokuan==null||iscompletefrzj==''" type="text" name="" disabled style="width:600px;" value="以下空白">
            <br><br>

            <b> 甲方（出租方）：<input type="text" name="" style="width:175px;font-size:10px" value=""></b>　<br>
            联系地址：<input type="text" name="" style="width:217px;font-size:10px" value=""></span>
            <br>联系方式：<input type="text" name="" style="width:217px;" disabled value="">
            <br>委托代理人：<input type="text" name="" style="width:160px;font-size:10px" value=""><br>

            <font class="datemar">_______年____月____日</font>&nbsp;&nbsp;
            <br><br><br><br>
        <p style="margin-top: 34px;">
            <b> <span style="display: inherit;">乙方（承租方）：<input type="text" name="" style="width:175px;font-size:10px" value=""></span></b>　<br>
            <span style="display: initial;">联系地址：<input type="text" name="" style="width:217px" value=""></span>
            <br><span style="display: initial;">联系方式：<input type="text" name="" style="width:217px;font-size:10px" value=""></span>
            <br><span style="display: initial;">委托代理人：<input type="text" name="" style="width:202px;font-size:10px" value=""></span><br>

            <font style="margin-left: 149px;" >_______年____月____日</font><br>
            <br>
        </p>
        <p>
            <b> 丙方（资产管理方）：<input type="text" name="" style="width:178px;font-size:10px" value=""></b><br>
        联系地址：<input type="text" name="" style="width:149px;font-size:10px" value="">
            <br>联系方式：<input type="text" name="" style="width:218px;font-size:10px" value="">
            <br>委托代理人：<input type="text" name="" style="width:218px;font-size:10px" value=""><br>
            <font class="datemar">_______年____月____日</font>
        </p>
    </div>
</template>
<style>
    body{
        text-align: center;
        height: 100%;
        position: relative;
    }
    div{
        margin: auto;
        top: 0;
        right: 0;
        left:0;
        bottom: 0;
        width: 90%;
        height: 20%;
    }
    .tc{text-align:center;}
    h1{
        font-size:28px;
    }
    span {
        display: block;}
    p{
        font-size: 14px; text-align:left;
        line-height: 2;}
    input{border: none;border-bottom: 1px solid#333333;outline: none; font-size: 14px!important;text-align:center}
    u{
        font-size:14px!important;
    }
    input[disabled]{
        background-color:white;
        color:#000000;
    }
    .datemar{margin-left: 152px;}
</style>
<script>
    import {getSaleContractInfo} from '../../api/api';
    export default {
        data(){
            return {
                buchongtiaokuan:null,
                hetongtype:null,
            }
        },
        methods:{
            //根据url得到的合同ID，来获取数据
            getSaleContract(id){
                getSaleContractInfo(id).then((res)=>{
                    if(res.data.code=='200'){
                        //把数据分别赋值给三个组件的变量
                        this.buchongtiaokuan = res.data.data.jiafangfeiyong;
                        this.hetongtype = res.data.data.hetongtype;
                    }else {
                        this.$message({
                            message: '获取数据失败',
                            type: 'error'
                        });
                    }
                })
            },
        },
        mounted(){
            this.getSaleContract(this.$route.query);
            document.title = '合同编号：'+this.$route.query.bianhao;
            function  hello() {
                window.print()
            }
            if(this.$route.query.isdump==1){
                setTimeout(hello,1000);
            }
        }

    }
</script>

