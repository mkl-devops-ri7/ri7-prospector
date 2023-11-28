<?php

namespace App\Entity\Enum;

enum ProspectionStatusEnum: string
{
    case Draft = 'draft';
    case InProgress = 'in_progress';
    case Failed = 'failed';
    case Success = 'success';
    case Abandoned = 'abandoned';
}
