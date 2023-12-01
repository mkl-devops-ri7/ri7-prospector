<?php

namespace App\Entity\Enum;

enum ProspectionTypeEnum: string
{
    case ColdProspection = 'cold_prospecting';
    case UnsolicitedJobApplication = 'unsolicited_job_application';
    case JobApplication = 'job_application';
}
