<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/26 0026
 * Time: 上午 9:50
 */
namespace common\service;

use yii\base\Model;
use yii\web\UploadedFile;
use OSS\OssClient;
use OSS\Core\OssException;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

    /**
     * @var folder
     */
    public $folder;

    /**
     * @var object
     */
    public $object;

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, gif'],
            ['folder', 'required'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->setObject();
            $runtimePath = \Yii::getAlias("@runtime");
            $savePath = $runtimePath . DIRECTORY_SEPARATOR . 'images';

            $filePath = $savePath . DIRECTORY_SEPARATOR . $this->file->baseName . '.' . $this->file->extension;

            if (!is_dir($savePath)) {
                // 创建目录
                mkdir($savePath, 0777, true);
            }

            $this->file->saveAs($filePath);

            $aliyunOss = \Yii::$app->params['aliyunOss'];
            try{
                $ossClient = new OssClient($aliyunOss['accessKeyId'], $aliyunOss['accessKeySecret'], $aliyunOss['endPoint']);

                $ossClient->uploadFile($aliyunOss['bucket'], $this->object, $filePath);
                unlink($filePath);
            } catch(OssException $e) {
                $this->addError('file', $e->getMessage());
                return false;
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取对象名称
     * @return string
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * 生成对象名称
     * @return string
     */
    public function setObject()
    {
        $this->object = $this->folder . DIRECTORY_SEPARATOR . date('Y/m') . DIRECTORY_SEPARATOR . uniqid();
    }
}