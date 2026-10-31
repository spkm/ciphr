<?php

namespace spkm\Ciphr\Resources;

/**
 * A resource for every entity Ciphr documents, on the connector and so on the Ciphr facade.
 */
trait HasResources
{
    public function absenceCodes(): AbsenceCodesResource
    {
        return new AbsenceCodesResource($this);
    }

    public function absenceDetails(): AbsenceDetailsResource
    {
        return new AbsenceDetailsResource($this);
    }

    public function absenceReasons(): AbsenceReasonsResource
    {
        return new AbsenceReasonsResource($this);
    }

    public function absences(): AbsencesResource
    {
        return new AbsencesResource($this);
    }

    public function auditData(): AuditDataResource
    {
        return new AuditDataResource($this);
    }

    public function auditLogs(): AuditLogsResource
    {
        return new AuditLogsResource($this);
    }

    public function authenticationLogs(): AuthenticationLogsResource
    {
        return new AuthenticationLogsResource($this);
    }

    public function jobDetails(): JobDetailsResource
    {
        return new JobDetailsResource($this);
    }

    public function leavingReasons(): LeavingReasonsResource
    {
        return new LeavingReasonsResource($this);
    }

    public function locations(): LocationsResource
    {
        return new LocationsResource($this);
    }

    public function orgChart(): OrgChartResource
    {
        return new OrgChartResource($this);
    }

    public function organisationDetails(): OrganisationDetailsResource
    {
        return new OrganisationDetailsResource($this);
    }

    public function personDetails(): PersonDetailsResource
    {
        return new PersonDetailsResource($this);
    }

    public function personPhotos(): PersonPhotosResource
    {
        return new PersonPhotosResource($this);
    }

    public function qualificationCategories(): QualificationCategoriesResource
    {
        return new QualificationCategoriesResource($this);
    }

    public function qualificationLevels(): QualificationLevelsResource
    {
        return new QualificationLevelsResource($this);
    }

    public function qualificationSubjects(): QualificationSubjectsResource
    {
        return new QualificationSubjectsResource($this);
    }

    public function qualifications(): QualificationsResource
    {
        return new QualificationsResource($this);
    }

    public function titles(): TitlesResource
    {
        return new TitlesResource($this);
    }

    public function training(): TrainingResource
    {
        return new TrainingResource($this);
    }

    public function workPatternShifts(): WorkPatternShiftsResource
    {
        return new WorkPatternShiftsResource($this);
    }

    public function workPatterns(): WorkPatternsResource
    {
        return new WorkPatternsResource($this);
    }
}
