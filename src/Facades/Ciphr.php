<?php

namespace spkm\Ciphr\Facades;

use Illuminate\Support\Facades\Facade;
use Saloon\Http\Request;
use Saloon\Http\Response;
use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\CiphrPaginator;
use spkm\Ciphr\CiphrService;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Resources\AbsenceCodesResource;
use spkm\Ciphr\Resources\AbsenceDetailsResource;
use spkm\Ciphr\Resources\AbsenceReasonsResource;
use spkm\Ciphr\Resources\AbsencesResource;
use spkm\Ciphr\Resources\AuditDataResource;
use spkm\Ciphr\Resources\AuditLogsResource;
use spkm\Ciphr\Resources\AuthenticationLogsResource;
use spkm\Ciphr\Resources\JobDetailsResource;
use spkm\Ciphr\Resources\LeavingReasonsResource;
use spkm\Ciphr\Resources\LocationsResource;
use spkm\Ciphr\Resources\OrganisationDetailsResource;
use spkm\Ciphr\Resources\OrgChartResource;
use spkm\Ciphr\Resources\PersonDetailsResource;
use spkm\Ciphr\Resources\PersonPhotosResource;
use spkm\Ciphr\Resources\QualificationCategoriesResource;
use spkm\Ciphr\Resources\QualificationLevelsResource;
use spkm\Ciphr\Resources\QualificationsResource;
use spkm\Ciphr\Resources\QualificationSubjectsResource;
use spkm\Ciphr\Resources\TitlesResource;
use spkm\Ciphr\Resources\TrainingResource;
use spkm\Ciphr\Resources\WorkPatternShiftsResource;
use spkm\Ciphr\Resources\WorkPatternsResource;

/**
 * @method static AbsenceCodesResource absenceCodes()
 * @method static AbsenceDetailsResource absenceDetails()
 * @method static AbsenceReasonsResource absenceReasons()
 * @method static AbsencesResource absences()
 * @method static AuditDataResource auditData()
 * @method static AuditLogsResource auditLogs()
 * @method static AuthenticationLogsResource authenticationLogs()
 * @method static JobDetailsResource jobDetails()
 * @method static LeavingReasonsResource leavingReasons()
 * @method static LocationsResource locations()
 * @method static OrgChartResource orgChart()
 * @method static OrganisationDetailsResource organisationDetails()
 * @method static PersonDetailsResource personDetails()
 * @method static PersonPhotosResource personPhotos()
 * @method static QualificationCategoriesResource qualificationCategories()
 * @method static QualificationLevelsResource qualificationLevels()
 * @method static QualificationSubjectsResource qualificationSubjects()
 * @method static QualificationsResource qualifications()
 * @method static TitlesResource titles()
 * @method static TrainingResource training()
 * @method static WorkPatternShiftsResource workPatternShifts()
 * @method static WorkPatternsResource workPatterns()
 * @method static CiphrConnector connection(string $name)
 * @method static Response send(Request $request)
 * @method static CiphrPaginator paginate(Request $request, ?int $perPage = null)
 * @method static Builder<\spkm\Ciphr\Data\Data> from(string $path, ?string $resultKey = null, string $sortKey = 'ID')
 * @method static Response get(string $path, array<string, mixed> $query = [])
 * @method static Response post(string $path, array<string, mixed>|\JsonSerializable $body = [], array<string, mixed> $query = [])
 * @method static Response put(string $path, array<string, mixed>|\JsonSerializable $body = [], array<string, mixed> $query = [])
 * @method static Response delete(string $path, array<string, mixed> $query = [])
 *
 * @see CiphrService
 * @see CiphrConnector
 */
class Ciphr extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'ciphr';
    }
}
