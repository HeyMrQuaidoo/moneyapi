<?php
namespace App\Http\Controllers;

/**
 * Class ApiController
 *
 * @package App\Http\Controllers
 *
 * @SWG\Swagger(
*      schemes={"http"},
 *     host="localhost:9000",
 *     basePath="/v1/api",
 *     @SWG\Info(
 *         version="0.0.1",
 *         title="API Documentation",
 *         description="Server endpoint for Gunther project. Each of the resources below has multiple endpoints which allows end users and developers to access the Gunther API. This server allows you can use the api key  ""special-key"" to test the authorization filters",
 *         termsOfService="https://dacardinal.azurewebsites.net",
 *         @SWG\Contact(
 *             email=""
 *         ),
 *         @SWG\License(
 *             name="",
 *             url=""
 *         )
 *     ),
 *     @SWG\ExternalDocumentation(
 *         description="",
 *         url=""
 *     )
 * )
 */
class ApiController extends Controller {
}