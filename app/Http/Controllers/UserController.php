<?php
namespace App\http\Controllers;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
/**
 *
 */
class UserController extends Controller
{
    public function getlist()
    {
        $name = Input::get('name');
        $pageSize = Input::get('pageSize');
        $page = Input::get('page');
        $count = DB::table('users')
                ->where("users.name","like","%$name%")
                ->count();
        $users =  DB::table('users')
            ->leftJoin('role_user','role_user.user_id','=','users.id')
            ->leftJoin('roles','roles.id','=','role_user.role_id')
            ->where("users.name","like","%$name%")
            ->select("users.id","users.name","users.sex","users.email","users.created_at",DB::raw('GROUP_CONCAT(roles.name) as role'))
            ->groupBy("users.id","users.name","users.sex","users.email","users.created_at")
            ->offset(($page-1)*$pageSize)
            ->limit($pageSize)
            ->get();

        return $data = ['total'=>$count,'data'=>$users];

    }
    public function delete()
    {
        $id = Input::get('params')['id'];
        return User::destroy($id);
    }
    public function info()
    {
        $id = Input::get('params')['id'];
        return User::find($id);
    }
    public function addUser(Request $request)
    {
        $input = $request->params;
        $user = new User();
        $user->name = $input['name'];
        $user->password = bcrypt('123456');
        $user->email = $input['email'];
        $user->phone = $input['phone'];
        $user->sex = $input['sex'];
        if($user->save()){
            return [
                'msg'=>'保存成功！',
                'code'=>200
            ];
        }
    }
    public function editUser(Request $request)
    {
        $input = $request->params;
        $id = $input['id'];
        $user = User::find($id);
        $user->name = $input['name'];
        //$user->password = bcrypt('secret');
        $user->email = $input['email'];
        $user->phone = $input['phone'];
        $user->sex = $input['sex'];
        if($user->save()){
            return [
                'msg'=>'保存成功！',
                'code'=>200
            ];
        }
    }
    public function batchRemoveUser(Request $request)
    {
        $ids = $request->params['ids'];
        $arr = explode(',',$ids);
        User::destroy($arr);
    }
    /*
     * 给设置角色
     * */
    public function setRole(Request $request ,$id){
        $input = $request->params;
        //角色全部删除
        DB::table('role_user')->where('user_id' ,'=',$id)->delete();
        $data = [];
        $res = DB::table('roles')->whereIn('name',$input['value'])->select('id')->get();
        foreach ($res as $key=> $value){
            $data[$key]['role_id'] = $value->id;
            $data[$key]['user_id'] = $id;
        }
        //设置
        DB::table('role_user')->insert($data);
    }
}