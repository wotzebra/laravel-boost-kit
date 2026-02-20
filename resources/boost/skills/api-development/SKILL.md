---
name: api-development
description: Implement an API endpoint based on a Swagger/OpenAPI specification from start to finish, including routing, controller, request validation, resources, and tests.
---

# API Development

## When to Apply

Activate this skill when:

- Implementing new API endpoints
- Adjusting existing API endpoints

## Process

Implementing or adjusting an API endpoint involves following steps:

### Step 1: Analyze OpenAPI Documentation:

- Review the relevant endpoint definition in the correct OpenAPI JSON file. Look at all available files in the `resources/swagger/` directory.
- Identify HTTP method, URL path, request parameters, request body schema, and response schema.
- Note any authentication requirements and response status codes. If the endpoint in spec has disabled global security, then ask to user if the endpoint should be public or if it just should support both authenticated and unauthenticated requests.
- Never make changes to the OpenAPI specification yourself. If you find any issues or missing information in the specification, ask for clarification and request that the specification be updated by the appropriate team before proceeding with implementation. The OpenAPI specification is the source of truth for API development, and all code should be implemented to match the specification exactly.

### Step 2: Write Laravel code based on the OpenAPI definition:

- Define the route in `routes/api.php` with the correct HTTP method and URL path. If the api endpoint is not part of the main api (e.g. integration-api), then define the route in the separate routes file that maches the name of the api (e.g. `routes/integration-api.php`).
- Create a Controller method that corresponds to the endpoint. The controller name should match the route (e.g. `ChatMessageController` for `/chat/{chat}/messages` route). The controller method name should be one of the following based on HTTP verb of the route and what is returned in the response: `index`, `show`, `store()`, `update()`, or `destroy()`. If the endpoint requires authentication, use `#[CurrentUser] User $user` to access the authenticated user instead of `$request->user()`. The Controller should have thin logic, delegating complex operations to Actions.
- Create a Form Request class for validating incoming requests based on the OpenAPI schema. The Form Request should be named similar to the controller method it corresponds to (e.g. `StoreChatMessageRequest` for `ChatMessageController@store`). Use array-based validation rules and include helper methods for computed values.
- Create a Policy class if the endpoint requires authorization. Define methods that correspond to the actions (e.g., `view()`, `create()`, `update()`, `delete()`) and implement the necessary logic to allow or deny access based on the authenticated user and the resource.
- Conditionally create a DTO class if the request body has 4+ fields or nested objects. The DTO should use constructor property promotion and include the authenticated user if applicable. The DTO should also have a static `from{FormRequest}()` method to create an instance from the Form Request.
- Conditionally create an Action class for complex write operations (POST/PUT/PATCH/DELETE with business logic). The Action should extend `Wotz\EnhancedActions\Action` and use the `#[DatabaseTransaction]` attribute.
- Create the necessary API Resource classes if the endpoint returns JSON data. The API Resource should format dates as ISO 8601 using `->format('c')` method, use `->whenLoaded()` for relationships, use `->when()` for conditional attributes, and cast numeric types appropriately.

### Step 3: Write PHPUnit Tests:

- Add tests for the API endpoint in an appropriate test class in a `tests/Http/` directory. The test class should match the the route and controller name (e.g. `GetChatMessagesTest` for `GET /chat/{chat}/messages` endpoint).
- Ensure you have tests covering the happy path, validation errors, authorization, and edge cases. But do not very obvious validation rules (e.g. do not test that a integer field in a request body throws a validation error if it contains a string).
- Use the available test traits (e.g. use the `AuthenticateAsOAuthUser` trait to authenticate a user when testing api that requires authentication).
- Use `$response->assertValidRequest()` (or `$response->assertInvalidRequest()`), `$response->assertValidResponse()` (or `$response->assertInvalidResponse()`) in each test to validate the API endpoint against the OpenAPI schema.
- Initialize test data in the `setUp()` method using faker. This ensures that test data that is used across multiple tests is not repeated in each test method.
- Ensure the first test in a test file uses the `$response->assertExactJsonStructure()` assertion to validate the response structure and make sure the API does not return more data than expected.
- For a complex Action class or an Action class that will be used in multiple endpoints, write dedicated tests for that Action class to cover business logic and edge cases and assert in the API tests that the Action is used by mocking it with `passthru()`. That way, you can ensure that the Action is used and tested without having to repeat all the business logic tests in each API test.
- Add dedicated tests for the Policy class to cover all authorization scenarios (use the `\Wotz\TestHelpers\MakesGateAssertions` trait). In the API tests, assert that the policy is applied and correctly handled by adding a test that mocks that the policy is allowed and a test that mocks that the policy is denied (use the `\Wotz\TestHelpers\MocksPolicies` trait).

If during any of these steps you have questions about the OpenAPI specification, the existing codebase, or how to implement a specific feature, ask for clarification before proceeding. It's important to ensure that you have a clear understanding of the requirements and the existing codebase before making changes.
