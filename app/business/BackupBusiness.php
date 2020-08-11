<?php


namespace app\business;


use phpspirit\databackup\BackupFactory;
class BackupBusiness
{
    public function backupMysql()
    {
        $backupdir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'backup' . DIRECTORY_SEPARATOR . date('Ymdhis');
        echo $backupdir;
        if (!is_dir($backupdir)) {
            mkdir($backupdir, 0777, true);
        }
        $backup = BackupFactory::instance('mysql', '55a32a9887e03.gz.cdb.myqcloud.com:16273', 'canteen', 'cdb_outerroot', 'Libo1234');
        $backup->setbackdir($backupdir) //设置备份目录
        ->setvolsize(0.2); //设置分卷大小
        do {
            $result = $backup->backup();
            echo str_repeat(' ', 1000); //这里会把浏览器缓存装满
            ob_flush();
            flush();
            if ($result['totalpercentage'] > 0) {
                echo '完成' . $result['totalpercentage'] . '%<br />';
            }
        } while ($result['totalpercentage'] < 100);

    }

}