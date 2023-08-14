<?php

function postImageToServer($imagePath) {
    $url = "https://tgph-figurebed.vercel.app/api/upload/api-tgph-official";
    
    $postData = [
        'image' => new CurlFile($imagePath)
    ];
    
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return "Curl error: " . $error;
    }
    
    curl_close($ch);
    
    return $response;
}

function extractPathFromURL($url) {
    $parsedUrl = parse_url($url);
    
    if (isset($parsedUrl['path'])) {
        return $parsedUrl['path'];
    }
    
    return '';
}


header('Access-Control-Allow-Origin:*'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    // 定义Python脚本路径和上传文件路径
    $uploadedImagePath = $_FILES['image']['tmp_name'];
    $output = postImageToServer($uploadedImagePath);
    // 删除上传的图片
    unlink($uploadedImagePath);
    
    // 返回处理结果
    echo extractPathFromURL($output);
    echo "\n";
} else {
    echo "Invalid request.";
}
?>
