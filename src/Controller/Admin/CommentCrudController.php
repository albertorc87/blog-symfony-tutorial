<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Crud::PAGE_DETAIL, Action::NEW)
            ->disable(Crud::PAGE_DETAIL, Action::EDIT)
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Comment')
            ->setEntityLabelInPlural('Comments')
            ->setSearchFields(['user.username', 'user.email', 'content'])
            ->setDefaultSort(['created_at' => 'DESC'])
        ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('user.username', 'User'),
            TextField::new('post.title', 'Post'),
            DateTimeField::new('created_at', 'Created At'),
            TextEditorField::new('content'),
        ];
    }

}
