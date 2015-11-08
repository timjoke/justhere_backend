<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ToolController
 *
 * @author jimmy
 */
class ToolController extends Controller
{

    public function actionUpdatePwd()
    {
        try
        {
            $all_users = Userinfo::model()->findAll();
            if (isset($all_users))
            {
                foreach ($all_users as $value)
                {
                    $pwd = md5($value->password);
                    Userinfo::model()->updateByPk($value->userID, array('password' => $pwd));
                }
            }
        } catch (Exception $e)
        {
            echo 'error:' . $e->getMessage();
        }
        echo 1;
    }

    /**
     * 获取大众点评网站的数据
     */
    public function actionGetDataFromAuto()
    {
        //header('Content-Type: application/json');
        try
        {
            $shopid = isset($_POST["shopid"]) ? $_POST["shopid"] : 0;
            if (!isset($shopid))
            {
                echo json_encode(array(
                    'code' => RETURN_PARAMS_ERROR,
                    'message' => 'params shopid invalid'
                ));
                return;
            }

            $webroot = Yii::getPathOfAlias('webroot');
            if (!file_exists($webroot . "/upload/temp"))
            {
                mkdir($webroot . "/upload/temp", 0777);
            }

            $base_url = 'http://www.dianping.com/shop/';
            $url = $base_url . $shopid;
            $result_data = array();

            //$data = busUlitity::get($url);
            //$data = file_get_contents($url);
            $ch = curl_init($url); //抓取店名，地址
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回  
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.122 Safari/537.36");
            $data_shop = curl_exec($ch);
            curl_close($ch);

//            var_dump($data_shop);
//            exit();

            $html_shop = new simple_html_dom();
            $html_shop->load($data_shop);
            $shop_name = trim($html_shop->find('h1[class=shop-name]', 0)->innertext);
            $shop_name = substr($shop_name, 0, strpos($shop_name, '<'));
            $result_data['shop_name'] = trim($shop_name);
            $result_data['add_region'] = trim($html_shop->find('span[itemprop=locality region]', 0)->innertext);
            $result_data['address'] = trim($html_shop->find('span[itemprop=street-address]', 0)->innertext);

            $url = $url . '/photos';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回  
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.122 Safari/537.36");
            $data_photos = curl_exec($ch);
            curl_close($ch);

            $result_data['photos'] = array();
            $html_photos = new simple_html_dom();
            $html_photos->load($data_photos);
            $all_photos = $html_photos->find('div[class=img] a img');

            if (!isset($all_photos))
            {
                echo json_encode(array(
                    'code' => RETURN_ERROR,
                    'message' => 'data photos empty',
                ));
                return;
            }

            foreach ($all_photos as $value)
            {
                array_push($result_data['photos'], $value->src);
            }
            //var_dump($result_data);
            //exit();

            $result_photos = array();

            //"http://i3.s2.dpfile.com/pc/fe1112f210a9aaee9bad8260c985faba(240c180)/thumb.jpg"

            foreach ($all_photos as $key => $value)
            {
                $image_type = 'image/jpg';
                $title = substr($value->alt, 0, strpos($value->alt, '-'));
                $img_url = str_ireplace('240c180', '700x700', $value->src);
                if (strpos($img_url, 'png') != false)
                {
                    $image_type = 'image/png';
                    //$filename = substr($img_url, 27, 41) . '.png';
                    $filename = $key . '_' . time() . '.png';
                } else if (strpos($img_url, 'jpg') != false)
                {
                    //$filename = substr($img_url, 27, 41) . '.jpg';
                    $filename = $key . '_' . time() . '.jpg';
                } else
                {
                    Yii::log('不支持此格式，跳过,url=' . $img_url, CLogger::LEVEL_ERROR);
                    continue;
                }

                if (!file_exists($webroot . "/upload/temp/" . $filename))
                {
                    try
                    {
                        $img = file_get_contents($img_url);
                        file_put_contents($webroot . "/upload/temp/" . $filename, $img);
                    } catch (Exception $exc)
                    {
                        Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
                        continue;
                    }
                }

                $data_item = new OperResult();
                if (!file_exists($webroot . "/upload/temp/" . "r43_" . $filename))
                {
                    $this->resizeImg($webroot . "/upload/temp/" . $filename, $webroot . "/upload/temp/" . "r43_" . $filename, $image_type);
                    $data_item->filename = "r43_" . $filename;
                } else
                {
                    $data_item->filename = "r43_" . $filename;
                }
                $data_item->filename_src = $filename;
                $data_item->title = $title; //$result_data['shop_name'];
                $data_item->shop_name = $result_data['add_region'] . $result_data['address'] . '-' . $result_data['shop_name'];
                $data_item->id = $key;
                array_push($result_photos, $data_item);
            }

            $result = array(
                'code' => RETURN_SUCCESS,
                'message' => 'success',
                'data' => $result_photos,
                'shop' => $result_data
            );
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            echo json_encode(array(
                'code' => RETURN_ERROR,
                'message' => $ex->getMessage(),
            ));
        }
    }

