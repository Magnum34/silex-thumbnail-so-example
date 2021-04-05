<?php

use ThumbnailSo\DriverManagerInterface;
use ThumbnailSo\Exceptions\ThumbnailSoException;
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxFile;

class DropboxDriver implements DriverManagerInterface {

    // Name driver - provider
    public function getName(){
        return 'dropbox';
    }

    // Name configuration driver
    public function getConfigName(){
        return "dropbox";
    }

    // data configuration driver
    public function getConfig(){
        return [
            'client_id' => $_ENV['DROPBOX_CLIENT_ID'],
            'client_secret' => $_ENV['DROPBOX_CLIENT_SECRET'],
            'token' => $_ENV['DROPBOX_ACCESS_TOKEN']
        ];

    }

    // Record rule for the driver
    public function afterSave(string $source_image, string $destination_dir, string $destination_name, string $extension){
        $config = $this->getConfig();
        try {
            $app = new DropboxApp($config['client_id'], $config['client_secret'],$config['token']);
            $dropbox = new Dropbox($app);
            $dropboxFile = new DropboxFile($source_image);
            $dropbox->simpleUpload($dropboxFile , "/{$destination_dir}/{$destination_name}.{$extension}", ['autorename' => true]);
           
        }catch(\Exception $exc){
            throw new ThumbnailSoException($exc->getMessage());
        }

    }
}