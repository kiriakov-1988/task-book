<?php

namespace App\Model;


class FileUploader
{
    /**
     * MIME-тип картинок, доступных для загрузки.
     */
    const FILE_MIME_TYPE = CONFIG_MIME_TYPE;

    const UPLOAD_DIR = '/images/';

    public function uploadFile():array
    {
        if (!$_FILES['userfile']['error']) {

            if (mb_stristr(FileUploader::FILE_MIME_TYPE, $_FILES['userfile']['type'])) {

                // на всякий случай обработка строки в названии файла ...
                $inputFileName = htmlspecialchars(trim($_FILES['userfile']['name']));

                $uniquer = new UniqueName($inputFileName);

                $uniqueFileName = $uniquer->getUniqueFileName();

                $uploadFile = __DIR__ . '/../../public' . self::UPLOAD_DIR . $uniqueFileName;

                if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadFile)) {

                    // TODO обработка файлов с большим расширением !!

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
}