    public function actionDownloadFile()
    {
        $file = isset($_POST["file"]) ? $_POST["file"] : null;
        if (!isset($file))
        {
            echo json_encode(array(
                'code' => RETURN_ERROR,
                'message' => 'file not exist',
            ));
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
        readfile($file);
        exit;

//        header('Content-Description: File Transfer');
//        header('Content-Type: application/octet-stream');
//        header('Content-Disposition: attachment; filename=' . basename($file));
//        header('Content-Transfer-Encoding: binary');
//        header('Expires: 0');
//        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
//        header('Pragma: public');
//        header('Content-Length: ' . filesize($file));
        //header_remove('Content-Type');
//        var_dump(headers_list());
//        exit();
        //header('Content-Type : application/octet-stream');
//        header("Content-Type: application/force-download");
//        header('Content-Disposition:attachment;filename="' . 'cc.txt');
//        //header('Content-Disposition:attachment;filename="' . basename($file));
//
//        readfile($file);
//        exit;

//        $file = fopen($file, "r");
//        header("Content-Type: application/octet-stream");
//        header("Accept-Ranges: bytes");
//        header("Accept-Length: " . filesize($file));
//        header("Content-Disposition: attachment; filename=文件名称");
//        echo fread($file, filesize('文件地址'));
//        fclose($file);
    }

    /**
     * 获取大众点评网站的数据
     */
    public function actionGetCityNameByid()
    {
        //header('Content-Type: application/json');
        try
        {
            $cityid = isset($_POST["cityid"]) ? $_POST["cityid"] : 0;
            if (!isset($cityid))
            {
                echo json_encode(array(
                    'code' => RETURN_PARAMS_ERROR,
                    'message' => 'params cityid invalid'
                ));
                return;
            }


            $url = 'http://www.dianping.com/search/category/' . $cityid . '/10/';
            $result_data = array();

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回  
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.122 Safari/537.36");
            $data_shop = curl_exec($ch);
            curl_close($ch);

//            var_dump($data_shop);
//            exit();

            $html_shop = new simple_html_dom();
            $html_shop->load($data_shop);
            $city_name = trim($html_shop->find('a[class=city J-city]', 0)->innertext);
            $city_name = substr($city_name, 0, strpos($city_name, '<'));

            $result_data['city_name'] = $city_name;

            $result = array(
                'code' => RETURN_SUCCESS,
                'message' => 'success',
                'data' => $result_data
            );
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            echo json_encode(array(
                'code' => RETURN_ERROR,
                'message' => $ex->getMessage(),
            ));
        }
    }

    /**
     * 获取大众点评网站的数据
     */
    public function actionGetShopidByCity()
    {
        //header('Content-Type: application/json');
        try
        {
            $cityid = isset($_POST["cityid"]) ? $_POST["cityid"] : null;
            $page_offset = isset($_POST["page_offset"]) ? $_POST["page_offset"] : 1;
            $page_count = isset($_POST["page_count"]) ? $_POST["page_count"] : 1;
            if (!isset($cityid))
            {
                echo json_encode(array(
                    'code' => RETURN_PARAMS_ERROR,
                    'message' => 'params cityid invalid'
                ));
                return;
            }

            $base_url = 'http://www.dianping.com/search/category/' . $cityid . '/10/p';
            $result_data = array();
            $filename = '';

            for ($i = 0; $i < $page_count; $i++)
            {
                $url = $cityid . 'p' . ($page_offset + $i);

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回  
                curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.122 Safari/537.36");
                $data_shop = curl_exec($ch);
                curl_close($ch);

                $html_shop = new simple_html_dom();
                $html_shop->load($data_shop);
                if ($filename == '')
                {
                    $filenames = $html_shop->find('span[itemprop=title]');

                    if (isset($filenames) && count($filenames) > 0)
                    {
                        $filename = $filenames[count($filenames) - 1]->innertext;
                        $filename = $filename . '.txt';
                        $filename = str_replace('/', '-', $filename);
                    }
                }

                $shop_ids = $html_shop->find('div[class=pic] a');

                foreach ($shop_ids as $value)
                {
                    array_push($result_data, substr($value->href, 6));
                }
                $last = $result_data[count($result_data) - 1];
                //$result_data[count($result_data) - 1] = $last . '<br/>';
                $result_data[count($result_data) - 1] = $last . "\r\n";
            }

            $result_data = implode(',', $result_data);

            $webroot = Yii::getPathOfAlias('webroot');
            if (!file_exists($webroot . "/upload/shoptxt"))
            {
                mkdir($webroot . "/upload/shoptxt", 0777);
            }

            file_put_contents($webroot . "/upload/shoptxt/" . $filename, $result_data);

            $result = array(
                'code' => RETURN_SUCCESS,
                'message' => 'success',
                'data' => $result_data,
                'filename' => $filename
            );
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            echo json_encode(array(
                'code' => RETURN_ERROR,
                'message' => $ex->getMessage(),
            ));
        }
    }

    /**
     * 获取花瓣网站的数据
     */
    public function actionGetDataFromHuaban()
    {
        //header('Content-Type: application/json');
        try
        {
            $url = isset($_POST["url"]) ? $_POST["url"] : null;
            if (!isset($url))
            {
                echo json_encode(array(
                    'code' => RETURN_PARAMS_ERROR,
                    'message' => 'params invalid'
                ));
                return;
            }
            $webroot = Yii::getPathOfAlias('webroot');
            if (!file_exists($webroot . "/upload/temp"))
            {
                mkdir($webroot . "/upload/temp", 0777);
            }
            $result_data = array();
            //$max_pin_id ++;
            //while (count($result_data) == 0) {
            //    $max_pin_id--;
            //http://huaban.com/search/?q=%E5%AE%B6%E5%B1%85&i2zh8j9u&page=3&per_page=20&wfl=1
            //$url = "http://huaban.com/?i2prvbxi&max=" . $max_pin_id . "&limit=1&wfl=1";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回  
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.122 Safari/537.36");
            curl_setopt($ch, CURLOPT_COOKIE, "_hmt=1; referer=http%3A%2F%2Fwww.baidu.com%2Fs%3Fwd%3D%25E8%258A%25B1%25E7%2593%25A3%25E7%25BD%2591%26rsv_spt%3D1%26issp%3D1%26f%3D8%26rsv_bp%3D0%26rsv_idx%3D2%26ie%3Dutf-8%26tn%3Dbaiduhome_pg; _pk_ref.1.082e=%5B%22%22%2C%22%22%2C1416490614%2C%22http%3A%2F%2Fwww.baidu.com%2Fs%3Fwd%3D%25E8%258A%25B1%25E7%2593%25A3%25E7%25BD%2591%26rsv_spt%3D1%26issp%3D1%26f%3D8%26rsv_bp%3D0%26rsv_idx%3D2%26ie%3Dutf-8%26tn%3Dbaiduhome_pg%22%5D; sid=s9WxZfDPKFyOos9GOAQqQahI.mw21mXeDIKsnRMoEW%2BVhlYVxzW85%2FJWLt4qhnRNtpss; _ga=GA1.2.1075871569.1414659436; _dc=1; _pk_id.1.082e=c541e64b4c644ac2.1416395157.9.1416491557.1416480561.; _pk_ses.1.082e=*; __asc=ed39c95e149cd6b7e04a0c2efa7; __auc=6ab5c374149c7baf01458b4beb3; __UPNS__=true");

            $data = curl_exec($ch);
            curl_close($ch);

            $data_json = json_decode($data);
            if (!isset($data_json))
            {
                echo json_encode(array(
                    'code' => RETURN_ERROR,
                    'message' => 'data empty',
                ));
                return;
            }

            foreach ($data_json->pins as $value)
            {
                $max_pin_id = $value->pin_id;
                if (!file_exists($webroot . "/upload/temp/" . $value->file->key))
                {
                    try
                    {
                        //_fw236:小尺寸图片；_fw658:大尺寸图片
                        $img = file_get_contents("http://img.hb.aicdn.com/" . $value->file->key . "_fw236");
                        file_put_contents($webroot . "/upload/temp/" . $value->file->key, $img);
                    } catch (Exception $exc)
                    {
                        Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
                    }
                }

                $data_item = new OperResult();
                if (!file_exists($webroot . "/upload/temp/" . "r43_" . $value->file->key))
                {
                    if (isset($value->file->type) && $value->file->type != "image/gif")
                    {
                        $this->resizeImg($webroot . "/upload/temp/" . $value->file->key, $webroot . "/upload/temp/" . "r43_" . $value->file->key, $value->file->type);
                        $data_item->filename = "r43_" . $value->file->key;
                    } else
                    {
                        $data_item->filename = $value->file->key;
                    }
                } else
                {
                    $data_item->filename = "r43_" . $value->file->key;
                }
                $data_item->filename = "r43_" . $value->file->key;
                $data_item->filename_src = $value->file->key;
                $data_item->title = $value->raw_text;
                $data_item->id = $value->pin_id;
                array_push($result_data, $data_item);
            }
            //}
//            $start = strpos($result, "app.page[\"pins\"]");
//            $end = strpos($result, "app._csr");
//            $data = substr($result, $start+19, $end-$start-21);
//            var_dump($result1);
//            exit();
            $result = array(
                'code' => RETURN_SUCCESS,
                'message' => 'success',
                'data' => $result_data
            );
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            echo json_encode(array(
                'code' => RETURN_ERROR,
                'message' => $ex->getMessage(),
            ));
        }
    }

    /**
     * 获取花瓣网站的数据
     */
    public function actionGetDataFromHuabanBySearch()
    {
        //header('Content-Type: application/json');
        try
        {
            $q = isset($_POST["q"]) ? $_POST["q"] : null;
            $page = isset($_POST["page"]) ? $_POST["page"] : null;
            if (!isset($q, $page))
            {
                echo json_encode(array(
                    'code' => RETURN_PARAMS_ERROR,
                    'message' => 'params invalid'
                ));
                return;
            }
            $webroot = Yii::getPathOfAlias('webroot');
            if (!file_exists($webroot . "/upload/temp"))
            {
                mkdir($webroot . "/upload/temp", 0777);
            }
            $result_data = array();

            //$param = urlencode("家居");
            //$cc = base64_encode($q);
            $url = "http://huaban.com/search/?q=" . urlencode($q) . "&i2zjopni&page=" . $page . "&per_page=20&wfl=1";
            //$url = "http://huaban.com/search/?q=%E5%AE%B6%E5%B1%85&i2zjopni&page=4&per_page=1&wfl=1";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回  
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.122 Safari/537.36");
            curl_setopt($ch, CURLOPT_COOKIE, "_hmt=1; referer=http%3A%2F%2Fwww.baidu.com%2Fs%3Fwd%3D%25E8%258A%25B1%25E7%2593%25A3%25E7%25BD%2591%26ie%3Dutf-8%26f%3D8%26rsv_bp%3D1%26rsv_idx%3D1%26tn%3Dbaidu%26rsv_pq%3Df2385e1800097f52%26rsv_t%3D67cej6wvzBljUbO6hvYAPe9YeqIAXg%252FYnBBidKI%252BGrDXuB6rFFtKOVsgrek; _dc=1; _pk_ref.1.082e=%5B%22%22%2C%22%22%2C1417053741%2C%22http%3A%2F%2Fwww.baidu.com%2Fs%3Fwd%3D%25E8%258A%25B1%25E7%2593%25A3%25E7%25BD%2591%26ie%3Dutf-8%26f%3D8%26rsv_bp%3D1%26rsv_idx%3D1%26tn%3Dbaidu%26rsv_pq%3Df2385e1800097f52%26rsv_t%3D67cej6wvzBljUbO6hvYAPe9YeqIAXg%252FYnBBidKI%252BGrDXuB6rFFtKOVsgrek%22%5D; __UPNS__=true; _ga=GA1.2.1231855278.1416535449; _pk_id.1.082e=774aecc96d09cd08.1416535449.7.1417053787.1416997293.; _pk_ses.1.082e=*; __asc=8008ef9e149eefc1e65c090295f; __auc=d3bad861149d0179ea71ec9a57e; sid=yCzqBSydzkMxG7EKRacW5JrH.5qK2xbDWnaCkhojrS3M3XaVTs9nhe3Qx6mLAaDJjnq4");
            //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept:application/json", "X-Request:JSON", "Host:huaban.com"));
            $result = curl_exec($ch);
            curl_close($ch);

            $start = strpos($result, "app.page[\"pins\"]") + 19;
            $end = strpos($result, "app.page[\"category\"]");
            $data = substr($result, $start, $end - $start - 2);

            $data_json = json_decode($data);

            if (!isset($data_json))
            {
                echo json_encode(array(
                    'code' => RETURN_ERROR,
                    'message' => 'data empty',
                ));
                return;
            }
            foreach ($data_json as $value)
            {
                $max_pin_id = $value->pin_id;
                if (!file_exists($webroot . "/upload/temp/" . $value->file->key))
                {
                    try
                    {
                        //_fw236:小尺寸图片；_fw658:大尺寸图片
                        $img = file_get_contents("http://img.hb.aicdn.com/" . $value->file->key . "_fw236");
                        file_put_contents($webroot . "/upload/temp/" . $value->file->key, $img);
                    } catch (Exception $exc)
                    {
                        Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
                    }
                }

                $data_item = new OperResult();
                if (!file_exists($webroot . "/upload/temp/" . "r43_" . $value->file->key))
                {
                    if (isset($value->file->type) && $value->file->type != "image/gif")
                    {
                        $this->resizeImg($webroot . "/upload/temp/" . $value->file->key, $webroot . "/upload/temp/" . "r43_" . $value->file->key, $value->file->type);
                        $data_item->filename = "r43_" . $value->file->key;
                    } else
                    {
                        $data_item->filename = $value->file->key;
                    }
                } else
                {
                    $data_item->filename = "r43_" . $value->file->key;
                }

                $data_item->filename_src = $value->file->key;
                $data_item->title = $value->raw_text;
                $data_item->id = $value->pin_id;
                array_push($result_data, $data_item);
            }
            //}
//            $start = strpos($result, "app.page[\"pins\"]");
//            $end = strpos($result, "app._csr");
//            $data = substr($result, $start+19, $end-$start-21);
//            var_dump($result1);
//            exit();
            $result = array(
                'code' => RETURN_SUCCESS,
                'message' => 'success',
                'data' => $result_data
            );
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            echo json_encode(array(
                'code' => RETURN_ERROR,
                'message' => $ex->getMessage(),
            ));
        }
    }

    /**
     * 获取Eput网站的数据
     */
    public function actionGetDataFromEput()
    {
        try
        {
            $url = isset($_POST["url"]) ? $_POST["url"] : null;
            //$count = isset($_POST["count"]) ? $_POST["count"] : null;
            if (!isset($url))
            {
                echo json_encode(array(
                    'code' => RETURN_PARAMS_ERROR,
                    'message' => 'params invalid'
                ));
                return;
            }
            $webroot = Yii::getPathOfAlias('webroot');
            if (!file_exists($webroot . "/upload/temp"))
            {
                mkdir($webroot . "/upload/temp", 0777);
            }
            $result_data = array();
            //while (count($result_data) == 0) {
            //$url = "http://eput.com/api/blockps/dynamic?limit=" . $count . "&excur=" . $max_pin_id;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回  
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.122 Safari/537.36");
            curl_setopt($ch, CURLOPT_COOKIE, "PHPSESSID=co384q58f76qm4i4hs98sggpn1; eput_user_session=C3pTIlI%2FAmtefg07VSUOMgc2ADUEMFZgAyRTLlFzVnNRKFFnUScGZVIxUDFRZgklBD5cfAd9UDsEYldxAm4CcAstUyJSOAJmXj8NalVpDmEHagBnBCZWbAMkU3hROlZlUSxRblF3BidSclA%2FUXEJYgRlXCoHYlAPBHVXbwJvAjcLI1M6UnQCPl5oDTBVMQ44Bz4ANAQ3VmADP1MgUX1WJFE3UWNRJgZ%2FUg9QMFFsCWAEbVwwByVQagQjVzcCNgJjCzdTOFJvAjlebw03VT4OIgcrACAEY1YkA2dTZlE0ViRRYVEgUWUGKVJ8UH5RcQlqBFtcPwdzUCQEI1c8AiACYgsjUyxSdAJ9XjENXlVkDm0HcwAgBD5WdAM2UyBRfVYkUTVRcFEKBmdSNVAoUXcJYgR2XHwHPVByBDFXJAIuAnALb1NyUgkCZl4yDXdVZA5vB2oAIAQ%2BVnQDNlMgUX1WJFE6UXRRNAZ%2FUjFQLlEhCT0EJlwrB3dQPARuV2cCZgIOCy5TYVIgAm5eKA1gVXUOcwdbAC0ENlZmAzdTNlENVilRalEzUWcGPlIMUHNRbgliBGBcNwdyUD0EXlczAjYCZQs1UzFSMAI2XjkNY1VkDjQHYwAwBCpWPAN2U2VRc1YqUXlRYVE0BmZSNVAuUWIJJQQ%2BXDAHclA8BG1XKgIgAj4LZFNuUiUCLV5mDW9Vcg5sB2sALgQmViUDb1NmUXNWPFF5UTJRdwYnUnJQLFFsCXQEcFw7B3FQNQRvV3ICIAJoCyNTMFJ0AiNefg1zVWIOIgc9ADIEKFZ0A2VTbVE8VmpRMlFsUTAGKVJqUG1RNwk2BDJcZgc%2BUGYEMlcwAjsCLw; comline=66133f03bc683842566e1f9cb8892268; token=54741fb83b349; attention_more=nomore; fa=close; Hm_lvt_2f771619ec069dd4df6fd8f6e649f588=1416541860,1416896459; Hm_lpvt_2f771619ec069dd4df6fd8f6e649f588=1416897419");
            $data = curl_exec($ch);
            curl_close($ch);

            $data_json = json_decode($data);

            foreach ($data_json->data as $value)
            {
                $file_names = explode('/', $value->imgurl[0]);
                $file_name = $value->uid;
                if (count($file_names) > 0)
                {
                    $file_name = $file_names[count($file_names) - 1];
                }

                if (!file_exists($webroot . "/upload/temp/" . $file_name))
                {
                    try
                    {
                        //1:小尺寸图片；0:大尺寸图片
                        $img = file_get_contents($value->imgurl[1]);
                        file_put_contents($webroot . "/upload/temp/" . $file_name, $img);
                    } catch (Exception $exc)
                    {
                        Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
                    }
                }
                if (!file_exists($webroot . "/upload/temp/" . "r43_" . $file_name))
                {
                    $this->resizeImg($webroot . "/upload/temp/" . $file_name, $webroot . "/upload/temp/" . "r43_" . $file_name);
                }
                $data_item = new OperResult();
                $data_item->filename_src = $file_name;
                $data_item->filename = "r43_" . $file_name;
                $data_item->title = $value->ptitle;
                array_push($result_data, $data_item);
            }
            $result = array(
                'code' => RETURN_SUCCESS,
                'message' => 'success',
                'data' => $result_data
            );
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            echo json_encode(array(
                'code' => RETURN_ERROR,
                'message' => $ex->getMessage(),
            ));
        }
    }

    /**
     * 在数据库中保存文件信息
     */
    public function actionInsertFile()
    {
        try
        {
            $file_name = isset($_POST["file_name"]) ? $_POST["file_name"] : null;
            if (!isset($file_name))
            {
                echo json_encode(array(
                    'code' => RETURN_PARAMS_ERROR,
                    'message' => 'params invalid'
                ));
                return;
            }

            $webroot = Yii::getPathOfAlias('webroot');
            if (!file_exists($webroot . "/upload/other"))
            {
                mkdir($webroot . "/upload/other", 0777);
            }
            if (file_exists($webroot . "/upload/temp/" . $file_name) && !file_exists($webroot . "/upload/other/" . $file_name))
            {
                rename($webroot . "/upload/temp/" . $file_name, $webroot . "/upload/other/" . $file_name);
            }

            $file = new Fileinfo();
            $file->filename = $file_name;
            $file->filepath = "/upload/other/" . $file_name;
            $file->uploadtime = date("Y-m-d H:m:s");
            $file->insert();
            $file_id = $file->primaryKey;

            echo json_encode(array(
                'code' => RETURN_SUCCESS,
                'message' => 'success',
                'data' => $file_id
            ));
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            echo json_encode(array(
                'code' => RETURN_ERROR,
                'message' => $ex->getMessage(),
            ));
        }
    }

    /**
     * 获取随机位置
     */
    public function actionGetRandomPostion()
    {
        try
        {
            $sql = "SELECT longitude,latitude from t_locationlog ORDER BY rand() LIMIT 1";
            $location = LocationLog::model()->findBySql($sql);
            $position = new OperResult();
            $position->lng = $location->longitude;
            $position->lat = $location->latitude;
            echo json_encode(array(
                'code' => RETURN_SUCCESS,
                'message' => 'success',
                'data' => $position
            ));
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            echo json_encode(array(
                'code' => RETURN_ERROR,
                'message' => $ex->getMessage(),
            ));
        }
    }

    //裁切图片
    public function actionCutImage()
    {
        try
        {
            $old_file_name = isset($_POST["old_file_name"]) ? $_POST["old_file_name"] : null;
            $x = isset($_POST["x"]) ? $_POST["x"] : null;
            $y = isset($_POST["y"]) ? $_POST["y"] : null;
            $x2 = isset($_POST["x2"]) ? $_POST["x2"] : null;
            $y2 = isset($_POST["y2"]) ? $_POST["y2"] : null;
            $w = isset($_POST["w"]) ? $_POST["w"] : null;
            $h = isset($_POST["h"]) ? $_POST["h"] : null;
            if (!isset($old_file_name, $x, $y, $x2, $y2, $w, $h))
            {
                echo json_encode(array(
                    'code' => RETURN_PARAMS_ERROR,
                    'message' => 'params invalid'
                ));
                return;
            }
            $webroot = Yii::getPathOfAlias('webroot');
            if (!file_exists($webroot . "/upload/temp"))
            {
                mkdir($webroot . "/upload/temp", 0777);
            }
            if (!file_exists($webroot . "/upload/temp/" . $old_file_name))
            {
                echo json_encode(array(
                    'code' => RETURN_ERROR,
                    'message' => 'file not exist!'
                ));
                return;
            }

            list($width, $height, $type, $attr) = getimagesize($webroot . "/upload/temp/" . $old_file_name); //获取大图属性
            if ($type == 3)
            {//png
                $image = imagecreatefrompng($webroot . "/upload/temp/" . $old_file_name);
            } else if ($type == 2)
            {//jpg
                $image = imagecreatefromjpeg($webroot . "/upload/temp/" . $old_file_name);
            } else
            {
                echo json_encode(array(
                    'code' => RETURN_ERROR,
                    'message' => '无法识别的格式!'
                ));
                return;
            }
            $im = @imagecreatetruecolor($w, $h) or die("Cannot Initialize new GD image stream");
            imagecopy($im, $image, 0, 0, $x, $y, $w, $h);

            $filename_to = "r43_" . date("U") . "_" . $old_file_name;
            if (file_exists($webroot . "/upload/temp/" . $filename_to))
            {
                unlink($webroot . "/upload/temp/" . $filename_to);
            }

            imagejpeg($im, $webroot . "/upload/temp/" . $filename_to, 100); //生成图片 定义命名规则
            imagedestroy($im);

            echo json_encode(array(
                'code' => RETURN_SUCCESS,
                'message' => 'success',
                'data' => $filename_to
            ));
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            echo json_encode(array(
                'code' => RETURN_ERROR,
                'message' => $ex->getMessage(),
            ));
        }
    }

    public function actionLocationTest()
    {
        try
        {
            $busApi = new busApi();
            $result = $obj = $busApi->locationTest();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    public function actionLocationGet()
    {
        try
        {
            $busApi = new busApi();
            $result = $obj = $busApi->locationGet();
            echo json_encode($result);
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            $this->error($ex);
        }
    }

    private function resizeImg($filename_from, $filename_to, $filetype = "image/jpeg")
    {
//                var_dump($filename_from);
//        exit();
        header('Content-type: image/jpg');
        try
        {
            list($width, $height, $type, $attr) = getimagesize($filename_from); //获取大图属性

            if ($type == 3)
            {
                $image = imagecreatefrompng($filename_from);
            } else if ($type == 2)
            {
                $image = imagecreatefromjpeg($filename_from);
            } else
            {
                return;
            }
        } catch (Exception $ex)
        {
            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
            return;
        }

//        if ($filetype == "image/png") {
//            $image = imagecreatefrompng($filename_from);
//        } else {
//            $image = imagecreatefromjpeg($filename_from);
//        }

        $sacle = $height / $width;

//        //不补黑边，裁切图片
//        if ($sacle<0.75)
//        {
//            $new_width = ceil($height*4/3);
//            $im = @imagecreatetruecolor($new_width, $height) or die("Cannot Initialize new GD image stream");
//            $colBG = imagecolorallocate($im, 0, 0, 0);
//            imagefill($im, 0, 0, $colBG); //创建背景为黑色的图片
//            imagecopy($im, $image, 0, 0, ($width-$new_width)/2, 0, $new_width, $height);
//        }
//        else if($sacle>=0.75)
//        {
//            $new_height = ceil($width*3/4);
//            $im = @imagecreatetruecolor($width, $new_height) or die("Cannot Initialize new GD image stream");
//            $colBG = imagecolorallocate($im, 0, 0, 0);
//            imagefill($im, 0, 0, $colBG); //创建背景为黑色的图片
//            imagecopy($im, $image, 0, 0, 0,($height-$new_height)/2, $width, $new_height);
//        }
        //补黑边
        if ($sacle > 0.75)
        {
            $new_width = ceil($height * 4 / 3);
            $im = @imagecreatetruecolor($new_width, $height) or die("Cannot Initialize new GD image stream");
            $colBG = imagecolorallocate($im, 0, 0, 0);
            imagefill($im, 0, 0, $colBG); //创建背景为黑色的图片
            imagecopy($im, $image, 0, 0, ($width - $new_width) / 2, 0, $new_width, $height);
        } else if ($sacle <= 0.75)
        {
            $new_height = ceil($width * 3 / 4);
            $im = @imagecreatetruecolor($width, $new_height) or die("Cannot Initialize new GD image stream");
            $colBG = imagecolorallocate($im, 0, 0, 0);
            imagefill($im, 0, 0, $colBG); //创建背景为黑色的图片
            imagecopy($im, $image, 0, 0, 0, ($height - $new_height) / 2, $width, $new_height);
        }

//        for ($i = 0; $i < ceil($width / $picW); $i++)
//        {
//            for ($j = 0; $j < ceil($height / $picH); $j++)
//            {
//
//                //为获取不完整图片坐标
//                $picX = ($picW * ($i + 1)) < $width ? $picW : ($picW + $width - $picW * ($i + 1));
//                $picY = ($picW * ($j + 1)) < $height ? $picW : ($picW + $height - $picW * ($j + 1));
//                imagecopy($im, $image, 0, 0, ($picW * $i), ($picH * $j), $picX, $picY);
//            }
//        }
        imagejpeg($im, $filename_to, 100); //生成图片 定义命名规则
        imagedestroy($im);
    }

    //    /**
//     * 获取花瓣网站的数据有查询条件
//     */
//    public function actionGetDataFromHuabanBySearch_cc()
//    {
//        //header('Content-Type: application/json');
//        try
//        {
//            $url = isset($_POST["url"]) ? $_POST["url"] : null;
//            if (!isset($url))
//            {
//                echo json_encode(array(
//                    'code' => RETURN_PARAMS_ERROR,
//                    'message' => 'params invalid'
//                ));
//                return;
//            }
//            $webroot = Yii::getPathOfAlias('webroot');
//            if (!file_exists($webroot . "/upload/temp"))
//            {
//                mkdir($webroot . "/upload/temp", 0777);
//            }
//            $result_data = array();
//
//            $url = "http://huaban.com/search/?q=家居&qq-pf-to=pcqq.group";
//            //$url = "http://huaban.com/?i2prvbxi&max=" . $max_pin_id . "&limit=1&wfl=1";
////            $ch = curl_init($url);
////            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回  
////            curl_setopt($ch, CURLOPT_BINARYTRANSFER, false); // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
////            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.122 Safari/537.36");
////            //curl_setopt($ch, CURLOPT_COOKIE, "_UPNS__=true; _hmt=1; _dc=1; _pk_ref.1.082e=%5B%22%22%2C%22%22%2C1416993605%2C%22http%3A%2F%2Fwww.baidu.com%2Fs%3Fie%3Dutf-8%26f%3D3%26rsv_bp%3D1%26rsv_idx%3D1%26tn%3Dbaidu%26wd%3D%25E8%258A%25B1%25E7%2593%25A3%25E7%25BD%2591%26rsv_pq%3De6c5e51400011ab7%26rsv_t%3Df451Iex9yZ2MEiuq74hhi4W%252B%252BKVb13671wLZbF4GLIJ6QWI1m8qKhb3wrzY%26rsv_enter%3D1%26inputT%3D2729%26rsv_sug3%3D10%26rsv_sug4%3D401%26rsv_sug1%3D9%26oq%3Dhuaban%26rsv_sug2%3D1%26rsp%3D1%26rsv_sug%3D1%22%5D; sid=yCzqBSydzkMxG7EKRacW5JrH.5qK2xbDWnaCkhojrS3M3XaVTs9nhe3Qx6mLAaDJjnq4; _ga=GA1.2.1231855278.1416535449; _pk_id.1.082e=774aecc96d09cd08.1416535449.5.1416993613.1416991364.; _pk_ses.1.082e=*; __asc=38579b3c149eb6685d885711219; __auc=d3bad861149d0179ea71ec9a57e");
////            $result = curl_exec($ch);
////            curl_close($ch);
//            //$url = urlencode($url);
//
//            $result = file_get_contents($url);
////            var_dump($result1);
////            exit();
//
//            $start = strpos($result, "app.page[\"pins\"]") + 19;
//            $end = strpos($result, "app.page[\"category\"]");
//            $data = substr($result, $start, $end - $start - 2);
//
//
//            $data_json = json_decode($data);
////            var_dump($data_json);
////            exit();
//
//            foreach ($data_json as $value)
//            {
//                $max_pin_id = $value->pin_id;
//                if (!file_exists($webroot . "/upload/temp/" . $value->file->key))
//                {
//                    try
//                    {
//                        //_fw236:小尺寸图片；_fw658:大尺寸图片
//                        $img = file_get_contents("http://img.hb.aicdn.com/" . $value->file->key . "_fw236");
//                        file_put_contents($webroot . "/upload/temp/" . $value->file->key, $img);
//                    }
//                    catch (Exception $exc)
//                    {
//                        Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
//                    }
//                }
//                if (!file_exists($webroot . "/upload/temp/" . "r43_" . $value->file->key . "_fw236"))
//                {
//                    $this->resizeImg($webroot . "/upload/temp/" . $value->file->key . "_fw236", $webroot . "/upload/temp/" . "r43_" . $value->file->key . "_fw236");
//                }
//                $data_item = new OperResult();
//                $data_item->filename = $value->file->key;
//                $data_item->title = $value->raw_text;
//                $data_item->id = $value->pin_id;
//                array_push($result_data, $data_item);
//            }
//
//            $result = array(
//                'code' => RETURN_SUCCESS,
//                'message' => 'success',
//                'data' => $result_data
//            );
//            echo json_encode($result);
//        }
//        catch (Exception $ex)
//        {
//            Yii::log('msg:' . $ex->getMessage() . ',trace:' . $ex->getTraceAsString(), CLogger::LEVEL_ERROR);
//            echo json_encode(array(
//                'code' => RETURN_ERROR,
//                'message' => $ex->getMessage(),
//            ));
//        }
//    }
}
