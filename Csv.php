   public function changeArrayToCsv($arr, $head, $filename) {
        if (substr($filename, -4) != '.csv') {
            return null;
        }
        if (count($arr) == 0) {
            return null;
        }

        @self::download_send_headers($filename);
        ob_start();
        $df = fopen("php://output", 'w');
        if(!$head){
            $head=array_keys(reset($arr));
        }
        fputcsv($df,$head);
        foreach ($arr as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        return ob_get_clean();
    }
    protected  function download_send_headers($filename) {
        // 不缓存
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");
        //强制下载
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }
