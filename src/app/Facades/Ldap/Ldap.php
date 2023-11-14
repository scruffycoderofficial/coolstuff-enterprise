<?php

 namespace App\Facades\Ldap;

 use Illuminate\Support\Facades\Facade;

 /**
  * Class Ldap
  *
  * @package App\Facades\Ldap
  */
 class Ldap extends Facade
 {
     /**
      * Get the registered name of the component.
      *
      * @return string
      */
     protected static function getFacadeAccessor(): string
     {
         return 'ldap';
     }
 }
