<?php

/**
 * Created by PhpStorm.
 * User: CoolKid
 * Date: 08.01.14
 * Time: 15:27
 */
class FUploader extends FFile
{
    protected $new_path = null;
    protected $allowed_mime_types = array();
    protected $allowed_extensions = array();
    protected $new_name = null;

    public function construct($upl_file, $allowed_mime_types = array(), $allowed_extensions = array())
    {
        $this->allowed_mime_types = $allowed_mime_types;
        $this->allowed_extensions = $allowed_extensions;

        return parent::__construct($upl_file);
    }

    public function upload()
    {
        $this->checkExtension($this->allowed_extensions)
            ->checkMimeType($this->allowed_mime_types)
            ->save()
            ->register();
    }

    protected function makeNewName()
    {
        return sha1($this->file_name . Lib::genRandID());
    }

    protected function makeNewPath($new_name)
    {
        return UPLOAD_DIR . '/' . substr($new_name, 0, 3);
    }

    public function save()
    {
        $this->new_name = $this->makeNewName();
        $this->new_path = $this->makeNewPath($this->new_name);
        $this->move($this->new_path, $this->new_name);

        return $this;
    }

    public function register()
    {
        $sql = "INSERT INTO fpst_files (upload_date,size,mime,path,newname,oldname,description)
              VALUES (NOW(),?, ?, ? ,? ,?, NULL)";

        DB::prepare($sql)->execute(
            array(
                $this->file_size,
                $this->mime_type,
                $this->new_path,
                $this->new_name,
                $this->file_name
            )
        );
    }

}
