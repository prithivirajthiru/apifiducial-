<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Utils\ApiResponse;

class FileUploader 
{
   

    public function upload($uploadDir, $file, $filename) 
    {
        try {

            $file->move($uploadDir, $filename);
        } catch (FileException $e){

            
            return new ApiResponse( [ $e->getMessage()],400,[],'json','fail to upload');
            throw new FileException('Failed to upload file');
        }
    }
    public function fileupload($foldername,$uploadDir, $file, $filename) 
    {
        try {

            $file->move($foldername,$uploadDir, $filename);
        } catch (FileException $e){

            
            return new ApiResponse( [ $e->getMessage()],400,[],'json','fail to upload');
            throw new FileException('Failed to upload file');
        }
    }

    // public function excelUpload($uploadDir, $file, $filename) 
    // {
    //     try {
    //         $path='/public/Excelsheet'.'/'.$uploadDir.'/'.$filename;
    //         $file->save($path);
    //     } catch (FileException $e){

            
    //         return new ApiResponse( [ $e->getMessage()],400,[],'json','fail to upload');
    //         throw new FileException('Failed to upload file');
    //     }
    // }
} 



    
