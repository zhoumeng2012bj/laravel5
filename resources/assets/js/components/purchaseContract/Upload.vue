<template>
    <el-form style="margin-top:2%" :model="copyForm" ref="copyForm" :rules="copyRules" label-position="top">
            <el-form-item label="合同" prop="iscompletehetong" style="margin-bottom: 24px;">
                <el-radio-group v-model="copyForm.iscompletehetong">
                    <el-radio :label=1>全</el-radio>
                    <el-radio :label=0>不全</el-radio>
                </el-radio-group>
            </el-form-item>
            <el-upload
                    action="/purchaseContract/addCopyImage?type=1"
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
            <el-dialog v-model="dialogVisible" size="large">
                <img width="100%" :src="dialogImageUrl" alt="">
            </el-dialog>
            <div v-if="yezhuleixing==1">
                <el-form-item label="产权人身份证"  prop="iscompletecqrsfz" style="margin-bottom: 24px;margin-top:40px;">
                    <el-radio-group v-model="copyForm.iscompletecqrsfz">
                        <el-radio :label=1>全</el-radio>
                        <el-radio :label=0>不全</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-upload
                        action="/purchaseContract/addCopyImage?type=2"
                        list-type="picture-card"
                        :headers="headers"
                        :data="data"
                        multiple
                        :on-preview="handlePictureCardPreview"
                        :on-remove="handleRemove"
                        :on-success="handleSuccess"
                        :file-list="chanquanrenList"
                        :disabled="!fun('purchaseUploadEdit')"
                >
                    <i v-if="fun('purchaseUploadEdit')" class="el-icon-plus"></i>
                </el-upload>
                <el-dialog v-model="dialogVisible" size="large">
                    <img width="100%" :src="dialogImageUrl" alt="">
                </el-dialog>
            </div>

        <div v-if="yezhuleixing==2">
            <el-form-item label="营业执照"  prop="iscompleteyyzz"  style="margin-bottom: 24px;margin-top:40px;">
                    <el-radio-group v-model="copyForm.iscompleteyyzz">
                        <el-radio :label=1>全</el-radio>
                        <el-radio :label=0>不全</el-radio>
                    </el-radio-group>
                </el-form-item>
            <el-upload
                    action="/purchaseContract/addCopyImage?type=3"
                    list-type="picture-card"
                    :headers="headers"
                    :data="data"
                    multiple
                    :on-preview="handlePictureCardPreview"
                    :on-remove="handleRemove"
                    :on-success="handleSuccess"
                    :file-list="yingyezhizhao"
                    :disabled="!fun('purchaseUploadEdit')"
            >
                <i v-if="fun('purchaseUploadEdit')" class="el-icon-plus"></i>
            </el-upload>
            <el-dialog v-model="dialogVisible" size="large">
                <img width="100%" :src="dialogImageUrl" alt="">
            </el-dialog>
        </div>

        <div v-if="yezhuleixing==2">
            <el-form-item label="法人证件"  prop="iscompletefrzz" style="margin-bottom:  24px;margin-top:40px;">
                    <el-radio-group v-model="copyForm.iscompletefrzz">
                        <el-radio :label=1>全</el-radio>
                        <el-radio :label=0>不全</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-upload
                        action="/purchaseContract/addCopyImage?type=4"
                        list-type="picture-card"
                        :headers="headers"
                        :data="data"
                        multiple
                        :on-preview="handlePictureCardPreview"
                        :on-remove="handleRemove"
                        :on-success="handleSuccess"
                        :file-list="faren"
                        :disabled="!fun('purchaseUploadEdit')"
                >
                    <i v-if="fun('purchaseUploadEdit')" class="el-icon-plus"></i>
                </el-upload>
                <el-dialog v-model="dialogVisible" size="large">
                    <img width="100%" :src="dialogImageUrl" alt="">
                </el-dialog>
        </div>

        <el-form-item label="房产证" prop="iscompletefcz" style="margin-bottom: 24px;margin-top:40px;">
            <el-radio-group v-model="copyForm.iscompletefcz">
                <el-radio :label=1>全</el-radio>
                <el-radio :label=0>不全</el-radio>
            </el-radio-group>
        </el-form-item>
        <el-upload
                action="/purchaseContract/addCopyImage?type=5"
                list-type="picture-card"
                :headers="headers"
                :data="data"
                multiple
                :on-preview="handlePictureCardPreview"
                :on-remove="handleRemove"
                :on-success="handleSuccess"
                :file-list="fangchanzheng"
                :disabled="!fun('purchaseUploadEdit')"
        >
            <i v-if="fun('purchaseUploadEdit')" class="el-icon-plus"></i>
        </el-upload>
        <el-dialog v-model="dialogVisible" size="large">
            <img width="100%" :src="dialogImageUrl" alt="">
        </el-dialog>

        <el-form-item label="不动产授权委托书" prop=""  style="margin-bottom: 24px;margin-top:40px;">
            <el-radio-group v-model="copyForm.iscompletebdcsqwts">
                <el-radio :label=1>全</el-radio>
                <el-radio :label=0>不全</el-radio>
            </el-radio-group>
        </el-form-item>
        <el-upload
                action="/purchaseContract/addCopyImage?type=6"
                list-type="picture-card"
                :headers="headers"
                :data="data"
                multiple
                :on-preview="handlePictureCardPreview"
                :on-remove="handleRemove"
                :on-success="handleSuccess"
                :file-list="budongchan"
                :disabled="!fun('purchaseUploadEdit')"
        >
            <i v-if="fun('purchaseUploadEdit')" class="el-icon-plus"></i>
        </el-upload>
        <el-dialog v-model="dialogVisible" size="large">
            <img width="100%" :src="dialogImageUrl" alt="">
        </el-dialog>

        <el-form-item label="业主授权代理人委托书" prop="" style="margin-bottom: 24px;margin-top:40px;">
            <el-radio-group v-model="copyForm.iscompleteyzsqdlrwts">
                <el-radio :label=1>全</el-radio>
                <el-radio :label=0>不全</el-radio>
            </el-radio-group>
        </el-form-item>
        <el-upload
                action="/purchaseContract/addCopyImage?type=7"
                list-type="picture-card"
                :headers="headers"
                :data="data"
                multiple
                :on-preview="handlePictureCardPreview"
                :on-remove="handleRemove"
                :on-success="handleSuccess"
                :file-list="yezhushouquan"
                :disabled="!fun('purchaseUploadEdit')"
        >
            <i v-if="fun('purchaseUploadEdit')" class="el-icon-plus"></i>
        </el-upload>
        <el-dialog v-model="dialogVisible" size="large">
            <img width="100%" :src="dialogImageUrl" alt="">
        </el-dialog>

            <el-form-item label="委托人身份证" prop="" style="margin-bottom: 24px;margin-top:40px;">
                <el-radio-group v-model="copyForm.iscompletewtrsfz">
                    <el-radio :label=1>全</el-radio>
                    <el-radio :label=0>不全</el-radio>
                </el-radio-group>
            </el-form-item>
            <el-upload
                    action="/purchaseContract/addCopyImage?type=8"
                    list-type="picture-card"
                    :headers="headers"
                    :data="data"
                    multiple
                    :on-preview="handlePictureCardPreview"
                    :on-remove="handleRemove"
                    :on-success="handleSuccess"
                    :file-list="weituoren"
                    :disabled="!fun('purchaseUploadEdit')"
            >
                <i v-if="fun('purchaseUploadEdit')" class="el-icon-plus"></i>
            </el-upload>
            <el-dialog v-model="dialogVisible" size="large">
                <img width="100%" :src="dialogImageUrl" alt="">
            </el-dialog>
        <div style="position: fixed;right:10%;top:50%;">
            <el-button type="primary" v-if="fun('purchaseUploadEdit')"  @click="save" style="margin-top:100px;">保存</el-button>
            <!--<el-button type="warning"   @click="cansel" style="margin-top:100px;">取消</el-button>-->
        </div>

    </el-form>
