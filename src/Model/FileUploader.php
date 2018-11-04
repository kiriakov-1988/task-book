<?php

namespace App\Model;


class FileUploader
{
    /**
     * MIME-тип картинок, доступных для загрузки.
     */
    const FILE_MIME_TYPE = CONFIG_MIME_TYPE;

    const UPLOAD_DIR = CONFIG_UPLOAD_DIR;

    const MAX_WIDTH  = 320;
    const MAX_HEIGHT = 240;

    public function uploadFile():array
    {
        if (!$_FILES['userfile']['error']) {

            if (mb_stristr(FileUploader::FILE_MIME_TYPE, $_FILES['userfile']['type'])) {

                // на всякий случай обработка строки в названии файла ...
                $inputFileName = htmlspecialchars(trim($_FILES['userfile']['name']));

                if (mb_strlen($inputFileName) > 70) {
                    // тут с запасом 70, так как к названию еще добавляет уникальная метка длиной гдето 20 символов
                    return [
                        'success' => false,
                        'message' => 'Длина указаного файла превышает допустимую дину - 70 символов.'
                    ];
                }

                $uniquer = new UniqueName($inputFileName);

                $uniqueFileName = $uniquer->getUniqueFileName();

                $uploadFile = __DIR__ . '/../../public' . self::UPLOAD_DIR . $uniqueFileName;

                if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadFile)) {

                    if (!$this->checkImageSize($uploadFile)) {
                        if (!$this->resizeImage($uploadFile)) {
                            // на крайний случай (например, отсутствие библиотеки GD) будет выведно следующая ошибка
                            // а сам исходный фал удален

                            unlink($uploadFile);

                            return [
                                'success' => false,
                                'message' => 'При загрузке файла произошла ошибка - ширина/высота загружаемого изображения превышает допустимые значения - ' . self::MAX_WIDTH . ' x ' . self::MAX_HEIGHT
                            ];
                        }
                    }

                    return [
                        'success'    => true,
                        'uploadFileName' => $uniqueFileName
                    ];

                } else {
                    $message = 'Возникла ошибка при загрузке файла.';
                }
            } else {
                $message = 'Возникла ошибка - выбран неподдерживаемый тип файла';
            }

        } else {
            $message = 'Возникла ошибка при загрузке файла - код - ' . $_FILES['userfile']['error'];
        }

        return [
            'success' => false,
            'message' => $message
        ];
    }

    private function checkImageSize($fileName):bool
    {
        list($widthOrig, $heightOrig) = getimagesize($fileName);

        if ($widthOrig > self::MAX_WIDTH) {
            return false;
        }

        if ($heightOrig > self::MAX_HEIGHT) {
            return false;
        }

        return true;
    }

    private function resizeImage($fileName): bool
    {
        $width  = self::MAX_WIDTH;
        $height = self::MAX_HEIGHT;

        list($widthOrig, $heightOrig, $type) = getimagesize($fileName);

        $types = array("", "gif", "jpeg", "png");

        $extension = $types[$type];

        if ($extension) {

            echo '<pre>';
            var_dump($extension);

            $ratioOrig = $widthOrig/$heightOrig;

            if ($width/$height > $ratioOrig) {
                $width = $height * $ratioOrig;
            } else {
                $height = $width / $ratioOrig;
            }

            $funcFrom = 'imagecreatefrom'.$extension;
            $img_i = $funcFrom($fileName);

            $img_o = imagecreatetruecolor($width, $height);

            // делает изначально фон прозрачным
            $transparent = imagecolorallocatealpha($img_o, 0, 0, 0, 127);
            imagefill($img_o, 0, 0, $transparent);
            imagesavealpha($img_o, true);

            imagecopyresampled($img_o, $img_i,0, 0, 0, 0, $width, $height, $widthOrig, $heightOrig);

            $funcTo = 'image'.$extension;

            return $funcTo($img_o, $fileName);
        }

        return false;
    }
}