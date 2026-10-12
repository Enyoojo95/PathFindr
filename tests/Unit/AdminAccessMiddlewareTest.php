<?php

namespace Tests\Unit;

use App\Http\Middleware\AdminAccessMiddleware;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class AdminAccessMiddlewareTest extends TestCase
{
    public function test_students_are_denied_admin_routes(): void
    {
        $request = Request::create('/admin/questions');
        $request->setUserResolver(fn () => new User(['role' => User::ROLE_STUDENT]));

        try {
            app(AdminAccessMiddleware::class)->handle($request, fn () => response('ok'));
            $this->fail('A student was allowed through the admin middleware.');
        } catch (HttpException $exception) {
            $this->assertSame(403, $exception->getStatusCode());
        }
    }

    public function test_admin_roles_are_allowed_through_admin_middleware(): void
    {
        foreach ([User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN] as $role) {
            $request = Request::create('/admin/questions');
            $request->setUserResolver(fn () => new User(['role' => $role]));

            $response = app(AdminAccessMiddleware::class)->handle(
                $request,
                fn () => response('ok')
            );

            $this->assertInstanceOf(Response::class, $response);
            $this->assertSame('ok', $response->getContent());
        }
    }

    public function test_suspended_admins_are_denied_admin_routes(): void
    {
        $request = Request::create('/admin/questions');
        $admin = new User(['role' => User::ROLE_ADMIN]);
        $admin->setAttribute('is_suspended', true);
        $request->setUserResolver(fn () => $admin);

        $this->expectException(HttpException::class);
        app(AdminAccessMiddleware::class)->handle($request, fn () => response('ok'));
    }
}