<?php

namespace APP\CommerceML2;

interface StorageInterface
{
    public function getItem($key);

    public function getItems($keys);

    public function setItem($key, $value);

    public function clearStorage();
}
