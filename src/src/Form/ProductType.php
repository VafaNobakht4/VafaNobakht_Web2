<?php

namespace App\Form;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function mapCategoryList($p)
    {
        $arr = new \stdClass();
        foreach ($p as $key => $value) {
            $arr->{$value->getName()} = $key;
        }
        return $arr;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name', null, array('attr' => ['placeholder' => 'Name of phone', 'class' => 'w-20 mx-2 my-2 rounded']))
            ->add('Detail', null, array('attr' => ['placeholder' => 'Detail of phone', 'class' => 'w-20 mx-2 my-2 rounded']))
            ->add('Price', null, array('attr' => ['placeholder' => 'Price of phone', 'class' => 'w-20 mx-2 my-2 rounded']))
            ->add('brochure', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload jpg-png image',
                    ])
                ],
            ])->add('Category',
                ChoiceType::class, ['choices' => json_decode(json_encode($this->mapCategoryList($this->categoryRepository->findAll())), true),
                ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
