<?php
/**
 *Author: zff
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\lib\exception\FileException;
use app\api\model\Resource as resourceModel;

class Upload extends BaseController
{
    public function uploadFile() {

        /*
         * 后台接口的上传文件逻辑
         * 1、接收到文件，通过框架的功能上传好，放在指定文件夹，获取放好后文件的相关参数 后缀 地址 名称
         * 2、将图片数据存到数据库中
         * 3、返回状态
         *
         * */
        $file = request()->file('file');

        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $data['name'] = $info->getFilename();
                $data['url'] = $info->getSaveName();
                $data['type'] = explode('.', $info->getFilename())[1];
                resourceModel::create($data);

                $result = [
                        "data" => [
                            "tmp_path"=> $info->getSaveName(),
                            "url"=> "http://www.gmkj.com/uploads/" . $info->getSaveName(),
                            "id" => resourceModel::getLastInsID()
                        ],
                        "meta" => [
                            "msg"=> "上传成功",
                            "status"=> 200
                        ]
                    ];
                return $result;
            }else{
                throw new FileException([
                    'code' => 404,
                    'msg' => $file->getError(),
                    'errorCode' => 90003
                ]);

            }
        }



    }
}