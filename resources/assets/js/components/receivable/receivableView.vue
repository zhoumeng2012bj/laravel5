<template>
		<div class="big_box">
			<ul id="img_box">
				<li v-for="(item,index) in imgArr">
					<img :src="item" alt="">
				</li>
			</ul>
		</div>
		<!-- <el-form style="margin-top:2%" :model="copyForm" ref="copyForm" :rules="copyRules" label-position="top">
			<div id="big_box">
			<el-upload
				action="/examine/addCopyImage"
				list-type="picture-card"
				:headers="headers"
				:data="data"
				multiple
				:on-preview="handlePictureCardPreview"
				:on-remove="handleRemove"
				:disabled="!fun('purchaseUploadEdit')"
				:on-success="handleSuccess"
				:file-list="hetongList"
			>
				<i v-if="fun('purchaseUploadEdit')" class="el-icon-plus"></i>
			</el-upload>
			</div>
			<el-dialog v-model="dialogVisible" size="large">
				<img width="100%" :src="dialogImageUrl" alt="">
			</el-dialog>
		</el-form> -->
</template>
<script>
	import Viewer from 'viewerjs';
	import '../../../../../node_modules/viewerjs/dist/viewer.min.css';
    import {
		receImageList,
		receiImageDelete,
        copyImageList,
        copyImageDelete,
        isCopyComplete,
        isCopyCompleteList,
        getPurchaseContractInfo,
    } from  '../../api/api';

    export default {
        data() {
            return {
                copyRules:{
                    iscompletehetong: [
                        { required: true, message: '不能为空' }
                    ],
                },
                copyForm:{
                    iscompletehetong:null,
                    iscompletecqrsfz: null,
                    iscompleteyyzz:null,
                    iscompletefrzz:null,
                    iscompletefcz:null,
                    iscompletebdcsqwts:null,
                    iscompleteyzsqdlrwts:null,
                    iscompletewtrsfz:null,
                    iscompletefwjgd:null,
                },
                hetongList: [],
                chanquanrenList: [],
                yingyezhizhao:[],
                faren:[],
                fangchanzheng:[],
                budongchan:[],
                yezhushouquan:[],
                weituoren:[],
                jiaogedan:[],
                yezhuleixing:null,
                dialogImageUrl: '',
                dialogVisible: false,
                headers:{
                    'X-CSRF-TOKEN':window.Laravel.csrfToken
                },
                data:{
                    id:null,
                },
				imgArr:[]
            };
        },
        methods: {
            save(){
                this.$refs.copyForm.validate((valid) => {
                    if(valid){
                        let para = Object.assign({}, this.copyForm,{id:parseInt(this.$route.query.id)});
                        isCopyComplete(para).then((res)=>{
                            if(res.data.code=='200'){
                                this.$message({
                                    message: '保存成功',
                                    type: 'success'
                                });
								window.location.reload();
                                history.go(-1);
                            }else{
                                this.$message({
                                    message: res.data.msg,
                                    type: 'error'
                                }) ;
                            }

                        });
                    }
                });

            },
            cansel(){
                history.go(-1);
            },
            beforeAvatarUpload(file) {
                const isJPG = file.type === 'image/jpeg';
                const isLt2M = file.size / 1024 / 1024 < 2;
                if (!isJPG) {
                    this.$message.error('上传头像图片只能是 JPG 格式!');
                }
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                return isJPG && isLt2M;
            },
            handleRemove(file, fileList) {
                this.$confirm('确认删除吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    let para ={
                        id :file.imgid?file.imgid:file.response.data,
                    }
                    receiImageDelete(para).then((res)=>{

                    });
                }).catch(()=>{
					fileList.push(file);
					// fileList.sort(this.compare('id'));
				})
            },
			compare(property){
				return function(a,b){
					var value1 = a[property];
					var value2 = b[property];
					return value2 - value1;
				}
			},
            handlePictureCardPreview(file) {
                this.dialogImageUrl = file.url;
                this.dialogVisible = true;
            },
            handleSuccess(response, file, fileList){
                //响应值
                console.log(response)
            },
			getCopyImageList(){
				let para = {
					id:this.$route.query.id
				}
				receImageList(para).then((res)=>{
					if(res.data.code=='200'){
						this.hetongList = res.data.data;//合同
						for(var m = 0; m < res.data.data.length; m++){
							this.imgArr.push('http://beta.youshikongjian.com' + res.data.data[m].url);//测试地址的图片路径
							// this.imgArr.push('http://erp.youshikongjian.com:8888' + res.data.data[m].url);//正式地址的图片路径
						}
						setTimeout(function(){
							const ViewerDom = document.getElementById('img_box');
							const viewer = new Viewer(ViewerDom, {
								// 配置
							})
						});
// 						if(!this.fun('purchaseUploadDel')){
// 							setTimeout(function(){
// 								$('.el-upload-list--picture-card .el-upload-list__item-actions .el-upload-list__item-delete').remove();
// 							})						
// 						}
					}
				})
			},
		},
		mounted(){
			this.data.id = this.$route.query.id;
			this.getCopyImageList();
			
		}
		

    }
</script>
<style>
	.big_box{margin-top: 30px;}
	#img_box{
			display: flex;
	}
	#img_box li{
			width: 146px;
			height: 146px;
			border-radius: 5px;
			overflow: hidden;
			margin-right: 10px;
	}
	#img_box li img{
			width: 100%;
			height: 100%;
	}
	
</style>