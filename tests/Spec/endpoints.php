<?php

/*
 * Every request class, with the arguments to build it, the HTTP method and path it must send, and what dto() returns.
 * RequestsSpecTest checks the classes against this list and that no class under src/Requests is missing from it.
 */

use spkm\Ciphr\Data;
use spkm\Ciphr\Requests;

return [
    'GetAbsencesRequest' => [Requests\Absences\GetAbsencesRequest::class, [], 'GET', '/Absence', 'list', Data\Absence::class, 'Absence', 'ID', false, true],
    'GetAbsenceRequest' => [Requests\Absences\GetAbsenceRequest::class, [7], 'GET', '/Absence/7', 'one', Data\Absence::class, 'Absence', null, false, false],
    'GetAbsenceWithDetailRequest' => [Requests\Absences\GetAbsenceWithDetailRequest::class, [7], 'GET', '/Absence/7/WithDetail', 'one', Data\Absence::class, 'Absence', null, false, false],
    'CreateAbsenceRequest' => [Requests\Absences\CreateAbsenceRequest::class, [['Notes' => 'x']], 'POST', '/Absence', 'one', Data\Absence::class, 'Absence', null, true, false],
    'UpdateAbsenceRequest' => [Requests\Absences\UpdateAbsenceRequest::class, [7, ['Notes' => 'x']], 'PUT', '/Absence/7', 'one', Data\Absence::class, 'Absence', null, true, false],
    'DeleteAbsenceRequest' => [Requests\Absences\DeleteAbsenceRequest::class, [7], 'DELETE', '/Absence/7', 'none', null, '', null, false, false],
    'GetAbsenceDetailsRequest' => [Requests\Absences\GetAbsenceDetailsRequest::class, [], 'GET', '/AbsenceDetail', 'list', Data\AbsenceDetail::class, 'AbsenceDetail', 'ID', false, true],
    'GetAbsenceDetailsForAbsenceRequest' => [Requests\Absences\GetAbsenceDetailsForAbsenceRequest::class, [3], 'GET', '/AbsenceDetail/3', 'list', Data\AbsenceDetail::class, 'AbsenceDetail', 'ID', false, true],
    'GetAbsenceCodesRequest' => [Requests\Absences\GetAbsenceCodesRequest::class, [], 'GET', '/AbsenceCode', 'list', Data\AbsenceCode::class, 'AbsenceCode', 'ID', false, true],
    'GetAbsenceCodeRequest' => [Requests\Absences\GetAbsenceCodeRequest::class, [7], 'GET', '/AbsenceCode/7', 'one', Data\AbsenceCode::class, 'AbsenceCode', null, false, false],
    'GetAbsenceReasonsRequest' => [Requests\Absences\GetAbsenceReasonsRequest::class, [], 'GET', '/AbsenceReason', 'list', Data\AbsenceReason::class, 'AbsenceReason', 'ID', false, true],
    'GetAbsenceReasonRequest' => [Requests\Absences\GetAbsenceReasonRequest::class, [7], 'GET', '/AbsenceReason/7', 'one', Data\AbsenceReason::class, 'AbsenceReason', null, false, false],
    'GetAuditDataRequest' => [Requests\Audit\GetAuditDataRequest::class, [], 'GET', '/AuditData', 'list', Data\AuditData::class, 'AuditData', 'AuditLogID', false, true],
    'GetAuditLogsRequest' => [Requests\Audit\GetAuditLogsRequest::class, [], 'GET', '/AuditLog', 'list', Data\AuditLog::class, 'AuditLog', 'ID', false, true],
    'GetAuthenticationLogsRequest' => [Requests\Audit\GetAuthenticationLogsRequest::class, [], 'GET', '/AuthenticationLog', 'list', Data\AuthenticationLog::class, 'AuthenticationLog', 'ID', false, true],
    'GetJobDetailsRequest' => [Requests\Jobs\GetJobDetailsRequest::class, [], 'GET', '/JobDetail', 'list', Data\JobDetail::class, 'JobDetail', 'ID', false, true],
    'GetJobDetailRequest' => [Requests\Jobs\GetJobDetailRequest::class, [7], 'GET', '/JobDetail/7', 'one', Data\JobDetail::class, 'JobDetail', null, false, false],
    'GetJobPaysRequest' => [Requests\Jobs\GetJobPaysRequest::class, [16], 'GET', '/PersonDetail/16/JobPay', 'list', Data\JobPay::class, 'JobPay', 'ID', false, true],
    'GetJobPayRequest' => [Requests\Jobs\GetJobPayRequest::class, [16, 7], 'GET', '/PersonDetail/16/JobPay/7', 'one', Data\JobPay::class, 'JobPay', null, false, false],
    'CreateJobPayRequest' => [Requests\Jobs\CreateJobPayRequest::class, [16, ['Notes' => 'x']], 'POST', '/PersonDetail/16/JobPay', 'one', Data\JobPay::class, 'JobPay', null, true, false],
    'UpdateJobPayRequest' => [Requests\Jobs\UpdateJobPayRequest::class, [16, 7, ['Notes' => 'x']], 'PUT', '/PersonDetail/16/JobPay/7', 'one', Data\JobPay::class, 'JobPay', null, true, false],
    'GetLeavingReasonsRequest' => [Requests\Jobs\GetLeavingReasonsRequest::class, [], 'GET', '/LeavingReason', 'list', Data\LeavingReason::class, 'LeavingReason', 'ID', false, true],
    'GetLeavingReasonRequest' => [Requests\Jobs\GetLeavingReasonRequest::class, [7], 'GET', '/LeavingReason/7', 'one', Data\LeavingReason::class, 'LeavingReason', null, false, false],
    'GetLocationsRequest' => [Requests\Locations\GetLocationsRequest::class, [], 'GET', '/Location', 'list', Data\Location::class, 'Location', 'ID', false, true],
    'GetLocationRequest' => [Requests\Locations\GetLocationRequest::class, [7], 'GET', '/Location/7', 'one', Data\Location::class, 'Location', null, false, false],
    'CreateLocationRequest' => [Requests\Locations\CreateLocationRequest::class, [['Notes' => 'x']], 'POST', '/Location', 'one', Data\Location::class, 'Location', null, true, false],
    'UpdateLocationRequest' => [Requests\Locations\UpdateLocationRequest::class, [7, ['Notes' => 'x']], 'PUT', '/Location/7', 'one', Data\Location::class, 'Location', null, true, false],
    'GetOrganisationDetailsRequest' => [Requests\Organisation\GetOrganisationDetailsRequest::class, [], 'GET', '/OrganisationDetail', 'list', Data\OrganisationDetail::class, 'OrganisationDetail', 'ID', false, true],
    'GetOrganisationDetailRequest' => [Requests\Organisation\GetOrganisationDetailRequest::class, [7], 'GET', '/OrganisationDetail/7', 'one', Data\OrganisationDetail::class, 'OrganisationDetail', null, false, false],
    'GetOrgChartDetailRequest' => [Requests\Organisation\GetOrgChartDetailRequest::class, [], 'GET', '/OrgChartDetail', 'list', Data\OrgChartDetail::class, 'OrgChartDetail', null, false, false],
    'GetPersonDetailsRequest' => [Requests\People\GetPersonDetailsRequest::class, [], 'GET', '/PersonDetail', 'list', Data\PersonDetail::class, 'PersonDetail', 'ID', false, true],
    'GetPersonDetailRequest' => [Requests\People\GetPersonDetailRequest::class, [7], 'GET', '/PersonDetail/7', 'one', Data\PersonDetail::class, 'PersonDetail', null, false, false],
    'UpdatePersonDetailRequest' => [Requests\People\UpdatePersonDetailRequest::class, [7, ['Notes' => 'x']], 'PUT', '/PersonDetail/7', 'one', Data\PersonDetail::class, 'PersonDetail', null, true, false],
    'GetPersonPhotosRequest' => [Requests\People\GetPersonPhotosRequest::class, [], 'GET', '/PersonPhoto', 'list', Data\PersonPhoto::class, 'PersonPhoto', 'ID', false, true],
    'GetPersonPhotoRequest' => [Requests\People\GetPersonPhotoRequest::class, [7], 'GET', '/PersonPhoto/7', 'one', Data\PersonPhoto::class, 'PersonPhoto', null, false, false],
    'GetPersonQualificationsRequest' => [Requests\People\GetPersonQualificationsRequest::class, [16], 'GET', '/PersonDetail/16/PersonQualification', 'list', Data\PersonQualification::class, 'PersonQualification', 'ID', false, true],
    'GetPersonQualificationRequest' => [Requests\People\GetPersonQualificationRequest::class, [16, 7], 'GET', '/PersonDetail/16/PersonQualification/7', 'one', Data\PersonQualification::class, 'PersonQualification', null, false, false],
    'GetQualificationsRequest' => [Requests\Qualifications\GetQualificationsRequest::class, [], 'GET', '/Qualification', 'list', Data\Qualification::class, 'Qualification', 'ID', false, true],
    'GetQualificationRequest' => [Requests\Qualifications\GetQualificationRequest::class, [7], 'GET', '/Qualification/7', 'one', Data\Qualification::class, 'Qualification', null, false, false],
    'GetQualificationCategoriesRequest' => [Requests\Qualifications\GetQualificationCategoriesRequest::class, [], 'GET', '/QualificationCategory', 'list', Data\QualificationCategory::class, 'QualificationCategory', 'ID', false, true],
    'GetQualificationLevelsRequest' => [Requests\Qualifications\GetQualificationLevelsRequest::class, [], 'GET', '/QualificationLevel', 'list', Data\QualificationLevel::class, 'QualificationLevel', 'ID', false, true],
    'GetQualificationSubjectsRequest' => [Requests\Qualifications\GetQualificationSubjectsRequest::class, [], 'GET', '/QualificationSubject', 'list', Data\QualificationSubject::class, 'QualificationSubject', 'ID', false, true],
    'GetTitlesRequest' => [Requests\Titles\GetTitlesRequest::class, [], 'GET', '/Title', 'list', Data\Title::class, 'Title', 'ID', false, true],
    'GetTrainingRecordsRequest' => [Requests\Training\GetTrainingRecordsRequest::class, [], 'GET', '/Training', 'list', Data\Training::class, 'Training', 'ID', false, true],
    'GetTrainingRecordRequest' => [Requests\Training\GetTrainingRecordRequest::class, [7], 'GET', '/Training/7', 'one', Data\Training::class, 'Training', null, false, false],
    'CreateTrainingRecordRequest' => [Requests\Training\CreateTrainingRecordRequest::class, [['Notes' => 'x']], 'POST', '/Training', 'one', Data\Training::class, 'Training', null, true, false],
    'UpdateTrainingRecordRequest' => [Requests\Training\UpdateTrainingRecordRequest::class, [7, ['Notes' => 'x']], 'PUT', '/Training/7', 'one', Data\Training::class, 'Training', null, true, false],
    'GetWorkPatternsRequest' => [Requests\WorkPatterns\GetWorkPatternsRequest::class, [], 'GET', '/WorkPattern', 'list', Data\WorkPattern::class, 'WorkPattern', 'ID', false, true],
    'GetWorkPatternRequest' => [Requests\WorkPatterns\GetWorkPatternRequest::class, [7], 'GET', '/WorkPattern/7', 'one', Data\WorkPattern::class, 'WorkPattern', null, false, false],
    'GetWorkPatternShiftsRequest' => [Requests\WorkPatterns\GetWorkPatternShiftsRequest::class, [], 'GET', '/WorkPatternShift', 'list', Data\WorkPatternShift::class, 'WorkPatternShift', 'ID', false, true],
    'GetWorkPatternShiftRequest' => [Requests\WorkPatterns\GetWorkPatternShiftRequest::class, [7], 'GET', '/WorkPatternShift/7', 'one', Data\WorkPatternShift::class, 'WorkPatternShift', null, false, false],
];
