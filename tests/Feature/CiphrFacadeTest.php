<?php

use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\CiphrService;
use spkm\Ciphr\Facades\Ciphr;
use spkm\Ciphr\Resources\HasResources;

it('hints every resource and every public connector method for the IDE', function () {
    $docblock = (string) (new ReflectionClass(Ciphr::class))->getDocComment();
    $resources = array_map(fn (ReflectionMethod $method) => $method->getName(), (new ReflectionClass(HasResources::class))->getMethods(ReflectionMethod::IS_PUBLIC));
    $service = array_map(fn (ReflectionMethod $method) => $method->getName(), array_filter((new ReflectionClass(CiphrService::class))->getMethods(ReflectionMethod::IS_PUBLIC), fn (ReflectionMethod $method) => ! str_starts_with($method->getName(), '__')));
    $connector = ['send', 'paginate', 'from', 'get', 'post', 'put', 'delete'];

    foreach ([...$resources, ...$service, ...$connector] as $method) {
        expect($docblock)->toContain(" $method(");
    }

    foreach ($connector as $method) {
        expect(method_exists(CiphrConnector::class, $method))->toBeTrue();
    }
});
