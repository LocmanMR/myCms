<?php
declare(strict_types=1);

namespace App\Form;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Article|null $article */
        $article = $options['data'] ?? null;

        $cannotEditAuthor = $article && $article->getId() && $article->isPublished();

        $builder
            ->add('title', null, [
                'label' => 'Заголовок',
            ])
            ->add('description', null, [
                'label' => 'Описание',
            ])
            ->add('body', null, [
                'label' => 'Содержание',
            ])
            ->add('keywords', null, [
                'label' => 'Ключевые слова',
            ])
            ->add('author', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return sprintf('%s (id: %d)', $user->getFirstName(), $user->getId());
                },
                'placeholder' => 'Выберите автора статьи',
                'choices' => $this->userRepository->findAllSortedByName(),
                'label' => 'Автор',
                'disabled' => $cannotEditAuthor,
            ])
        ;

        if ($options['enable_published_at']) {
            $builder
                ->add('publishedAt', null, [
                    'widget' => 'single_text',
                    'label' => 'Дата публикации',
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'enable_published_at' => false,
        ]);
    }
}
