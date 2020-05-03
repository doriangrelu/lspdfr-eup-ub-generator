<?php

namespace App\Form;

use App\Validator\FileIni;
use App\Validator\FileIniValidator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\File;

class EUPUBConfigType extends AbstractType
{

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ini_file', FileType::class, [
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '100M',
                        'maxSizeMessage' => 'Votre fichier ne doit pas dépasser les 100 méga.',
                        'mimeTypes' => FileIniValidator::ALLOWED,
                        'mimeTypesMessage' => 'Veuillez fournir un fichier INI',
                    ]),
                    new FileIni(),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'form',
            'action' => $this->urlGenerator->generate('eup.upload'),
            'method' => 'POST',
        ]);
    }
}
