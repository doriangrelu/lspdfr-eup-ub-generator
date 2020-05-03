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
            return false;
        }

        if ($value->isExecutable()) {
            $this->context->buildViolation('Le fichier ne doit pas être executable')
                ->addViolation();
            return false;
        }

        if ($value->getClientOriginalExtension() !== 'ini' && $value->getClientOriginalExtension() !== 'txt') {
            $this->context->buildViolation("Le fichier doit avoir l'extension INI ou TXT")
                ->addViolation();
            return false;
        }

        /* if (!in_array($value->getMimeType(), self::ALLOWED)) {
             $this->context->buildViolation('Veuillez fournir un fichier INI ou TXT')
                 ->addViolation();
             return false;
         }*/

        return true;
    }
}
