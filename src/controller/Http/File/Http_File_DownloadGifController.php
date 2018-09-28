<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 27/09/2018
 * Time: 7:26 PM
 */


class Http_File_DownloadGifController extends \HttpBaseController
{
    private $fileType = 'gif';
    public function index()
    {
        $tag = __CLASS__."-".__FUNCTION__;
        $gifId = $_GET['gifId'];
        $returnBase64 = isset($_GET['returnBase64']) ? $_GET['returnBase64'] : "";
        try{
            $result = $this->ctx->SiteUserGifTable->getGifByGifId($gifId);
            if(!$result) {
                echo "failed";
                return;
            }
            $gifUrl = $result['gifUrl'];
            $mimeType = $this->ctx->File_Manager->contentType($gifUrl);
            $fileContent = $this->ctx->File_Manager->readFile($gifUrl, $this->fileType );

            if(strlen($fileContent)<1) {
                throw new Exception("load file void");
            }
            header('Cache-Control: max-age=86400, public');
            header("Content-type:text/html");

            if($returnBase64) {
                $fileContent =  base64_decode($fileContent);
            } else {
                $fileContent =  $fileContent;
            }
            echo $fileContent;
        }catch (Exception $e) {
            header("Content-type:$mimeType");
            $this->ctx->Wpf_Logger->error($tag, "error_msg ==" .$e->getMessage() );
            echo "failed";
        }
    }
}