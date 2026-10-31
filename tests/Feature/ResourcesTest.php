<?php

use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use spkm\Ciphr\Data\Absence;
use spkm\Ciphr\Data\AbsenceDetail;
use spkm\Ciphr\Data\JobPay;
use spkm\Ciphr\Data\OrgChartDetail;
use spkm\Ciphr\Data\PersonQualification;
use spkm\Ciphr\Requests\Absences\GetAbsenceDetailsForAbsenceRequest;
use spkm\Ciphr\Requests\Absences\GetAbsenceWithDetailRequest;
use spkm\Ciphr\Requests\Jobs\CreateJobPayRequest;
use spkm\Ciphr\Requests\Jobs\GetJobPayRequest;
use spkm\Ciphr\Requests\Jobs\GetJobPaysRequest;
use spkm\Ciphr\Requests\Jobs\UpdateJobPayRequest;
use spkm\Ciphr\Requests\Organisation\GetOrgChartDetailRequest;
use spkm\Ciphr\Requests\People\GetPersonQualificationRequest;
use spkm\Ciphr\Requests\People\GetPersonQualificationsRequest;
use spkm\Ciphr\Requests\People\UpdatePersonDetailRequest;
use spkm\Ciphr\Tests\Helpers\FakeCiphr;

it('reaches a person\'s job and pay records under their id', function () {
    $mockClient = FakeCiphr::respondWith([
        GetJobPaysRequest::class => FakeCiphr::list('JobPay', [['ID' => 1056, 'PerID' => 10, 'BasicPay' => '30000.00']]),
        GetJobPayRequest::class => MockResponse::make(['ID' => 1056, 'PerID' => 10]),
        CreateJobPayRequest::class => MockResponse::make(['ID' => 1057], 201),
        UpdateJobPayRequest::class => MockResponse::make('', 204),
    ]);
    $jobPay = FakeCiphr::connector()->personDetails()->jobPay(10);

    expect($jobPay->where('Leaver', false)->get()->first())->toBeInstanceOf(JobPay::class)
        ->and($jobPay->get()->first()?->BasicPay)->toBe(30000.0)
        ->and($jobPay->find(1056)?->ID)->toBe(1056)
        ->and($jobPay->create(['BasicPay' => 1])?->ID)->toBe(1057)
        ->and($jobPay->update(1056, ['BasicPay' => 2]))->toBeNull()
        ->and(FakeCiphr::urisReceived($mockClient, GetJobPaysRequest::class)[0])->toBe(FakeCiphr::BASE_URL.'/PersonDetail/10/JobPay?Leaver=%5Beq%5Dfalse')
        ->and(FakeCiphr::received($mockClient, GetJobPayRequest::class)->getUrl())->toBe(FakeCiphr::BASE_URL.'/PersonDetail/10/JobPay/1056')
        ->and(FakeCiphr::received($mockClient, CreateJobPayRequest::class)->getUrl())->toBe(FakeCiphr::BASE_URL.'/PersonDetail/10/JobPay')
        ->and(FakeCiphr::received($mockClient, UpdateJobPayRequest::class)->body()?->all())->toBe(['ID' => 1056, 'BasicPay' => 2]);
});

it('reaches a person\'s qualifications under their id', function () {
    $mockClient = FakeCiphr::respondWith([
        GetPersonQualificationsRequest::class => FakeCiphr::list('PersonQualification', [['ID' => 3, 'PerID' => 16]]),
        GetPersonQualificationRequest::class => MockResponse::make(['ID' => 3]),
    ]);
    $qualifications = FakeCiphr::connector()->personDetails()->qualifications(16);

    expect($qualifications->get()->first())->toBeInstanceOf(PersonQualification::class)
        ->and($qualifications->find(3)?->ID)->toBe(3)
        ->and(FakeCiphr::received($mockClient, GetPersonQualificationsRequest::class)->getUrl())->toBe(FakeCiphr::BASE_URL.'/PersonDetail/16/PersonQualification')
        ->and(FakeCiphr::received($mockClient, GetPersonQualificationRequest::class)->getUrl())->toBe(FakeCiphr::BASE_URL.'/PersonDetail/16/PersonQualification/3')
        ->and(fn () => $qualifications->create([]))->toThrow(LogicException::class);
});

