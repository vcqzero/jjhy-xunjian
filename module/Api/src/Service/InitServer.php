<?php
namespace Api\Service;

class InitServer
{
    public function __construct(
        UserManager $UserManager
        )
    {
        $UserManager->createSuperAdmin();
    }
}
