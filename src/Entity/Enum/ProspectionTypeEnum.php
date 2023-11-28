<?php

namespace App\Entity\Enum;

enum ProspectionTypeEnum: string
{
    case ColdProspection = 'cold prospecting';
    case UnsolicitedJobApplication = 'unsolicited job application';
    case JobApplication = 'job application';
}
