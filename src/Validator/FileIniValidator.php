<?php

namespace App\Validator;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class FileIniValidator extends ConstraintValidator
{
    const ALLOWED = [
        'text/plain',
        'text/ini',
        'application/octet-stream',
        'application/x-wine-extension-ini',
    ];

    /**
     * @param UploadedFile $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\FileIni */

        if (null === $value || '' === $value) {
            return;
        }

        if ($value->isExecutable()) {
            dd('exec');
            $this->context->buildViolation('Le fichier ne doit pas être executable')
                ->addViolation();
        }

        if ($value->getClientOriginalExtension() !== 'ini' && $value->getClientOriginalExtension() !== 'txt') {
            dd('ext');
            $this->context->buildViolation('Veuillez fournir un fichier INI ou TXT')
                ->addViolation();
        }

        if (!in_array($value->getMimeType(), self::ALLOWED)) {
            dd('mime');
            $this->context->buildViolation('Veuillez fournir un fichier INI ou TXT')
                ->addViolation();
        }

    }
}