it('updates a person with their id in the body', function () {
    $mockClient = FakeCiphr::respondWith([UpdatePersonDetailRequest::class => MockResponse::make(['ID' => 180, 'WorkEmailAddress' => 'someone@example.test'])]);

    $person = FakeCiphr::connector()->personDetails()->update(180, ['WorkEmailAddress' => 'someone@example.test']);

    expect($person?->WorkEmailAddress)->toBe('someone@example.test')
        ->and(FakeCiphr::received($mockClient, UpdatePersonDetailRequest::class)->body()?->all())->toBe(['ID' => 180, 'WorkEmailAddress' => 'someone@example.test']);
});

it('fetches an absence with its details, or null', function () {
    $mockClient = FakeCiphr::respondWith([GetAbsenceWithDetailRequest::class => function ($request) {
        return str_ends_with($request->getUrl(), '/7/WithDetail')
            ? MockResponse::make(['ID' => 7, 'Days' => '2', 'Details' => [['ID' => 70, 'AbsenceID' => 7, 'Days' => '1'], ['ID' => 71, 'Days' => '1']]])
            : MockResponse::make('', 404);
    }]);
    $absences = FakeCiphr::connector()->absences();

    $absence = $absences->withDetail(7);

    expect($absence)->toBeInstanceOf(Absence::class)
        ->and($absence->Days)->toBe(2.0)
        ->and($absence->Details)->toHaveCount(2)
        ->and($absence->Details[0])->toBeInstanceOf(AbsenceDetail::class)
        ->and($absence->Details[0]->AbsenceID)->toBe(7)
        ->and($absences->withDetail(8))->toBeNull()
        ->and(FakeCiphr::urisReceived($mockClient, GetAbsenceWithDetailRequest::class))->toBe([FakeCiphr::BASE_URL.'/Absence/7/WithDetail', FakeCiphr::BASE_URL.'/Absence/8/WithDetail']);
});

it('fetches the detail rows of one absence', function () {
    $mockClient = FakeCiphr::respondWith([GetAbsenceDetailsForAbsenceRequest::class => FakeCiphr::list('AbsenceDetail', [['ID' => 70], ['ID' => 71]])]);

    $details = FakeCiphr::connector()->absences()->details()->forAbsence(7);

    expect($details)->toBeInstanceOf(Collection::class)->toHaveCount(2)
        ->and($details[1])->toBeInstanceOf(AbsenceDetail::class)
        ->and(FakeCiphr::received($mockClient, GetAbsenceDetailsForAbsenceRequest::class)->getUrl())->toBe(FakeCiphr::BASE_URL.'/AbsenceDetail/7');
});

it('fetches the org charts and decodes their tables', function () {
    FakeCiphr::respondWith([GetOrgChartDetailRequest::class => FakeCiphr::list('OrgChartDetail', [['JsonTable' => '[{"ID":1,"Name":"Head"}]'], ['JsonTable' => '[]']])]);

    $charts = FakeCiphr::connector()->orgChart()->get();

    expect($charts)->toBeInstanceOf(Collection::class)->toHaveCount(2)
        ->and($charts[0])->toBeInstanceOf(OrgChartDetail::class)
        ->and($charts[0]->table())->toBe([['ID' => 1, 'Name' => 'Head']])
        ->and($charts[1]->table())->toBe([])
        ->and((new OrgChartDetail(JsonTable: 'not json'))->table())->toBe([])
        ->and((new OrgChartDetail)->table())->toBe([]);
});
