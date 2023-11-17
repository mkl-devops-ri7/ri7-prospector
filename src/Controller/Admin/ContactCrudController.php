<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id');
        yield  TextField::new('email');
        yield  TextField::new('job');
        yield  TextField::new('firstname');
        yield  TextField::new('lastname');
        yield  TextField::new('linkedinProfilUrl');
        yield  TextField::new('phoneNumber');
        yield  TextEditorField::new('description');
    }
}
