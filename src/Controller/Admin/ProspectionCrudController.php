<?php

namespace App\Controller\Admin;

use App\Entity\Enum\ProspectionStatusEnum;
use App\Entity\Enum\ProspectionTypeEnum;
use App\Entity\Prospection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProspectionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Prospection::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('name');
        yield ChoiceField::new('status')->setChoices(ProspectionStatusEnum::cases());
        yield ChoiceField::new('type')->setChoices(ProspectionTypeEnum::cases());
        yield AssociationField::new('user');
        yield AssociationField::new('contact');
        yield AssociationField::new('actions')->hideOnForm();
        yield TextEditorField::new('comment');
    }
}
