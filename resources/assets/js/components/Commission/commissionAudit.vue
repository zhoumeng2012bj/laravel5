<template>
	<el-row>
		<table border="1" bordercolor="#DFE6EC" style="border-collapse:collapse!important;text-align:center;width:1280px">
			<tbody>
				<tr style=" text-align:left;background-color:#EEF1F6;height: 40px">
					<td colspan="6">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 基本信息</td>
				</tr>
				<tr class="tr1">
					<td class="td1"> 楼盘：
					</td>
					<td class="td2">{{Payable.loupan}}</td>
					<td class="td1">
						座栋：
					</td>
					<td class="td2">
						{{Payable.loudong}}
					</td>
					<td class="td1">
						房间号：
					</td>
					<td class="td2">
						{{Payable.fanghao}}
					</td>
				</tr>
				<tr class="tr1">
					<td> 签单人：
					</td>
					<td>{{Payable.salesmanName}}</td>
					<td>
						联系电话：
					</td>
					<td>
						{{Payable.salesmanPhone}}
					</td>
					<td>
						申领金额：
					</td>
					<td>
						{{toDecimal(Payable.yongjin)}}
					</td>
				</tr>
				<tr class="tr1">
					<td> 合同月租金：
					</td>
					<td>{{toDecimal(Payable.monthMoney)}}</td>
					<td>
						佣金信息：
					</td>
					<td>
						{{changeBool(Payable.xsYongjinxinxi)}}
					</td>
					<td>
					</td>
					<td>
					</td>
				</tr>
				<tr class="tr1">
					<td> 佣金计算公式：
					</td>
					<td colspan="5">{{Payable.xsJisuangongshi}}</td>

				</tr>
				<tr class="tr1">
					<td> 渠道门店：
					</td>
					<td>{{Payable.gsname}}</td>
					<td>
						渠道人员：
					</td>
					<td>
						{{Payable.qvdao}}
					</td>
					<td>
						联系电话：
					</td>
					<td>
						{{Payable.phone}}
					</td>
				</tr>
				<tr class="tr1">
					<td> 收款方户名：
					</td>
					<td>{{Payable.huming}}</td>
					<td>
						开户行：
					</td>
					<td>
						{{Payable.kaihuhang}}
					</td>
					<td>
						银行账号：
					</td>
					<td>
						{{Payable.zhanghao | card_str}}
					</td>
				</tr>
			</tbody>
		</table>
		<table width="1280px" border="1" bordercolor="#DFE6EC" style="border-collapse:collapse!important;text-align:center;margin-top:30px">
			<tbody>
				<tr style=" text-align:left;background-color:#EEF1F6;height: 40px">
					<td colspan="6">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 发票信息</td>
				</tr>

			</tbody>
		</table>
		<!-- <el-upload  style="margin-top:30px"
                    action=""
                    list-type="picture-card"
                    disabled="true"
                    :on-preview="handlePictureCardPreview"
                    :file-list="fapiao"
        >

        </el-upload>
        <el-dialog v-model="dialogVisible" size="50" style="height: 100%;">
            <div class="imgbox">
            	<div id="imageView_container" style="overflow: auto; position: relative; width: 800px; height: 500px; ">
				    <img :src="dialogImageUrl" id="rotImg" style="cursor: pointer; visibility: visible; position: absolute;width: auto; height: auto;" />
				</div>
				<div style="padding-top:5px;">
				    <input type="button" value="" @click="imgToSize(100)" class="fbig" @mousedown="fbig" @mouseup="fbig1" @mouseleave="leav">
				    <input type="button" value="" @click="imgToSize(-100);" class="fsmall" @mousedown="fsmall" @mouseup="fsmall1" @mouseleave="leav">
				    <input type="button" value="" id="rotRight" @click="toright" @mousedown="torights" @mouseup="torights1" @mouseleave="leav">
				</div>
            </div>
        </el-dialog> -->
		<div class="big_box">
			<ul id="img_box">
				<li v-for="(item,index) in imgArr">
					<img :src="item" alt="">
				</li>
			</ul>
		</div>
		<table width="1280px" border="1" bordercolor="#DFE6EC" style="border-collapse:collapse!important;text-align:center;margin-top:30px">
			<tbody>
				<tr style=" text-align:left;background-color:#EEF1F6;height: 40px">
					<td colspan="6">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 审批进度</td>
				</tr>
				<tr style=" text-align:left; height: 40px">
					<td colspan="6">
						<!--<div style="margin-left:20px;margin-top:30px;width: 100%;height: 130px"  >-->

						<!--<el-steps :active=shenpijindu align-center >
                        <el-step v-for="(item, index) in options" :title="item.value" :key="index" :description="item.label"></el-step>

                    </el-steps>-->
						<!--</div>-->
						<ul class="plan_box" v-for="(i,idx) in spDatas.length">
							<li v-for="(item, index) in spDatas[idx]" :title="item.shuoming">
								<p :class="item.shenpi==1 && !item.isfock?'zt':(item.shenpi!=1 && item.shenpi!=2 && item.isfock?'zt spz':(item.shenpi==2?'zt ybh':'zt dsp'))">
									<span :class="item.shenpi==1 && !item.isfock?'xh':(item.shenpi!=1 && item.shenpi!=2 && item.isfock?'xh xh3':(item.shenpi==2?'xh xh2':'xh xh1'))">{{(idx * 8 + index + 1)}}</span>
									<span>
										<i v-if='item.shenpi==1 && !item.isfock'>{{item.shenpi==1 && !item.isfock?"已通过":"待审批"}}</i>
										<i v-if='item.shenpi==null && !item.isfock'>{{item.shenpi==null && !item.isfock?"待审批":''}}</i>
										<i v-if='item.shenpi!=1 && item.shenpi!=2 && item.isfock'>审批中</i>
										<i v-if='item.shenpi==2'>{{item.shenpi==2?"已驳回":"已审批"}}</i>
									</span>
									<span :class="item.shenpi==1 && !item.isfock?'tiao':(item.shenpi!=1 && item.shenpi!=2 && item.isfock?'tiao jb1':(item.shenpi==2?'tiao jb2':(item.shenpi==null && !item.isfock?'tiao jb3':'tiao jb')))"></span>

									<span v-for=" items in options" :class="items.shenpi==1 && !items.isfock?'tiaos':(items.shenpi!=1 && items.shenpi!=2 && items.isfock?'tiaos jb1':(items.shenpi==2?'tiaos jb4':(items.shenpi==null && !items.isfock?'tiaos jb3':(items.shenpi==2 && items.isfock?'tiaos jb2':'tiaos jb'))))"
									 v-if="(idx * 8 + index + 1)%8==0"></span>
								</p>
								<p class="name">{{item.person}}</p>
								<p class="date">{{item.shenpitime | typeDate}}</p>
							</li>
							<!--<p class="jdx"></p>-->
						</ul>
					</td>
				</tr>
			</tbody>
		</table>

		<table width="1280px" border="1" bordercolor="#DFE6EC" style="border-collapse:collapse!important;text-align:center;margin-top:30px">
			<tbody>
				<tr style=" text-align:left;background-color:#EEF1F6;height: 40px">
					<td colspan="6">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 审批意见</td>
				</tr>

			</tbody>
		</table>
		<el-input type="textarea" auto-complete="off" placeholder="请输入意见" v-model="shuoming" style="width:1280px"></el-input>
		<div style="text-align:center;margin-top:30px;width:1280px">
			<el-button @click.native="save">通过</el-button>
			<el-button @click.native="bohui">驳回</el-button>
		</div>
	</el-row>
