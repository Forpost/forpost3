<?php

/**
 * Created by PhpStorm.
 * User: CoolKid
 * Date: 08.01.14
 * Time: 2:17
 */
class FFile extends AFClass
{
    protected $file_path = null;
    protected $file_name = null;
    protected $file_ext = null;
    protected $file_size = null;
    protected $mime_type = null;

    public function construct($file_path = null)
    {
        if (is_null($file_path) || Lib::chkFile($file_path)) {
            $this->file_path = $file_path;
            $this->file_name = basename($this->file_path);
            $this->file_size = filesize($this->file_path);
            $this->mime_type = $this->detectMimeType();
            $this->file_ext = $this->getExtension();
        } else {
            throw new FException('Filename is not specified or file does not exists');
        }

        return $this;
    }

    public function getExtension()
    {
        $arr = explode('.', $this->file_name);

        if (is_array($arr) && count($arr) > 1) {
            return strtolower(array_pop($arr));
        }

        return '';
    }

    public function getFileSize()
    {
        return $this->file_size;
    }

    public function getFileMimeType()
    {
        return $this->mime_type;
    }

    public function checkMimeType($mime_types = array())
    {
        if (Lib::chkArr($mime_types)) {

            if (in_array($this->mime_type, $mime_types)) {
                return $this;
            }

            throw new FException('Check MIME failed');
        }

        return $this;
    }

    public function checkExtension($extensions = array())
    {
        if (Lib::chkArr($extensions)) {

            if (in_array($this->file_ext, $extensions)) {
                return $this;
            }

            throw new FException('Check MIME failed');
        }

        return $this;
    }

    protected function detectMimeType()
    {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($this->file_path);
        unset($finfo);

        return $mime_type;
    }

    public function setNewName($file_name)
    {
        $this->file_name = $file_name;

        return $this;
    }

    public function copy($new_path, $new_name = null)
    {
        $new_path = rtrim($new_path, '/');

        if (!Lib::chkDir($new_path)) {

            if (!mkdir($new_path, octdec(Config::get('sys.dir_chmod')), true)) {
                throw new FException('Can`t create directory');
            }

        } elseif (!Lib::chkDirWrite($new_path)) {
            throw new FException('Directory not writable');
        }

        if (!is_null($new_name)) {
            $dest_path = $new_path . '/' . $new_name;
        } else {
            $dest_path = $new_path . '/' . $this->file_name;
        }

        return copy($this->file_path, $dest_path);
    }

    public function move($new_path, $new_name = null)
    {
        if ($this->copy($new_path, $new_name)) {
            $this->delete();

            return true;
        }

        return false;
    }

    public function delete()
    {
        if (unlink($this->file_path)) {

            return true;
        } else {
            throw new FException('Can`t delete file');
        }
    }

}
