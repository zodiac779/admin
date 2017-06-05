<?php
ob_start(); 
if (!isset($_SESSION)) { session_start(); }

if(isset($_SESSION["mcf_membervalid"]) && $_SESSION["mcf_membervalid"]){
		//return true;
    if (!function_exists("imageUpload")) {
        function imageUpload($p_name,$savefolder){
            $iWidth = $_POST[$p_name.'_iWidth'];
            $iHeight = $_POST[$p_name.'_iHeight'];
            $iJpgQuality = 90;
            $aspectRatio = $iWidth/$iHeight;
            $fullname= $_FILES[$p_name]['name'];
            $array_name = explode(".", strtolower($fullname));
            $type = '.'.end($array_name);
            $name='';
            for($i=0;$i<sizeof($array_name)-1;$i++)
                if($i==(sizeof($array_name)-2))$name.=$array_name[$i];else $name.=$array_name[$i].'.';

            //BEGIN rename duplicate file tiamstamp
            // if (file_exists($savefolder.$name.$type))
            //     $name.=date("_Y-m-d-h-m-s");
            //END rename duplicate file tiamstamp

            //BEGIN rename duplicate file count
            $teamp_name = $name;
            $count=0;
            while (file_exists($savefolder.$teamp_name.$type)){
                $count++;
                $teamp_name=$name.'_'.$count;
            }
            $name = $teamp_name;
            //END rename duplicate file count

            // random filename
            // $image = md5(time().rand());

            $sTempFileName = $savefolder . $name;
            move_uploaded_file($_FILES[$p_name]['tmp_name'], $sTempFileName);
            // change file permission to 644
            @chmod($sTempFileName, 0644);
            if (file_exists($sTempFileName) && filesize($sTempFileName) > 0) {
                $aSize = getimagesize($sTempFileName); // try to obtain image info
                if (!$aSize) {
                    @unlink($sTempFileName);
                    return;
                }
                // check for image type
                switch($aSize[2]) {
                    case IMAGETYPE_JPEG:
                        $sExt = '.jpg';
                        // create a new image from file 
                        $vImg = @imagecreatefromjpeg($sTempFileName);
                        break;
                    case IMAGETYPE_PNG:
                        $sExt = '.png';
                        // create a new image from file 
                        $vImg = @imagecreatefrompng($sTempFileName);
                        break;
                    case IMAGETYPE_GIF:
                        $sExt = '.gif';
                        // create a new image from file 
                        $vImg = @imagecreatefrompng($sTempFileName);
                        break;
                    case IMAGETYPE_BMP:
                        $sExt = '.bmp';
                        // create a new image from file 
                        $vImg = @imagecreatefrompng($sTempFileName);
                        break;
                    default:
                        @unlink($sTempFileName);
                        return;
                }

	            $x1 = 0;
	            if(isset($_POST[$p_name.'_x1']) and $_POST[$p_name.'_x1'] != '')
	            	$x1=(int)$_POST[$p_name.'_x1'];
	            $y1 = 0;
	            if(isset($_POST[$p_name.'_y1']) and $_POST[$p_name.'_y1'] != '')
	            	$y1=(int)$_POST[$p_name.'_y1'];
	            $w = 0;
	            $h = 0;
	            if((isset($_POST[$p_name.'_w']) and $_POST[$p_name.'_w'] != '') and (isset($_POST[$p_name.'_h']) and $_POST[$p_name.'_h'] != '')){
	            	$w=(int)$_POST[$p_name.'_w'];
	            	$h=(int)$_POST[$p_name.'_h'];
	            }else{
	            	if($aspectRatio>=0){
	            		$w=imagesx($vImg);
	            		$h=imagesx($vImg)*($iHeight/$iWidth);
	            	}else{
	            		$w=imagesy($vImg)*($iWidth/$iHeight);
	            		$h=imagesy($vImg);
	            	}
            	}
                // create a new true color image
                $vDstImg = @imagecreatetruecolor( $iWidth, $iHeight );
                imagealphablending($vDstImg, false);
                imagesavealpha($vDstImg, true);
                // copy and resize part of an image with resampling
                imagecopyresampled($vDstImg, $vImg, 0, 0, $x1, $y1, $iWidth, $iHeight, $w, $h);

                // define a result image filename
                $sResultFileName = $sTempFileName . $sExt;
                // output image to file
                switch ($sExt) {
                    case '.png':
                    case '.gif':
                        imagepng($vDstImg, $sResultFileName, 9);
                        break;
                    default:
                        imagejpeg($vDstImg, $sResultFileName, $iJpgQuality);
                        break;
                }
                $filename = $name.$sExt;
                @unlink($sTempFileName);
                if(isset($_POST[$p_name.'_watermark']) and $_POST[$p_name.'_watermark']=='true'){
                    mysql_select_db(database_conn_database, conn_database);
                    $query_rs_set = "SELECT * FROM setting WHERE id = 1";
                    $rs_set = mysql_query($query_rs_set, conn_database) or die(mysql_error());
                    $row_rs_set = mysql_fetch_assoc($rs_set);
                    $totalRows_rs_set = mysql_num_rows($rs_set);
                    $watermark_file = '../../images/watermark/'.$row_rs_set['watermark'];

                    $stamp = imagecreatefrompng($watermark_file);
                    $im = imagecreatefromjpeg($sResultFileName);

                    // Set the margins for the stamp and get the height/width of the stamp image
                    $marge_right = 0;
                    $marge_bottom = 0;
                    $sx = imagesx($stamp);
                    $sy = imagesy($stamp);

                    // Copy the stamp image onto our photo using the margin offsets and the photo 
                    // width to calculate positioning of the stamp. 
                    imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
                    imagepng($im,$sResultFileName);
                    imagedestroy($im);
               }
            }else{
                $filename = false;
            }
            return $filename;
        }
    }
}else{
		$_SESSION = array(); //destroy all of the session variables
    	session_destroy();
		header('Location: ../login/index.php?error=login');
}
?>