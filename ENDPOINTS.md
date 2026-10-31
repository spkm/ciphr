# Endpoints

Every call the package makes to Ciphr.

## Resources

| Call | Returns |
|---|---|
| `Ciphr::absenceCodes()` | a builder over `AbsenceCode` records: `get()`, `first()`, `last()`, `count()`, `lazy()`, `find($id)` |
| `Ciphr::absenceDetails()` | a builder over `AbsenceDetail` records: `get()`, `first()`, `last()`, `count()`, `lazy()`, `find($id)` (by filtering) |
| `Ciphr::absenceDetails()->forAbsence($absenceId)` | `Collection` of `AbsenceDetail` |
| `Ciphr::absenceReasons()` | a builder over `AbsenceReason` records: `get()`, `first()`, `last()`, `count()`, `lazy()`, `find($id)` |
| `Ciphr::absences()` | a builder over `Absence` records: `get()`, `first()`, `last()`, `count()`, `lazy()`, `find($id)`, `create()`, `update()`, `delete()` |
| `Ciphr::absences()->withDetail($id)` | `Absence` with its `Details`, or null |
| `Ciphr::absences()->details()` | the `AbsenceDetail` builder |
| `Ciphr::auditData()` | a builder over `AuditData` records: `get()`, `first()`, `last()`, `count()`, `lazy()`, `find($id)` (by filtering) |
| `Ciphr::auditLogs()` | a builder over `AuditLog` records: `get()`, `first()`, `last()`, `count()`, `lazy()`, `find($id)` (by filtering) |
| `Ciphr::authenticationLogs()` | a builder over `AuthenticationLog` records: `get()`, `first()`, `last()`, `count()`, `lazy()`, `find($id)` (by filtering) |
| `Ciphr::jobDetails()` | a builder over `JobDetail` records: `get()`, `first()`, `last()`, `count()`, `lazy()`, `find($id)` |
| `Ciphr::leavingReasons()` | a builder over `LeavingReason` records: `get()`, `first()`, `last()`, `count()`, `lazy()`, `find($id)` |
| `Ciphr::locations()` | a builder over `Location` records: `get()`, `first()`, `last()`, `count()`, `lazy()`, `find($id)`, `create()`, `update()` |
| `Ciphr::orgChart()->get()` | `Collection` of `OrgChartDetail` |
| `Ciphr::organisationDetails()` | a builder over `OrganisationDetail` records: `get()`, `first()`, `last()`, `count()`, `lazy()`, `find($id)` |
| `Ciphr::personDetails()` | a builder over `PersonDetail` records: `get()`, `first()`, `last()`, `count()`, `lazy()`, `find($id)`, `update()` |
| `Ciphr::personDetails()->jobPay($perId)` | a builder over the person's `JobPay` records: `get()`, `find($id)`, `create()`, `update()` |
| `Ciphr::personDetails()->qualifications($perId)` | a builder over the person's `PersonQualification` records: `get()`, `find($id)` |
| `Ciphr::personPhotos()` | a builder over `PersonPhoto` records: `get()`, `first()`, `last()`, `count()`, `lazy()`, `find($id)` |
| `Ciphr::qualificationCategories()` | a builder over `QualificationCategory` records: `get()`, `first()`, `last()`, `count()`, `lazy()`, `find($id)` (by filtering) |
| `Ciphr::qualificationLevels()` | a builder over `QualificationLevel` records: `get()`, `first()`, `last()`, `count()`, `lazy()`, `find($id)` (by filtering) |
| `Ciphr::qualificationSubjects()` | a builder over `QualificationSubject` records: `get()`, `first()`, `last()`, `count()`, `lazy()`, `find($id)` (by filtering) |
| `Ciphr::qualifications()` | a builder over `Qualification` records: `get()`, `first()`, `last()`, `count()`, `lazy()`, `find($id)` |
| `Ciphr::titles()` | a builder over `Title` records: `get()`, `first()`, `last()`, `count()`, `lazy()`, `find($id)` (by filtering) |
| `Ciphr::training()` | a builder over `Training` records: `get()`, `first()`, `last()`, `count()`, `lazy()`, `find($id)`, `create()`, `update()` |
| `Ciphr::workPatternShifts()` | a builder over `WorkPatternShift` records: `get()`, `first()`, `last()`, `count()`, `lazy()`, `find($id)` |
| `Ciphr::workPatterns()` | a builder over `WorkPattern` records: `get()`, `first()`, `last()`, `count()`, `lazy()`, `find($id)` |
| `Ciphr::from('/Path')` | a builder over any other end node, returning arrays |

