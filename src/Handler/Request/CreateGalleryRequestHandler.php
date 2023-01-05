<?php

namespace App\Handler\Request;

use stdClass;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;

class CreateGalleryRequestHandler
{
    public static function handle(Request $request): stdClass
    {
        $inputBag = self::handleInputBag($request->request);
        $fileBag = self::handleFileBag($request->files);
        return (object) ['inputBag' => $inputBag, 'fileBag' => $fileBag];
    }

    private static function handleInputBag(InputBag $inputBag): stdClass
    {
        $input = $inputBag->all();
        $inputResult = [];

        $title = $input['title'] ?? '';
        $published = $input['published'] ?? '0';
        $pictures = $input['pictures'] ?? [];
        $tag = $input['tag'] ?? '';

        $inputResult['title'] = $title;
        $inputResult['published'] = $published;
        $inputResult['pictures'] = $pictures;
        $inputResult['tag'] = $tag;

        return (object) $inputResult;
    }

    private static function handleFileBag(FileBag $fileBag): stdClass
    {
        $files = $fileBag->all();
        $filesResult = [];

        $cover = $files['cover'] ?? null;
        $uploads = $files['uploads'] ?? [];

        $filesResult['cover'] = $cover;
        $filesResult['uploads'] = $uploads;

        return (object) $filesResult;
    }
}