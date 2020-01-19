<?php
class Bing
{
    private $host;
    private $url;
    private $filename;
    public function __construct()
    {
        $this->host     = 'https://cn.bing.com';
        $this->url      = 'https://cn.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1';
        $this->filename = __DIR__ . '/bing/' . date('Y-m-d') . '.jpg';
    }
    public function run()
    {
        if (file_exists($this->filename)) {
            $this->show();
            return;
        }
        $data  = json_decode(file_get_contents($this->url));
        $image = file_get_contents($this->host . $data->images[0]->url);
        if (! file_exists(__DIR__ . '/bing/')) {
            mkdir(__DIR__ . '/bing/', 0777) or die('创建目录失败，请检查权限。');
        }
        file_put_contents($this->filename, $image);
        $this->show();
    }
    private function show()
    {
        if ($this->isCli()) {
            echo 'done.';
        } else {
            header('Expires: ' . gmdate('D, d M Y H:i:s', strtotime(date('Y-m-d', strtotime('+1 day')))) . ' GMT');
            header('Cache-Control: public, max-age=3600');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', strtotime(date('Y-m-d'))) . ' GMT');
            header('Content-Type: image/jpeg');
            readfile($this->filename);
        }
    }
    private function isCli()
    {
        preg_match('/cli/', PHP_SAPI, $isCli);
        return boolval($isCli);
    }
}
(new Bing())->run();