</template>
<script>
    import {
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
                        id :file.id?file.id:file.response.data,
                    }
                    copyImageDelete(para).then((res)=>{

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
                copyImageList(para).then((res)=>{
                    if(res.data.code=='200'){
                        this.hetongList = res.data.data[1];//合同
                        this.chanquanrenList = res.data.data[2];//合同
                        this.yingyezhizhao = res.data.data[3];//合同
                        this.faren  = res.data.data[4];//合同
                        this.fangchanzheng = res.data.data[5];//合同
                        this.budongchan = res.data.data[6];//合同
                        this.yezhushouquan = res.data.data[7];//合同
                        this.weituoren = res.data.data[8];//合同
                        this.jiaogedan = res.data.data[9];//合同
						if(!this.fun('purchaseUploadDel')){
							setTimeout(function(){
								$('.el-upload-list--picture-card .el-upload-list__item-actions .el-upload-list__item-delete').remove();
							})						
						}
                    }
                })
            },
        },
        mounted(){
            this.data.id = this.$route.query.id;
            getPurchaseContractInfo(this.$route.query).then((res)=>{
                //查数据，得业主leixing
                this.yezhuleixing = res.data.data.yezhuleixing;
            }),
            isCopyCompleteList(this.$route.query).then((res)=>{
                //查数据，得业主leixing
                this.copyForm = res.data.data;
            })
            this.getCopyImageList();
        }


    }
</script>