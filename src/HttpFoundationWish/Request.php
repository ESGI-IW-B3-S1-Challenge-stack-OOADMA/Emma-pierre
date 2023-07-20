<?php

namespace App\HttpFoundationWish;

class Request
{
    public InputBag $request;
    public InputBag $query;
    public InputBag $files;

    /**
     * Sets the parameters for this request.
     *
     * This method also re-initializes all properties.
     *
     * @param array $query The GET parameters
     * @param array $request The POST parameters
     * @param array $attributes The request attributes (parameters parsed from the PATH_INFO, ...)
     * @param array $cookies The COOKIE parameters
     * @param array $files The FILES parameters
     * @param array $server The SERVER parameters
     * @param string|resource|null $content The raw body data
     *
     * @return void
     */
    public function __construct(array $query = [], array $request = [], array $files = [])
    {
        $this->request = new InputBag($request);
        $this->query = new InputBag($query);
        //$this->attributes = new ParameterBag($attributes);
        //$this->cookies = new InputBag($cookies);
        $this->files = new InputBag($files);
        //$this->server = new ServerBag($server);
        //$this->headers = new HeaderBag($this->server->getHeaders());
    }
}