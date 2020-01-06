<?php
echo "<pre>";
class fileupload{
    // 允许上传的图片后缀
    public $path = "./upload/";          //上传文件保存的路径
    public $allowtype = array('jpg','jpeg','png'); //设置限制上传文件的类型
    public $maxsize = 10240;           //限制文件上传大小（字节）
    public $originName;              //源文件名
    public $tmpFileName;              //临时文件名
    public $fileType;               //文件类型(文件后缀)
    public $fileSize;               //文件大小
    public $newFileName;              //新文件名


    /* 设置和$_FILES有关的内容 */

    function getname(){
        $this->fileName = $_FILES["file"]["name"];
        $this->fileSize = ($_FILES["file"]["size"] /1024);
        $this->tmpFileName = explode(".", $_FILES["file"]["name"]);
        $this->fileType = end($this->tmpFileName);
        $this->checkType();
    }

    function checkType(){
        if(in_array($this->fileType,$this->allowtype) ){
            $this->checkSize();
        }else{
            echo"文件类型不正确";
            return false;
        }
    }

    function checkSize(){
        if ($this->fileSize > $this->maxsize) {
            echo"文件超出最大限制";
            return false;
        }else{
            $this->setnewName();
            $this->ifExists();
        }
    }

    function setnewName(){
        date_default_timezone_set('PRC');//中国时区
        $D = date("Ymd");//时间戳
        $this->newFileName = $this->fileName .$D . "." . $this->fileType; //文件新名字
    }

    function ifExists(){
        if(file_exists("upload/" . $this->newFileName)) {
            echo $this->newFileName . "<br> 文件已经存在。 ";
            return false;
        }
        else{
            $arr = [
                "文件类型"=>$this->fileType,
                "文件大小"=>$this->fileSize,
                "上传文件本地保存路径:" =>  "./upload/" . $this->newFileName,
            ] ;
            print_r($arr);
            $fileName = $this->path.time().md5('asdf').'.'.$this->fileType;
            move_uploaded_file($_FILES['file']['tmp_name'],$fileName);

            $allimgs = scandir($this->path);
            unset($allimgs[0],$allimgs[1]);
            foreach ($allimgs as $values){
                $img = $this->path.$values;

                echo "<a href=$img download=$img><img src=$img style=width:200px;height:200px ></a>";
            }

        }
    }

}

class file extends fileupload{
    public function load(){
        $this->getname();
    }
}

$up = new file;

$up-> load();


?>