## Requests

| Request | Method | Path | Returns |
|---|---|---|---|
| `Absences\GetAbsencesRequest` | GET | `/Absence` | `Collection` of `Absence` (or `int` with `count`) |
| `Absences\GetAbsenceRequest` | GET | `/Absence/{id}` | `Absence` |
| `Absences\GetAbsenceWithDetailRequest` | GET | `/Absence/{id}/WithDetail` | `Absence` |
| `Absences\CreateAbsenceRequest` | POST | `/Absence` | `Absence` |
| `Absences\UpdateAbsenceRequest` | PUT | `/Absence/{id}` | `Absence` or null |
| `Absences\DeleteAbsenceRequest` | DELETE | `/Absence/{id}` | null |
| `Absences\GetAbsenceDetailsRequest` | GET | `/AbsenceDetail` | `Collection` of `AbsenceDetail` (or `int` with `count`) |
| `Absences\GetAbsenceDetailsForAbsenceRequest` | GET | `/AbsenceDetail/{absenceId}` | `Collection` of `AbsenceDetail` |
| `Absences\GetAbsenceCodesRequest` | GET | `/AbsenceCode` | `Collection` of `AbsenceCode` (or `int` with `count`) |
| `Absences\GetAbsenceCodeRequest` | GET | `/AbsenceCode/{id}` | `AbsenceCode` |
| `Absences\GetAbsenceReasonsRequest` | GET | `/AbsenceReason` | `Collection` of `AbsenceReason` (or `int` with `count`) |
| `Absences\GetAbsenceReasonRequest` | GET | `/AbsenceReason/{id}` | `AbsenceReason` |
| `Audit\GetAuditDataRequest` | GET | `/AuditData` | `Collection` of `AuditData` (or `int` with `count`) |
| `Audit\GetAuditLogsRequest` | GET | `/AuditLog` | `Collection` of `AuditLog` (or `int` with `count`) |
| `Audit\GetAuthenticationLogsRequest` | GET | `/AuthenticationLog` | `Collection` of `AuthenticationLog` (or `int` with `count`) |
| `Jobs\GetJobDetailsRequest` | GET | `/JobDetail` | `Collection` of `JobDetail` (or `int` with `count`) |
| `Jobs\GetJobDetailRequest` | GET | `/JobDetail/{id}` | `JobDetail` |
| `Jobs\GetJobPaysRequest` | GET | `/PersonDetail/{perId}/JobPay` | `Collection` of `JobPay` |
| `Jobs\GetJobPayRequest` | GET | `/PersonDetail/{perId}/JobPay/{id}` | `JobPay` |
| `Jobs\CreateJobPayRequest` | POST | `/PersonDetail/{perId}/JobPay` | `JobPay` |
| `Jobs\UpdateJobPayRequest` | PUT | `/PersonDetail/{perId}/JobPay/{id}` | `JobPay` or null |
| `Jobs\GetLeavingReasonsRequest` | GET | `/LeavingReason` | `Collection` of `LeavingReason` (or `int` with `count`) |
| `Jobs\GetLeavingReasonRequest` | GET | `/LeavingReason/{id}` | `LeavingReason` |
| `Locations\GetLocationsRequest` | GET | `/Location` | `Collection` of `Location` (or `int` with `count`) |
| `Locations\GetLocationRequest` | GET | `/Location/{id}` | `Location` |
| `Locations\CreateLocationRequest` | POST | `/Location` | `Location` |
| `Locations\UpdateLocationRequest` | PUT | `/Location/{id}` | `Location` or null |
| `Organisation\GetOrganisationDetailsRequest` | GET | `/OrganisationDetail` | `Collection` of `OrganisationDetail` (or `int` with `count`) |
| `Organisation\GetOrganisationDetailRequest` | GET | `/OrganisationDetail/{id}` | `OrganisationDetail` |
| `Organisation\GetOrgChartDetailRequest` | GET | `/OrgChartDetail` | `Collection` of `OrgChartDetail` |
| `People\GetPersonDetailsRequest` | GET | `/PersonDetail` | `Collection` of `PersonDetail` (or `int` with `count`) |
| `People\GetPersonDetailRequest` | GET | `/PersonDetail/{id}` | `PersonDetail` |
| `People\UpdatePersonDetailRequest` | PUT | `/PersonDetail/{id}` | `PersonDetail` or null |
| `People\GetPersonPhotosRequest` | GET | `/PersonPhoto` | `Collection` of `PersonPhoto` (or `int` with `count`) |
| `People\GetPersonPhotoRequest` | GET | `/PersonPhoto/{id}` | `PersonPhoto` |
| `People\GetPersonQualificationsRequest` | GET | `/PersonDetail/{perId}/PersonQualification` | `Collection` of `PersonQualification` |
| `People\GetPersonQualificationRequest` | GET | `/PersonDetail/{perId}/PersonQualification/{id}` | `PersonQualification` |
| `Qualifications\GetQualificationsRequest` | GET | `/Qualification` | `Collection` of `Qualification` (or `int` with `count`) |
| `Qualifications\GetQualificationRequest` | GET | `/Qualification/{id}` | `Qualification` |
| `Qualifications\GetQualificationCategoriesRequest` | GET | `/QualificationCategory` | `Collection` of `QualificationCategory` (or `int` with `count`) |
| `Qualifications\GetQualificationLevelsRequest` | GET | `/QualificationLevel` | `Collection` of `QualificationLevel` (or `int` with `count`) |
| `Qualifications\GetQualificationSubjectsRequest` | GET | `/QualificationSubject` | `Collection` of `QualificationSubject` (or `int` with `count`) |
| `Titles\GetTitlesRequest` | GET | `/Title` | `Collection` of `Title` (or `int` with `count`) |
| `Training\GetTrainingRecordsRequest` | GET | `/Training` | `Collection` of `Training` (or `int` with `count`) |
| `Training\GetTrainingRecordRequest` | GET | `/Training/{id}` | `Training` |
| `Training\CreateTrainingRecordRequest` | POST | `/Training` | `Training` |
| `Training\UpdateTrainingRecordRequest` | PUT | `/Training/{id}` | `Training` or null |
| `WorkPatterns\GetWorkPatternsRequest` | GET | `/WorkPattern` | `Collection` of `WorkPattern` (or `int` with `count`) |
| `WorkPatterns\GetWorkPatternRequest` | GET | `/WorkPattern/{id}` | `WorkPattern` |
| `WorkPatterns\GetWorkPatternShiftsRequest` | GET | `/WorkPatternShift` | `Collection` of `WorkPatternShift` (or `int` with `count`) |
| `WorkPatterns\GetWorkPatternShiftRequest` | GET | `/WorkPatternShift/{id}` | `WorkPatternShift` |

Notes:

- Ciphr answers a GET for an id it doesn't have with a 500 and no error list, not a 404. The package reads that as `NotFoundException`, so `find()` still returns null.
- An entity the key doesn't expose answers "Unauthorized access to API method" as a 400; the package throws `UnauthorizedException` for it. An entity with no readable properties answers "Unable to retrieve the data" (a 400 `InvalidRequestException`).
- A portal's own custom fields (names beginning `CF`) are not on the models, as their names differ per portal. Read them with `select()` through `Ciphr::from('/PersonDetail')`, or from `Ciphr::send(...)->json()`.
- `Training` records belong to a person through `PerID`; Ciphr's own example filters them with `?TrainingPerID=[eq]24`, so check which name your portal accepts.
- `AbsenceDetail/{id}` takes an absence id and returns that absence's detail rows, so `absenceDetails()->find()` filters by `ID` instead.
