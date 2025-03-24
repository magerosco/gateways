<?php


namespace App\Traits;


use Illuminate\Http\Request;

trait ApiVersion
{
    protected array $supportedVersions = ['v1', 'v2'];

    public function getApiVersion(Request $request): string
    {
        $version = strtolower($request->header('Accept-Version') ?? $request->query('version') ?? 'v1');

        return in_array($version, $this->supportedVersions) ? $version : 'v1';
    }
}
