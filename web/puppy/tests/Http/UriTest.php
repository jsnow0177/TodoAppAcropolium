<?php
namespace Puppy\Tests\Http;

use PHPUnit\Framework\TestCase;
use Puppy\Http\Uri;

class UriTest extends TestCase{

    /**
     * @var Uri
     */
    protected $uri;

    public function setUp(): void
    {
        $this->uri = new Uri();
    }

    public function testModifyScheme(){
        $this->assertEquals('', $this->uri->GetScheme());

        $uri = $this->uri->WithScheme('http');

        $this->assertNotSame($uri, $this->uri);
        $this->assertEquals('http', $uri->GetScheme());
    }

    public function testModifyUser(){
        $this->assertEquals('', $this->uri->GetUser());

        $uri = $this->uri->WithUser('username');

        $this->assertNotSame($uri, $this->uri);
        $this->assertEquals('username', $uri->GetUser());
    }

    public function testModifyPass(){
        $this->assertEquals('', $this->uri->GetPass());

        $uri =  $this->uri->WithPass('1234');

        $this->assertNotSame($uri, $this->uri);
        $this->assertEquals('1234', $uri->GetPass());
    }

    /**
     * @requires testModifyUser
     * @requires testModifyPass
     */
    public function testAuthorityBuildsCorrect(){
        $this->assertEquals('', $this->uri->GetAuthority());

        $uri = $this->uri->WithUser('username')->WithPass('1234');

        $this->assertNotSame($uri, $this->uri);
        $this->assertEquals('username:1234', $uri->GetAuthority());
    }

    public function testModifyHost(){
        $this->assertEquals('', $this->uri->GetHost());

        $uri = $this->uri->WithHost('example.com');

        $this->assertNotSame($uri, $this->uri);
        $this->assertEquals('example.com', $uri->GetHost());
    }

    public function testModifyPort(){
        $this->assertEquals(null, $this->uri->GetPort());

        $uri = $this->uri->WithPort(80);

        $this->assertNotSame($uri, $this->uri);
        $this->assertEquals(80, $uri->GetPort());
    }

    public function testModifyPath(){
        $this->assertEquals('/', $this->uri->GetPath());

        $uri = $this->uri->WithPath('/test/path/');

        $this->assertNotSame($uri, $this->uri);
        $this->assertEquals('/test/path/', $uri->GetPath());
    }

    public function testModifyQuery(){
        $this->assertEquals('', $this->uri->GetQuery());

        $uri = $this->uri->WithQuery('a=1&b=2');

        $this->assertNotSame($uri, $this->uri);
        $this->assertEquals('a=1&b=2', $uri->GetQuery());
    }

    public function testModifyFragment(){
        $this->assertEquals('', $this->uri->GetFragment());

        $uri = $this->uri->WithFragment('foo=bar&baz=qux');

        $this->assertNotSame($uri, $this->uri);
        $this->assertEquals('foo=bar&baz=qux', $uri->GetFragment());
    }

    /**
     * @requires testModifyScheme
     * @requires testModifyUser
     * @requires testModifyPass
     * @requires testModifyHost
     * @requires testModifyPort
     * @requires testModifyPath
     * @requires testModifyQuery
     * @requires testModifyFragment
     */
    public function testUriCanBeRepresentedAsString(){
        $this->assertEquals('/', (string)$this->uri);

        $uri = $this->uri
            ->WithScheme('https')
            ->WithUser('username')
            ->WithPass('1234')
            ->WithHost('example.com')
            ->WithPort(80)
            ->WithPath('///foo/bar/')
            ->WithQuery('a=1&b=2')
            ->WithFragment('foo=bar&baz=qux');

        $this->assertNotSame($uri, $this->uri);
        $this->assertEquals('https://username:1234@example.com:80/foo/bar/?a=1&b=2#foo=bar&baz=qux', (string)$uri);
    }

    public function testUriCanBeCreatedFromString(){
        $uriString = 'http://username:1234@example.com:80/foo/bar/?a=1&b=2#foo=bar&baz=qux';

        $uri = Uri::FromUriString($uriString);

        $this->assertEquals('http', $uri->GetScheme());
        $this->assertEquals('username', $uri->GetUser());
        $this->assertEquals('1234', $uri->GetPass());
        $this->assertEquals('username:1234', $uri->GetAuthority());
        $this->assertEquals('example.com', $uri->GetHost());
        $this->assertEquals(80, $uri->GetPort());
        $this->assertEquals('/foo/bar/', $uri->GetPath());
        $this->assertEquals('a=1&b=2', $uri->GetQuery());
        $this->assertEquals('foo=bar&baz=qux', $uri->GetFragment());
        $this->assertEquals($uriString, (string)$uri);
    }

}