</template>
<style>
	.tr1 {
		height: 40px;
	}

	.td1 {
		width: 160px;
	}

	.td2 {
		width: 240px;
	}

	.el-upload--picture-card {
		display: none !important;
	}
</style>
<script>
	import Viewer from 'viewerjs';
	import '../../../../../node_modules/viewerjs/dist/viewer.min.css';
	import {
		showcommission,
		auditComm
	} from '../../api/api';
	import ElForm from "../../../../../node_modules/element-ui/packages/form/src/form";
	export default {
		components: {
			ElForm
		},
		data() {
			return {
				Payable: [],
				nowDate: '',
				options: [],
				shenpijindu: 1,
				shuoming: '',
				fapiao: [],
				dialogVisible: false,
				dialogImageUrl: '',
				spData: [],
				spDatas: [], //重新组织后的数据123
				rote: -1, //旋转状态
				sizes: 0,
				imgArr: []
			}
		},
		methods: {
			//时间戳转日期格式
			changeDate(value) {
				if (value != '' && value != null) {
					console.log(value)
					var newDate = new Date();
					newDate.setTime(value);
					var hour = newDate.getHours();
					var minute = newDate.getMinutes();
					var second = newDate.getSeconds();
					if (hour < 10) {
						hour = '0' + hour;
					}
					if (minute < 10) {
						minute = '0' + minute;
					}
					if (second < 10) {
						second = '0' + second;
					}
					return newDate.toLocaleDateString() + ' ' + hour + ':' + minute + ':' + second;
				}
			},
			//时间戳转日期格式
			changeBool(value) {
				return value ? '正常' : "不正常";

			},
			handlePictureCardPreview(file) {
				this.dialogImageUrl = file.url;
				this.dialogVisible = true;
			},
			//获取应付款列表
			getPayable() {
				let para = {
					id: this.$route.query.id
				};
				this.listLoading = true;
				showcommission(para).then((res) => {
					this.spData = res.data.shenPi;
					this.Payable = res.data.data;
					this.fapiao = res.data.imgs;
					for (var m = 0; m < res.data.imgs.length; m++) {
						this.imgArr.push(res.data.imgs[m].url);
					}

					setTimeout(function() {
						const ViewerDom = document.getElementById('img_box');
						const viewer = new Viewer(ViewerDom, {
							// 配置
						})
					});
					this.listLoading = false;
					for (var item in res.data.shenPi) {
						if (res.data.shenPi[item].isfock == true) {
							this.shenpijindu = parseInt(item);
						}

						this.options.push({
							value: res.data.shenPi[item].person,
							label: this.changeDate(res.data.shenPi[item].shenpitime),
							shenpi: res.data.shenPi[item].shenpi,
							isfock: res.data.shenPi[item].isfock,
						});
					}
					var newArr = [],
						b;
					this.spData.forEach(function(item, index, array) {
						var a = Math.floor(index / 8);
						if (b !== a) {
							b = a;
							newArr[a] = new Array();
						}
						newArr[a].push(item);
					});
					this.spDatas = newArr;


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

				return s.split('').reverse().join('').replace(/(\d{3}(?=\d)(?!\d+\.|$))/g, '$1,').split('').reverse().join('');

			},
			save() {
				this.$confirm('确认通过吗？', '提示', {}).then(() => {
					let para = {
						id: this.$route.query.id,
						shenpi: 1,
						shuoming: this.shuoming,
					};
					auditComm(para).then((res) => {
						if (res.data.code == 200) {
							this.$message({
								message: '提交成功',
								type: 'success'
							});
							if (this.fun("commissionAuditList")) {
								this.$router.push('/commissionAuditList');
							} else {
								this.$router.push('/commissionBalanceList');
							}

						} else {
							this.$message({
								message: res.data.msg,
								type: 'error'
							});

						};
					});
				});
			},
			bohui() {
				this.$confirm('确认驳回吗？', '提示', {}).then(() => {
					let para = {
						id: this.$route.query.id,
						shenpi: 2,
						shuoming: this.shuoming,
					};
					auditComm(para).then((res) => {
						if (res.data.code == 200) {
							this.$message({
								message: '提交成功',
								type: 'success'
							});
							if (this.fun("commissionAuditList")) {
								this.$router.push('/commissionAuditList');
							} else {
								this.$router.push('/commissionBalanceList');
							}
						} else {
							this.$message({
								message: res.data.msg,
								type: 'error'
							});

						};
					});
				});
			},
			//放大缩小图片
			imgToSize(size) {
				this.sizes = size;
				var img = $("#rotImg");
				var oWidth = img.width(); //取得图片的实际宽度
				var oHeight = img.height(); //取得图片的实际高度
				if ((oWidth + this.sizes) < 200) {
					return;
				}
				img.width(oWidth + this.sizes);
				img.height(oHeight + this.sizes / oWidth * oHeight);
			},
			toright() {
				this.rote++;
				if (this.rote == 0) {
					$('#rotImg').addClass("rot1");
					$('#rotImg').removeClass("rot4");
				}
				if (this.rote == 1) {
					$('#rotImg').addClass("rot2");
					$('#rotImg').removeClass("rot1");
				}
				if (this.rote == 2) {
					$('#rotImg').addClass("rot3");
					$('#rotImg').removeClass("rot2");
				}
				if (this.rote == 3) {
					$('#rotImg').addClass("rot4");
					$('#rotImg').removeClass("rot3");
					this.rote = -1;
				}
			},
			fbig() {
				$('.fbig').css('transform', 'scale(0.8)');
			},
			fbig1() {
				$('.fbig').css('transform', 'scale(1)');
			},
			fsmall() {
				$('.fsmall').css('transform', 'scale(0.8)');
			},
			fsmall1() {
				$('.fsmall').css('transform', 'scale(1)');
			},
			leav() {
				$('.fbig').css('transform', 'scale(1)');
				$('.fsmall').css('transform', 'scale(1)');
				$('#rotRight').css('transform', 'scale(1)');
			},
			torights() {
				$('#rotRight').css('transform', 'scale(0.8)');
			},
			torights1() {
				$('#rotRight').css('transform', 'scale(1)');
			}
		},
		mounted() {
			this.getPayable();
			//
			if (this.dialogVisible) {
				var param = {
					right: document.getElementById("rotRight"),
					left: document.getElementById("rotLeft"),
					img: document.getElementById("rotImg"),
					rot: 0
				};
				console.log(param)
				var fun = {
					right: function() {
						param.rot += 1;
						param.img.className = "rot" + param.rot;
						if (param.rot === 3) {
							param.rot = -1;
						}
					},
					left: function() {
						param.rot -= 1;
						if (param.rot === -1) {
							param.rot = 3;
						}
						param.img.className = "rot" + param.rot;
					}
				};
				$('#imageView_container').imageView({
					width: 800,
					height: 500
				});
				var size = 0;
			}
		},
		filters: {
			//时间戳转日期格式
			typeDate(value) {
				if (value != '' && value != null) {
					console.log(value)
					var newDate = new Date();
					newDate.setTime(value);
					var hour = newDate.getHours();
					var minute = newDate.getMinutes();
					var second = newDate.getSeconds();
					if (hour < 10) {
						hour = '0' + hour;
					}
					if (minute < 10) {
						minute = '0' + minute;
					}
					if (second < 10) {
						second = '0' + second;
					}
					return newDate.toLocaleDateString() + ' ' + hour + ':' + minute + ':' + second;
				}
			},
			card_str(num) {
				var str = String(num).replace(/(\d{4})/g, '$1 ').replace(/\s*$/, '');
				return str;
			}

		}
	}
</script>
<style scoped>
	ul,
	li,
	dl,
	dd {
		list-style: none;
		margin: 0;
		padding: 0;
	}

	.plan_box {
		position: relative;
		display: flex;
		min-height: 50px;
		height: auto;
		font-size: 16px;
		margin: 30px;
	}

	.plan_box p {
		margin: 0;
		padding: 0;
		text-align: center;
	}

	.plan_box li {
		display: flex;
		flex-direction: column;
		align-items: center;
		width: 120px;
		margin-right: 35px;
	}

	.zt {
		position: relative;
		width: 120px;
		height: 34px;
		line-height: 34px;
		background: #1fa0fc;
		color: #fff;
		font-size: 16px;
		text-align: center;
		border-radius: 34px;
		margin-bottom: 10px !important;
	}

	.zt .xh {
		display: inline-block;
		width: 20px;
		height: 20px;
		line-height: 20px;
		border-radius: 50%;
		background: #fff;
		color: #1fa0fc;
		text-align: center;
	}

	.zt i {
		font-style: normal;
	}

	.name,
	.date {
		color: #646464;
	}

	.tiao {
		position: absolute;
		left: -37.5px;
		top: 50%;
		margin-top: -1px;
		display: inline-block;
		width: 40px;
		height: 2px;
		background: #1fa0fc;
	}

	.tiaos {
		position: absolute;
		right: -35px;
		top: 85px;
		margin-top: -1px;
		display: inline-block;
		width: 104px;
		height: 2px;
		background: #1fa0fc;
		transform: rotate(-270deg) !important;
	}

	.plan_box li:first-child .tiao {
		display: none;
	}

	.dsp {
		/*待审批样式*/
		background: #bbbbbb;
	}

	.xh1 {
		/*待审批文字颜色*/
		color: #bbbbbb !important;
	}

	.ybh {
		/*驳回样式*/
		background: #ff7271;
	}

	.xh2 {
		/*驳回文字颜色*/
		color: #ff7271 !important;
	}

	.spz {
		/*审批中*/
		background: #fea843;
	}

	.xh3 {
		/*审批中文字颜色*/
		color: #fea843 !important;
	}

	.jb {
		/*条渐变审批中到待审批*/
		background: -webkit-linear-gradient(left, #fea843, #bbbbbb);
	}

	.jb1 {
		/*条渐变已通过到审批中*/
		background: -webkit-linear-gradient(left, #20a1ff, #fea843);
	}

	.jb2 {
		/*条渐变已通过到已驳回*/
		background: -webkit-linear-gradient(left, #20a1ff, #ff7271);
	}

	.jb3 {
		background: #bbbbbb;
	}

	.plan_box:nth-child(even) {
		flex-direction: row-reverse;
	}

	.plan_box:nth-child(even) .tiao {
		transform: rotateY(180deg) !important;
		left: 100%;
	}

	.plan_box:nth-child(odd) .jdx {
		position: absolute;
		top: 17px;
		right: 21px;
		width: 12px;
		height: 134px;
		border: 2px solid #20a1ff;
		border-left: none;
	}

	.plan_box:nth-child(even) .jdx {
		position: absolute;
		top: 17px;
		right: 21px;
		width: 12px;
		height: 134px;
		border: 2px solid #20a1ff;
		border-left: none;
		transform: rotateY(180deg) !important;
		right: 100%;
	}

	.plan_box:last-child .jdx {
		/*最后一行隐藏*/
		display: none;
	}

	.rot1 {
		-moz-transform: rotate(90deg);
		-webkit-transform: rotate(90deg);
		transform: rotate(90deg);
		filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=1);
		-ms-filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=1);
	}

	.rot2 {
		-moz-transform: rotate(180deg);
		-webkit-transform: rotate(180deg);
		transform: rotate(180deg);
		filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=2);
		-ms-filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=2);
	}

	.rot3 {
		-moz-transform: rotate(270deg);
		-webkit-transform: rotate(270deg);
		transform: rotate(270deg);
		filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
		-ms-filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
	}

	.rot4 {
		-moz-transform: rotate(360deg);
		-webkit-transform: rotate(360deg);
		transform: rotate(360deg);
		filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=4);
		-ms-filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=4);
	}

	#imageView_container {
		display: flex;
		align-items: center;
		justify-content: center;
		/*border: 2px solid #000000;*/
	}

	.fbig {
		outline: none;
		border: none;
		width: 60px;
		height: 30px;
		background: url(../../../../../public/image/fbig.png) no-repeat center;
		background-size: 45%;
	}

	.fsmall {
		outline: none;
		border: none;
		width: 60px;
		height: 30px;
		background: url(../../../../../public/image/fsmall.png) no-repeat center;
		background-size: 45%;
	}

	#rotRight {
		outline: none;
		border: none;
		width: 60px;
		height: 30px;
		background: url(../../../../../public/image/rotes.png) no-repeat center;
		background-size: 45%;
	}

	.fbig:hover {
		background: url(../../../../../public/image/fbig1.png) no-repeat center;
		background-size: 45%;
	}

	.fsmall:hover {
		background: url(../../../../../public/image/fsmall1.png) no-repeat center;
		background-size: 45%;
	}

	#rotRight:hover {
		background: url(../../../../../public/image/rotes1.png) no-repeat center;
		background-size: 45%;
	}

	.big_box {
		margin-top: 30px;
	}

	#img_box {
		display: flex;
	}

	#img_box li {
		width: 146px;
		height: 146px;
		border-radius: 5px;
		overflow: hidden;
		margin-right: 10px;
	}

	#img_box li img {
		width: 100%;
		height: 100%;
	}
</style>
