<?php

namespace ArtMeBlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class SongType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('songName', FileType::class, array('label' => 'Song (MP3, WAV or AIFF file)'))
            ->add('description', TextType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ArtMeBlogBundle\Entity\Song',
        ));
    }

    public function getName()
    {
        return 'art_me_blog_bundle_song_type';
    }
}
